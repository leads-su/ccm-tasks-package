<?php

namespace ConsulConfigManager\Tasks\Projectors;

use ConsulConfigManager\Tasks\Events;
use ConsulConfigManager\Tasks\Models\Task;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

/**
 * Class TaskProjector
 * @package ConsulConfigManager\Tasks\Projectors
 */
class TaskProjector extends Projector
{
    /**
     * Handle `created` event
     * @param Events\Task\TaskCreated $event
     * @return void
     */
    public function onCreated(Events\Task\TaskCreated $event): void
    {
        $model = new Task();
        $model->setUuid($event->aggregateRootUuid());
        $model->setName($event->getName());
        $model->setDescription($event->getDescription());
        $model->failOnError($event->shouldFailOnError());
        $model->save();
    }

    /**
     * Handle `updated` event
     * @param Events\Task\TaskUpdated $event
     * @return void
     */
    public function onUpdated(Events\Task\TaskUpdated $event): void
    {
        $model = Task::uuid($event->aggregateRootUuid());
        $model->setName($event->getName());
        $model->setDescription($event->getDescription());
        $model->failOnError($event->shouldFailOnError());
        $model->save();
    }

    /**
     * Handle `deleted` event
     * @param Events\Task\TaskDeleted $event
     * @return void
     */
    public function onDeleted(Events\Task\TaskDeleted $event): void
    {
        Task::uuid($event->aggregateRootUuid())->delete();
    }

    /**
     * Handle `restored` event
     * @param Events\Task\TaskRestored $event
     * @return void
     */
    public function onRestored(Events\Task\TaskRestored $event): void
    {
        $model = Task::uuid($event->aggregateRootUuid(), true);
        $model->deleted_at = null;
        $model->save();
    }
}
