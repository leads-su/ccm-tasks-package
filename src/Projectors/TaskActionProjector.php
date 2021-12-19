<?php

namespace ConsulConfigManager\Tasks\Projectors;

use ConsulConfigManager\Tasks\Events;
use ConsulConfigManager\Tasks\Models\TaskAction;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

/**
 * Class TaskTaskActionProjector
 * @package ConsulConfigManager\Tasks\Projectors
 */
class TaskActionProjector extends Projector
{
    /**
     * Handle `created` event
     * @param Events\TaskAction\TaskActionCreated $event
     * @return void
     */
    public function onCreated(Events\TaskAction\TaskActionCreated $event): void
    {
        $model = new TaskAction();
        $model->setUuid($event->aggregateRootUuid());
        $model->setTaskUuid($event->getTask());
        $model->setActionUuid($event->getAction());
        $model->setOrder($event->getOrder());
        $model->save();
    }

    /**
     * Handle `updated` event
     * @param Events\TaskAction\TaskActionUpdated $event
     * @return void
     */
    public function onUpdated(Events\TaskAction\TaskActionUpdated $event): void
    {
        $model = TaskAction::uuid($event->aggregateRootUuid());
        $model->setOrder($event->getOrder());
        $model->save();
    }

    /**
     * Handle `deleted` event
     * @param Events\TaskAction\TaskActionDeleted $event
     * @return void
     */
    public function onDeleted(Events\TaskAction\TaskActionDeleted $event): void
    {
        if ($event->isForced()) {
            TaskAction::uuid($event->aggregateRootUuid())->forceDelete();
        } else {
            TaskAction::uuid($event->aggregateRootUuid())->delete();
        }
    }

    /**
     * Handle `restored` event
     * @param Events\TaskAction\TaskActionRestored $event
     * @return void
     */
    public function onRestore(Events\TaskAction\TaskActionRestored $event): void
    {
        $model = TaskAction::uuid($event->aggregateRootUuid(), true);
        $model->deleted_at = null;
        $model->save();
    }
}
