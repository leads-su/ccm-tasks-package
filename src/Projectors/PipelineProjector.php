<?php

namespace ConsulConfigManager\Tasks\Projectors;

use ConsulConfigManager\Tasks\Events;
use ConsulConfigManager\Tasks\Models\Pipeline;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

/**
 * Class PipelineProjector
 * @package ConsulConfigManager\Tasks\Projectors
 */
class PipelineProjector extends Projector
{
    /**
     * Handle `created` event
     * @param Events\Pipeline\PipelineCreated $event
     * @return void
     */
    public function onCreated(Events\Pipeline\PipelineCreated $event): void
    {
        $model = new Pipeline();
        $model->setUuid($event->aggregateRootUuid());
        $model->setName($event->getName());
        $model->setDescription($event->getDescription());
        $model->save();
    }

    /**
     * Handle `updated` event
     * @param Events\Pipeline\PipelineUpdated $event
     * @return void
     */
    public function onUpdated(Events\Pipeline\PipelineUpdated $event): void
    {
        $model = Pipeline::uuid($event->aggregateRootUuid());
        $model->setName($event->getName());
        $model->setDescription($event->getDescription());
        $model->save();
    }

    /**
     * Handle `deleted` event
     * @param Events\Pipeline\PipelineDeleted $event
     * @return void
     */
    public function onDeleted(Events\Pipeline\PipelineDeleted $event): void
    {
        Pipeline::uuid($event->aggregateRootUuid())->delete();
    }

    /**
     * Handle `restored` event
     * @param Events\Pipeline\PipelineRestored $event
     * @return void
     */
    public function onRestored(Events\Pipeline\PipelineRestored $event): void {
        $model = Pipeline::uuid($event->aggregateRootUuid(), true);
        $model->deleted_at = null;
        $model->save();
    }

}
