<?php

namespace ConsulConfigManager\Tasks\Projectors;

use ConsulConfigManager\Tasks\Events;
use ConsulConfigManager\Tasks\Models\PipelineTask;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

/**
 * Class TaskPipelineTaskProjector
 * @package ConsulConfigManager\Tasks\Projectors
 */
class PipelineTaskProjector extends Projector
{
    /**
     * Handle `created` event
     * @param Events\PipelineTask\PipelineTaskCreated $event
     * @return void
     */
    public function onCreated(Events\PipelineTask\PipelineTaskCreated $event): void
    {
        $model = new PipelineTask();
        $model->setUuid($event->aggregateRootUuid());
        $model->setPipelineUuid($event->getPipeline());
        $model->setTaskUuid($event->getTask());
        $model->setOrder($event->getOrder());
        $model->save();
    }

    /**
     * Handle `updated` event
     * @param Events\PipelineTask\PipelineTaskUpdated $event
     * @return void
     */
    public function onUpdated(Events\PipelineTask\PipelineTaskUpdated $event): void
    {
        $model = PipelineTask::uuid($event->aggregateRootUuid());
        $model->setOrder($event->getOrder());
        $model->save();
    }

    /**
     * Handle `deleted` event
     * @param Events\PipelineTask\PipelineTaskDeleted $event
     * @return void
     */
    public function onDeleted(Events\PipelineTask\PipelineTaskDeleted $event): void
    {
        if ($event->isForced()) {
            PipelineTask::uuid($event->aggregateRootUuid())->forceDelete();
        } else {
            PipelineTask::uuid($event->aggregateRootUuid())->delete();
        }
    }

    /**
     * Handle `restored` event
     * @param Events\PipelineTask\PipelineTaskRestored $event
     * @return void
     */
    public function onRestore(Events\PipelineTask\PipelineTaskRestored $event): void
    {
        $model = PipelineTask::uuid($event->aggregateRootUuid(), true);
        $model->deleted_at = null;
        $model->save();
    }
}
