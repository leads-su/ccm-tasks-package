<?php namespace ConsulConfigManager\Tasks\Services\TaskRunner\Tasks;

use Exception;
use React\Http\Browser;
use React\Promise\Deferred;
use React\Socket\Connector;
use Illuminate\Support\Facades\Http;
use Clue\React\EventSource\EventSource;
use Clue\React\EventSource\MessageEvent;

/**
 * Class RemoteTask
 * @package ConsulConfigManager\Tasks\Services\TaskRunner\Tasks
 */
class RemoteTask extends AbstractTask {

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
     * Value for `execution_id` query parameter
     * @var string
     */
    private string $execution;

    /**
     * Value for `pipeline_id` query parameter
     * @var string
     */
    private string $pipeline;

    /**
     * Value for `task_id` query parameter
     * @var string
     */
    private string $task;

    /**
     * Value for `action_id` query parameter
     * @var string
     */
    private string $action;

    /**
     * Value for `server_id` query parameter
     * @var string
     */
    private string $server;

    /**
     * Value for `command` query parameter
     * @var string
     */
    private string $command;

    /**
     * Value for `arguments` query parameter
     * @var array
     */
    private array $arguments = [];

    /**
     * Value for `run_as` query parameter
     * @var string|null
     * @default null
     */
    private ?string $runAs = null;

    /**
     * Value for `use_sudo` query parameter
     * @var bool
     * @default false
     */
    private bool $useSudo = false;

    /**
     * Value for `fail_on_error` query parameter
     * @var bool
     * @default true
     */
    private bool $failOnError = true;

    /**
     * Value for `working_dir` query parameter
     * @var string|null
     * @default null
     */
    private ?string $workingDirectory = null;

    /**
     * Task execution output
     * @var array
     */
    private array $output = [];


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
     * Get execution identifier
     * @return string
     */
    public function getExecutionIdentifier(): string
    {
        return $this->execution;
    }

    /**
     * Set execution identifier
     * @param string $identifier
     * @return $this
     */
    public function setExecutionIdentifier(string $identifier): RemoteTask
    {
        $this->execution = $identifier;
        return $this;
    }

    /**
     * Get pipeline identifier
     * @return string
     */
    public function getPipelineIdentifier(): string
    {
        return $this->pipeline;
    }

    /**
     * Set pipeline identifier
     * @param string $identifier
     * @return $this
     */
    public function setPipelineIdentifier(string $identifier): RemoteTask
    {
        $this->pipeline = $identifier;
        return $this;
    }

    /**
     * Get task identifier
     * @return string
     */
    public function getTaskIdentifier(): string
    {
        return $this->task;
    }

    /**
     * Set task identifier
     * @param string $identifier
     * @return $this
     */
    public function setTaskIdentifier(string $identifier): RemoteTask
    {
        $this->task = $identifier;
        return $this;
    }

    /**
     * Get action identifier
     * @return string
     */
    public function getActionIdentifier(): string
    {
        return $this->action;
    }

    /**
     * Set action identifier
     * @param string $identifier
     * @return $this
     */
    public function setActionIdentifier(string $identifier): RemoteTask
    {
        $this->action = $identifier;
        return $this;
    }

    /**
     * Get server identifier
     * @return string
     */
    public function getServerIdentifier(): string
    {
        return $this->server;
    }

    /**
     * Set server identifier
     * @param string $identifier
     * @return $this
     */
    public function setServerIdentifier(string $identifier): RemoteTask
    {
        $this->server = $identifier;
        return $this;
    }

    /**
     * Get command
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * Set command
     * @param string $command
     * @return $this
     */
    public function setCommand(string $command): RemoteTask
    {
        $this->command = $command;
        return $this;
    }

    /**
     * Get arguments
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Set arguments
     * @param array $arguments
     * @return $this
     */
    public function setArguments(array $arguments): RemoteTask
    {
        $this->arguments = $arguments;
        return $this;
    }

    /**
     * Get user under whom this task will be performed
     * @return string|null
     */
    public function getRunAs(): ?string
    {
        return $this->runAs;
    }

    /**
     * Set user under whom this task will be performed
     * @param string|null $runAs
     * @return $this
     */
    public function setRunAs(?string $runAs = null): RemoteTask
    {
        $this->runAs = $runAs;
        return $this;
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
    public function setWorkingDirectory(?string $workingDirectory = null): RemoteTask
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
    public function setUseSudo(bool $useSudo = true): RemoteTask
    {
        $this->useSudo = $useSudo;
        return $this;
    }

    /**
     * Get information about whether we should fail on error
     * @return bool
     */
    public function getFailOnError(): bool
    {
        return $this->failOnError;
    }

    /**
     * Set information about whether we should fail on error
     * @param bool $failOnError
     * @return $this
     */
    public function setFailOnError(bool $failOnError = true): RemoteTask
    {
        $this->failOnError = $failOnError;
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
     * @inheritDoc
     */
    public function toTaskArray(): array
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
        ];
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
     * Create new task on the remote runner
     * @return RemoteTask
     */
    protected function createNewTask(): RemoteTask
    {
        Http::post($this->getTaskCreationUrl(), $this->toTaskArray());
        return $this;
    }

    /**
     * Retrieve output from remote runner
     * @return void
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

        $this->output[$eventId] = [
            'message'   =>  $message,
            'timestamp' =>  $timestamp,
        ];

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
            call_user_func_array($this->onResult, [
                $statusCode,
                $this->output,
            ]);
            $stream->close();
            $promise->resolve();
        }
    }

}
