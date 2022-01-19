<?php

namespace ConsulConfigManager\Tasks\Repositories;

use Illuminate\Database\Eloquent\Collection;
use ConsulConfigManager\Tasks\Models\ActionHost;
use ConsulConfigManager\Tasks\Interfaces\ActionHostInterface;
use ConsulConfigManager\Tasks\Interfaces\ActionHostRepositoryInterface;

/**
 * Class ActionHostRepository
 * @package ConsulConfigManager\Tasks\Repositories
 */
class ActionHostRepository implements ActionHostRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function all(): Collection
    {
        return ActionHost::all();
    }

    /**
     * @inheritDoc
     */
    public function findBy(string $field, string $value, array $with = []): Collection
    {
        return ActionHost::where($field, '=', $value)->with($with)->get();
    }

    /**
     * @inheritDoc
     */
    public function findByAction(string $identifier, array $with = []): Collection
    {
        return $this->findBy('action_uuid', $identifier, $with);
    }

    /**
     * @inheritDoc
     */
    public function findByServer(string $identifier, array $with = []): Collection
    {
        return $this->findBy('service_uuid', $identifier, $with);
    }

    /**
     * @inheritDoc
     */
    public function findExact(string $actionIdentifier, string $serverIdentifier): ActionHostInterface|null
    {
        return ActionHost::where('action_uuid', '=', $actionIdentifier)
            ->where('service_uuid', '=', $serverIdentifier)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function findExactOrFail(string $actionIdentifier, string $serverIdentifier): ActionHostInterface
    {
        return ActionHost::where('action_uuid', '=', $actionIdentifier)
            ->where('service_uuid', '=', $serverIdentifier)
            ->firstOrFail();
    }

    /**
     * @inheritDoc
     */
    public function create(string $actionIdentifier, string $serverIdentifier): ActionHostInterface
    {
        $model = new ActionHost();
        $model->setActionUuid($actionIdentifier);
        $model->setServiceUuid($serverIdentifier);
        $model->save();
        return $model;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $actionIdentifier, string $serverIdentifier): bool
    {
        return $this->findExactOrFail($actionIdentifier, $serverIdentifier)->delete();
    }
}
