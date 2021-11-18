<?php

namespace ConsulConfigManager\Tasks\Projectors;

use ConsulConfigManager\Tasks\Events;
use ConsulConfigManager\Tasks\Models\Action;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

/**
 * Class ActionProjector
 * @package ConsulConfigManager\Tasks\Projectors
 */
class ActionProjector extends Projector
{
    /**
     * Handle `created` event
     * @param Events\Action\ActionCreated $event
     * @return void
     */
    public function onCreated(Events\Action\ActionCreated $event): void
    {
        $model = new Action();
        $model->setUuid($event->aggregateRootUuid());
        $model->setName($event->getName());
        $model->setDescription($event->getDescription());
        $model->setType($event->getType());
        $model->setCommand($event->getCommand());
        $model->setArguments($event->getArguments());
        $model->setWorkingDirectory($event->getWorkingDirectory());
        $model->setRunAs($event->getRunAs());
        $model->useSudo($event->isUsingSudo());
        $model->failOnError($event->isFailingOnError());
        $model->save();
    }

    /**
     * Handle `updated` event
     * @param Events\Action\ActionUpdated $event
     * @return void
     */
    public function onUpdated(Events\Action\ActionUpdated $event): void
    {
        $model = Action::uuid($event->aggregateRootUuid());
        $model->setName($event->getName());
        $model->setDescription($event->getDescription());
        $model->setType($event->getType());
        $model->setCommand($event->getCommand());
        $model->setArguments($event->getArguments());
        $model->setWorkingDirectory($event->getWorkingDirectory());
        $model->setRunAs($event->getRunAs());
        $model->useSudo($event->isUsingSudo());
        $model->failOnError($event->isFailingOnError());
        $model->save();
    }

    /**
     * Handle `deleted` event
     * @param Events\Action\ActionDeleted $event
     * @return void
     */
    public function onDeleted(Events\Action\ActionDeleted $event): void
    {
        Action::uuid($event->aggregateRootUuid())->delete();
    }
}
