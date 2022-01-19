<?php

namespace ConsulConfigManager\Tasks\Services\TaskRunner;

use Illuminate\Support\Facades\DB;
use ConsulConfigManager\Tasks\Models\TaskExecution;
use ConsulConfigManager\Tasks\Models\ActionExecution;
use ConsulConfigManager\Tasks\Models\PipelineExecution;
use Illuminate\Contracts\Container\BindingResolutionException;
use ConsulConfigManager\Tasks\Services\TaskRunner\Manager\Resolver;

/**
 * Class Runner
 * @package ConsulConfigManager\Tasks\Services\TaskRunner
 */
class Runner
{
    /**
     * Runner event loop
     * @var EventLoop
     */
    private EventLoop $eventLoop;

    /**
     * Pipeline resolver
     * @var Resolver
     */
    private Resolver $resolver;

    /**
     * Runner constructor.
     * @param string|int $pipelineIdentifier
     * @param bool $withTruncate
     * @throws BindingResolutionException
     */
    public function __construct(string|int $pipelineIdentifier, bool $withTruncate = false)
    {
        if ($withTruncate) {
            $this->truncate();
        }
        $this->boot($pipelineIdentifier);
    }

    /**
     * Start runner
     * @return void
     */
    public function run(): void
    {
        $sequence = $this->resolver->getSequence();
        $this->eventLoop->withSequence($sequence);
        $this->eventLoop->start();
    }

    /**
     * Boot class
     * @param string|int $pipelineIdentifier
     * @return void
     * @throws BindingResolutionException
     */
    protected function boot(string|int $pipelineIdentifier): void
    {
        $this->eventLoop = new EventLoop();
        $this->resolver = new Resolver($pipelineIdentifier);
    }

    /**
     * Truncate tables
     * @return void
     */
    public function truncate(): void
    {
        DB::table((new ActionExecution())->getTable())->truncate();
        DB::table((new TaskExecution())->getTable())->truncate();
        DB::table((new PipelineExecution())->getTable())->truncate();
    }
}
