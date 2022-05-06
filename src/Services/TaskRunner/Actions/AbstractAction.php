<?php

namespace ConsulConfigManager\Tasks\Services\TaskRunner\Actions;

use Closure;

/**
 * Class AbstractAction
 * @package ConsulConfigManager\Tasks\Services\TaskRunner\Actions
 */
abstract class AbstractAction
{
    /**
     * Callback with logic for when action is finished
     * @var Closure
     */
    private Closure $onResult;

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
     * Value for `fail_on_error` query parameter
     * @var bool
     * @default true
     */
    private bool $failOnError = true;

    /**
     * Task execution output
     * @var array
     */
    private array $output = [];

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
    public function setExecutionIdentifier(string $identifier): self
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
    public function setPipelineIdentifier(string $identifier): self
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
    public function setTaskIdentifier(string $identifier): self
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
    public function setActionIdentifier(string $identifier): self
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
    public function setServerIdentifier(string $identifier): self
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
    public function setCommand(string $command): self
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
    public function setArguments(array $arguments): self
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
    public function setRunAs(?string $runAs = null): self
    {
        $this->runAs = $runAs;
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
    public function setFailOnError(bool $failOnError = true): self
    {
        $this->failOnError = $failOnError;
        return $this;
    }

    /**
     * Get `onResult` handler
     * @return Closure
     */
    public function getOnResultHandler(): Closure
    {
        return $this->onResult;
    }

    /**
     * Get execution output
     * @return array
     */
    public function getOutput(): array
    {
        return $this->output;
    }

    /**
     * Execute action
     * @param Closure $onResult
     * @return void
     */
    public function execute(Closure $onResult): void
    {
        $this->onResult = $onResult;
        $this->createNewAction()
            ->retrieveOutput();
    }

    /**
     * Append data to output
     * @param array $value
     * @return $this
     */
    protected function outputAppend(array $value): self
    {
        $this->output[] = $value;
        return $this;
    }

    /**
     * Append data to output at given index
     * @param string|int $index
     * @param array $value
     * @return $this
     */
    protected function outputAppendAtIndex(string|int $index, array $value): self
    {
        $this->output[$index] = $value;
        return $this;
    }

    /**
     * Convert action class to array
     * @return array
     */
    abstract public function toActionArray(): array;

    /**
     * Create new action
     * @return $this
     */
    abstract protected function createNewAction(): self;

    /**
     * Retrieve output from action executor
     * @return void
     */
    abstract protected function retrieveOutput(): void;
}
