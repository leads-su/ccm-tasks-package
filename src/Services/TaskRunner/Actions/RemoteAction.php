<?php

namespace ConsulConfigManager\Tasks\Services\TaskRunner\Actions;

use Exception;
use React\Http\Browser;
use React\Promise\Deferred;
use React\Socket\Connector;
use Illuminate\Support\Facades\Http;
use Clue\React\EventSource\EventSource;
use Clue\React\EventSource\MessageEvent;
use ConsulConfigManager\Tasks\Enums\ActionType;

/**
 * Class RemoteAction
 * @package ConsulConfigManager\Tasks\Services\TaskRunner\Actions
 */
class RemoteAction extends AbstractAction
{
    /**
     * Host for runner which will process this task
     * @var string
     */
    private string $runnerHost;

    /**
     * Port for runner which will process this task
     * @var int
     */
    private int $runnerPort;

    /**
     * Value for `use_sudo` query parameter
     * @var bool
     * @default false
     */
    private bool $useSudo = false;

    /**
     * Value for `working_dir` query parameter
     * @var string|null
     * @default null
     */
    private ?string $workingDirectory = null;

    /**
     * RemoteTask constructor.
     * @param string $runnerHost
     * @param int $runnerPort
     * @return void
     */
    public function __construct(string $runnerHost, int $runnerPort)
    {
        $this->runnerHost = $runnerHost;
        $this->runnerPort = $runnerPort;
    }

    /**
     * Get runner host
     * @return string
     */
    public function getRunnerHost(): string
    {
        return $this->runnerHost;
    }

    /**
     * Get runner port
     * @return int
     */
    public function getRunnerPort(): int
    {
        return $this->runnerPort;
    }

    /**
     * Get working directory
     * @return string|null
     */
    public function getWorkingDirectory(): ?string
    {
        return $this->workingDirectory;
    }

    /**
     * Set working directory
     * @param string|null $workingDirectory
     * @return $this
     */
    public function setWorkingDirectory(?string $workingDirectory = null): self
    {
        $this->workingDirectory = $workingDirectory;
        return $this;
    }

    /**
     * Get information about whether we are using sudo
     * @return bool
     */
    public function getUseSudo(): bool
    {
        return $this->useSudo;
    }

    /**
     * Set information about whether we are using sudo
     * @param bool $useSudo
     * @return $this
     */
    public function setUseSudo(bool $useSudo = true): self
    {
        $this->useSudo = $useSudo;
        return $this;
    }

    /**
     * Generate stream identifier
     * @return string
     */
    public function generateStreamIdentifier(): string
    {
        return hash('sha256', sprintf(
            '%s_%s_%s_%s_%s',
            $this->getExecutionIdentifier(),
            $this->getPipelineIdentifier(),
            $this->getTaskIdentifier(),
            $this->getActionIdentifier(),
            $this->getServerIdentifier(),
        ));
    }

    /**
     * Get task creation url on specified runner
     * @return string
     */
    public function getTaskCreationUrl(): string
    {
        return sprintf(
            'http://%s:%d/tasks/create',
            $this->getRunnerHost(),
            $this->getRunnerPort(),
        );
    }

    /**
     * Get task stream url on specified runner
     * @return string
     */
    public function getTaskStreamUrl(): string
    {
        return sprintf(
            'http://%s:%d/events/watch?stream=%s',
            $this->getRunnerHost(),
            $this->getRunnerPort(),
            $this->generateStreamIdentifier(),
        );
    }

    /**
     * Get task log url on specified runner
     * @return string
     */
    public function getTaskLogUrl(): string
    {
        return sprintf(
            'http://%s:%d/events/load?stream=%s',
            $this->getRunnerHost(),
            $this->getRunnerPort(),
            $this->generateStreamIdentifier(),
        );
    }

    /**
     * @inheritDoc
     */
    public function toActionArray(): array
    {
        return [
            'execution_id'      =>  $this->getExecutionIdentifier(),
            'pipeline_id'       =>  $this->getPipelineIdentifier(),
            'task_id'           =>  $this->getTaskIdentifier(),
            'action_id'         =>  $this->getActionIdentifier(),
            'server_id'         =>  $this->getServerIdentifier(),
            'command'           =>  $this->getCommand(),
            'arguments'         =>  $this->getArguments(),
            'working_dir'       =>  $this->getWorkingDirectory(),
            'run_as'            =>  $this->getRunAs(),
            'use_sudo'          =>  $this->getUseSudo(),
            'fail_on_error'     =>  $this->getFailOnError(),
            'type'              =>  ActionType::REMOTE,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function createNewAction(): AbstractAction
    {
        Http::post($this->getTaskCreationUrl(), $this->toActionArray());
        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function retrieveOutput(): void
    {
        $deferred = new Deferred();

        $connector = new Connector([
            'timeout'       =>  15.0,
        ]);
        $browser = new Browser($connector);
        $stream = new EventSource($this->getTaskStreamUrl(), $browser);

        $stream->on('message', function (MessageEvent $messageEvent) use ($deferred, $stream): void {
            $this->processExecutionOutput($deferred, $stream, $messageEvent);
        });

        $stream->on('error', function (Exception $exception) use ($deferred, $stream): void {
            $executionLog = $this->requestExecutionLog();
            foreach ($executionLog as $index => $entry) {
                $this->processExecutionOutput($deferred, $stream, $entry, $index);
            }
        });

        $deferred->promise()->done();
    }

    /**
     * Request execution log from runner
     * @return array
     */
    private function requestExecutionLog(): array
    {
        return Http::get($this->getTaskLogUrl())->json('data');
    }

    /**
     * Process provided execution output
     * @param Deferred $promise
     * @param EventSource $stream
     * @param MessageEvent|array $event
     * @param int|null $index
     * @return void
     */
    private function processExecutionOutput(Deferred $promise, EventSource $stream, MessageEvent|array $event, ?int $index = null): void
    {
        if ($event instanceof MessageEvent) {
            $response = json_decode($event->data, true);
            $message = $response['line'];
            $timestamp = (int) $response['timestamp'];
            $eventId = (int) $event->lastEventId;
        } else {
            $message = $event['line'];
            $timestamp = (int) $event['timestamp'];
            $eventId = (int) $index;
        }

        $this->outputAppendAtIndex($eventId, [
            'message'   =>  $message,
            'timestamp' =>  $timestamp,
        ]);

        $this->handleExitCode($promise, $message, $stream);
    }

    /**
     * Handle exit code received from execution on runner
     * @param Deferred $promise
     * @param string $message
     * @param EventSource $stream
     * @return void
     */
    private function handleExitCode(Deferred $promise, string $message, EventSource $stream): void
    {
        if (false !== stripos($message, 'exit status')) {
            $statusCode = intval(trim(str_replace('exit status', '', $message)));
            call_user_func_array($this->getOnResultHandler(), [
                $statusCode,
                $this->getOutput(),
            ]);
            $stream->close();
            $promise->resolve();
        }
    }
}
