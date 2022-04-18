<?php

namespace ConsulConfigManager\Tasks\Repositories;

use Throwable;
use Illuminate\Database\Eloquent\Collection;
use ConsulConfigManager\Users\Interfaces\UserInterface;
use ConsulConfigManager\Tasks\Models\PipelinePermission;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ConsulConfigManager\Tasks\Exceptions\ModelAlreadyExistsException;
use ConsulConfigManager\Tasks\Interfaces\PipelinePermissionInterface;
use ConsulConfigManager\Tasks\Interfaces\PipelinePermissionRepositoryInterface;

/**
 * Class PipelinePermissionRepository
 * @package ConsulConfigManager\Tasks\Repositories
 */
class PipelinePermissionRepository extends AbstractRepository implements PipelinePermissionRepositoryInterface
{
    /**
     * @inheritDoc
     */
    protected string $modelClass = PipelinePermission::class;

    /**
     * @inheritDoc
     */
    public function all(array $columns = ['*'], array $with = [], array $append = []): Collection
    {
        return $this->getModelQuery()->with($with)->get($columns)->each->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function find(int $id, array $columns = ['*'], array $with = [], array $append = []): PipelinePermissionInterface|null
    {
        return $this->findBy(
            field: 'id',
            value: $id,
            columns: $columns,
            with: $with,
            append: $append,
        );
    }

    /**
     * @inheritDoc
     */
    public function findOrFail(int $id, array $columns = ['*'], array $with = [], array $append = []): PipelinePermissionInterface
    {
        return $this->findByOrFail(
            field: 'id',
            value: $id,
            columns: $columns,
            with: $with,
            append: $append,
        );
    }

    /**
     * @inheritDoc
     */
    public function findBy(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = []): PipelinePermissionInterface|null
    {
        return $this->getModelQuery()->with($with)->where($field, '=', $value)->first($columns)?->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findManyBy(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = []): Collection
    {
        return $this->getModelQuery()->with($with)->where($field, '=', $value)->get($columns)->each->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findByOrFail(string $field, mixed $value, array $columns = ['*'], array $with = [], array $append = []): PipelinePermissionInterface
    {
        return $this->getModelQuery()->with($with)->where($field, '=', $value)->firstOrFail($columns)->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function hasPermission(string $pipelineIdentifier, UserInterface|int|null $user = null): bool
    {
        if (!$user) {
            $authUser = request()->user();
            if (!$authUser) {
                return false;
            }
            $user = $authUser;
        }

        if ($user instanceof UserInterface) {
            $user = $user->getID();
        }

        return $this->permissionExists($user, $pipelineIdentifier);
    }

    /**
     * @inheritDoc
     */
    public function permissionExists(int $userIdentifier, string $pipelineIdentifier): bool
    {
        return $this->getModelQuery()
            ->where('user_id', '=', $userIdentifier)
            ->where('pipeline_uuid', '=', $pipelineIdentifier)
            ->exists();
    }

    /**
     * @inheritDoc
     */
    public function create(int $userIdentifier, string $pipelineIdentifier): PipelinePermissionInterface
    {
        if ($this->permissionExists($userIdentifier, $pipelineIdentifier)) {
            throw new ModelAlreadyExistsException();
        }
        return PipelinePermission::create([
            'user_id'       =>  $userIdentifier,
            'pipeline_uuid' =>  $pipelineIdentifier,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function delete(int $userIdentifier, string $pipelineIdentifier, bool $forceDelete = false): bool
    {
        if (!$this->permissionExists($userIdentifier, $pipelineIdentifier)) {
            throw new ModelNotFoundException();
        }
        try {
            $model = $this->getModelQuery()
                ->where('user_id', '=', $userIdentifier)
                ->where('pipeline_uuid', '=', $pipelineIdentifier)
                ->first();

            if ($forceDelete) {
                return $model->forceDelete();
            }
            return $model->delete();
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function forceDelete(int $userIdentifier, string $pipelineIdentifier): bool
    {
        return $this->delete($userIdentifier, $pipelineIdentifier, true);
    }
}
