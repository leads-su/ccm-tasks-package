<?php

namespace ConsulConfigManager\Tasks\Services\TaskRunner\Actions;

use Throwable;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Artisan;
use ConsulConfigManager\Tasks\Enums\ActionType;
use ConsulConfigManager\Tasks\Exceptions\InvalidLocalTaskCommandException;

/**
 * Class LocalAction
 * @package ConsulConfigManager\Tasks\Services\TaskRunner\Actions
 */
class LocalAction extends AbstractAction
{
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
            'run_as'            =>  $this->getRunAs(),
            'fail_on_error'     =>  $this->getFailOnError(),
            'type'              =>  ActionType::LOCAL,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function createNewAction(): AbstractAction
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function retrieveOutput(): void
    {
        $availableExecutors = $this->resolveAvailableCommandHandlers();
        $availableHandlers = array_keys($availableExecutors);

        if (!in_array($this->getRunAs(), $availableHandlers)) {
            throw new InvalidLocalTaskCommandException($this->getRunAs(), $availableHandlers);
        }

        $this->outputAppend([
            'message'   =>  'Local Task execution started',
            'timestamp' =>  time(),
        ]);

        $this->outputAppend([
            'message'   =>  'Local Task execution started',
            'timestamp' =>  time(),
        ]);

        [$exitCode, $message, $time, $command] = call_user_func([$this, $availableExecutors[$this->getRunAs()]]);

        $this->outputAppend([
            'message'   =>  sprintf('Command: `%s`', $command),
            'timestamp' =>  $time,
        ]);

        $this->outputAppend([
            'message'   =>  $message,
            'timestamp' =>  $time,
        ]);

        $this->outputAppend([
            'message'   =>  'Local Task execution finished',
            'timestamp' =>  time(),
        ]);

        call_user_func_array($this->getOnResultHandler(), [
            $exitCode,
            $this->getOutput(),
        ]);
    }

    /**
     * Resolve list of available command handlers
     * @return array
     */
    private function resolveAvailableCommandHandlers(): array
    {
        $filteredMethods = array_filter(get_class_methods($this), function (string $method): bool {
            $startsWith = Str::startsWith($method, 'handle');
            $endsWith = Str::endsWith($method, 'CommandExecution');
            return $startsWith && $endsWith;
        });

        $formattedMethods = array_map(function (string $method): array {
            $handler = (string) Str::of($method)
                ->replace('handle', '')
                ->replace('CommandExecution', '')
                ->lower();
            return [$handler, $method];
        }, $filteredMethods);

        $methods = [];

        foreach ($formattedMethods as $methodData) {
            [$handler, $method] = $methodData;
            $methods[$handler] = $method;
        }

        return $methods;
    }

    /**
     * Handle `curl` command execution
     * @return array
     */
    private function handleCurlCommandExecution(): array
    {
        $arg = $this->getArguments();
        array_unshift($arg, 'curl');
        $arg[] = $this->getCommand();

        $process = new Process($arg);
        $exitCode = $process->run();
        $message = $process->getOutput();
        return [
            $exitCode,
            $message,
            time(),
            implode(' ', $arg),
        ];
    }

    /**
     * Handle `artisan` command execution
     * @return array
     */
    private function handleArtisanCommandExecution(): array
    {
        try {
            $exitCode = Artisan::call($this->getCommand(), $this->getArguments());
            $message = Artisan::output();
        } catch (Throwable $throwable) {
            $exitCode = 255;
            $message = $throwable->getMessage();
        }
        return [
            $exitCode,
            $message,
            time(),
            trim(sprintf('php artisan %s %s', $this->getCommand(), implode(' ', $this->getArguments()))),
        ];
    }
}
