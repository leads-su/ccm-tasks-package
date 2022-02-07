<?php

namespace ConsulConfigManager\Tasks\Services\TaskRunner\Tasks;

use Closure;

/**
 * Class AbstractTask
 * @package ConsulConfigManager\Tasks\Services\TaskRunner\Tasks
 */
abstract class AbstractTask
{
    /**
     * Callback with logic for when task is finished
     * @var Closure
     */
    protected Closure $onResult;

    /**
     * Execute task on remote runner
     * @param Closure $onResult
     * @return void
     */
    public function execute(Closure $onResult): void
    {
        $this->onResult = $onResult;
        $this
            ->createNewTask()
            ->retrieveOutput();
    }

    /**
     * Convert class to task array
     * @return array
     */
    abstract public function toTaskArray(): array;

    /**
     * Create new task
     * @return $this
     */
    abstract protected function createNewTask(): self;

    /**
     * Retrieve output from remote runner
     * @return void
     */
    abstract protected function retrieveOutput(): void;
}
