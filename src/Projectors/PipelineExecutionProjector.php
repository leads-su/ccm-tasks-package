<?php

namespace ConsulConfigManager\Tasks\Projectors;

use ConsulConfigManager\Tasks\Events;
use ConsulConfigManager\Tasks\Models\PipelineExecution;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

/**
 * Class PipelineExecutionProjector
 * @package ConsulConfigManager\Tasks\Projectors
 */
class PipelineExecutionProjector extends Projector
{
    /**
     * Handle `created` event
     * @param Events\PipelineExecution\PipelineExecutionCreated $event
     * @return void
     */
    public function onCreated(Events\PipelineExecution\PipelineExecutionCreated $event): void
    {
        $model = new PipelineExecution();
        $model->setUuid($event->aggregateRootUuid());
        $model->setPipelineUuid($event->getPipelineUuid());
        $model->setState($event->getState());
        $model->save();
    }

    /**
     * Handle `updated` event
     * @param Events\PipelineExecution\PipelineExecutionUpdated $event
     * @return void
     */
    public function onUpdated(Events\PipelineExecution\PipelineExecutionUpdated $event): void
    {
        $model = PipelineExecution::uuid($event->aggregateRootUuid());
        $model->setPipelineUuid($event->getPipelineUuid());
        $model->setState($event->getState());
        $model->save();
    }

    /**
     * Handle `deleted` event
     * @param Events\PipelineExecution\PipelineExecutionDeleted $event
     * @return void
     */
    public function onDeleted(Events\PipelineExecution\PipelineExecutionDeleted $event): void
    {
        PipelineExecution::uuid($event->aggregateRootUuid())->delete();
    }

    /**
     * Handle `restored` event
     * @param Events\PipelineExecution\PipelineExecutionRestored $event
     * @return void
     */
    public function onRestored(Events\PipelineExecution\PipelineExecutionRestored $event): void
    {
        $model = PipelineExecution::uuid($event->aggregateRootUuid(), true);
        $model->deleted_at = null;
        $model->save();
    }
}
