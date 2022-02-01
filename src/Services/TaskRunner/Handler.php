<?php

namespace ConsulConfigManager\Tasks\Services\TaskRunner;

use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;
use ConsulConfigManager\Tasks\Services\TaskRunner\Entities\PipelineEntity;

/**
 * Class Handler
 * @package ConsulConfigManager\Tasks\Services\TaskRunner
 */
class Handler extends LoggableClass {

    /**
     * Pipeline entity instance
     * @var PipelineEntity
     */
    private PipelineEntity $pipelineEntity;

    /**
     * Event Loop instance
     * @var LoopInterface
     */
    private LoopInterface $loop;

    /**
     * Handler constructor.
     * @param PipelineEntity $pipelineEntity
     * @return void
     */
    public function __construct(PipelineEntity $pipelineEntity) {
        $this->pipelineEntity = $pipelineEntity;
    }

    /**
     * @inheritDoc
     */
    public function bootstrap(): void {
        $this->loop = Loop::get();
    }

    /**
     * Start handler
     * @return void
     */
    public function run(): void {
        $this->pipelineEntity->runHandler($this->loop);
        $this->loop->run();
    }

}
