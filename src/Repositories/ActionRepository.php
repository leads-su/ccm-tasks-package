<?php

namespace ConsulConfigManager\Tasks\Repositories;

use Throwable;
use Illuminate\Support\Str;
use ConsulConfigManager\Tasks\Models\Action;
use Illuminate\Database\Eloquent\Collection;
use ConsulConfigManager\Tasks\Interfaces\ActionInterface;
use ConsulConfigManager\Tasks\AggregateRoots\ActionAggregateRoot;
use ConsulConfigManager\Tasks\Interfaces\ActionRepositoryInterface;

/**
 * Class ActionRepository
 * @package ConsulConfigManager\Tasks\Repositories
 */
class ActionRepository implements ActionRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function all(array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): Collection
    {
        return Action::withTrashed($withDeleted)->with($with)->get($columns)->each->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function find(int $id, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): ActionInterface|null
    {
        return $this->findBy(
            field: 'id',
            value: $id,
            columns: $columns,
            with: $with,
            append: $append,
            withDeleted: $withDeleted,
        );
    }

    /**
     * @inheritDoc
     */
    public function findOrFail(int $id, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): ActionInterface
    {
        return $this->findByOrFail(
            field: 'id',
            value: $id,
            columns: $columns,
            with: $with,
            append: $append,
            withDeleted: $withDeleted,
        );
    }

    /**
     * @inheritDoc
     */
    public function findBy(string $field, string $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): ActionInterface|null
    {
        return Action::withTrashed($withDeleted)->with($with)->where($field, '=', $value)->first($columns)?->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findByOrFail(string $field, string $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): ActionInterface
    {
        return Action::withTrashed($withDeleted)->with($with)->where($field, '=', $value)->firstOrFail($columns)->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findByMany(array $fields, string $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): ActionInterface|null
    {
        $query = Action::withTrashed($withDeleted)->with($with);
        foreach ($fields as $index => $field) {
            if ($index === 0) {
                $query = $query->where($field, '=', $value);
            } else {
                $query = $query->orWhere($field, '=', $value);
            }
        }
        $result = $query->first($columns);
        if (!$result) {
            return $result;
        }
        return $result->setAppends($append);
    }

    /**
     * @inheritDoc
     */
    public function findByManyOrFail(array $fields, string $value, array $columns = ['*'], array $with = [], array $append = [], bool $withDeleted = false): ActionInterface
    {
        $query = Action::withTrashed($withDeleted)->with($with);
        foreach ($fields as $index => $field) {
            if ($index === 0) {
                $query = $query->where($field, '=', $value);
            } else {
                $query = $query->orWhere($field, '=', $value);
            }
        }
        return $query->firstOrFail($columns)->setAppends($append);
    }


    /**
     * @inheritDoc
     */
    public function create(string $name, string $description, int $type, string $command, array $arguments, ?string $workingDirectory = null, ?string $runAs = null, bool $useSudo = false, bool $failOnError = true): ActionInterface
    {
        $uuid = Str::uuid()->toString();
        ActionAggregateRoot::retrieve($uuid)
            ->createEntity(
                $name,
                $description,
                $type,
                $command,
                $arguments,
                $workingDirectory,
                $runAs,
                $useSudo,
                $failOnError,
            )
            ->persist();
        return Action::uuid($uuid);
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, string $name, string $description, int $type, string $command, array $arguments, ?string $workingDirectory = null, ?string $runAs = null, bool $useSudo = false, bool $failOnError = true): ActionInterface
    {
        $model = $this->findOrFail(
            id: $id,
            columns: ['uuid'],
            withDeleted: true
        );
        ActionAggregateRoot::retrieve($model->getUuid())
            ->updateEntity(
                $name,
                $description,
                $type,
                $command,
                $arguments,
                $workingDirectory,
                $runAs,
                $useSudo,
                $failOnError,
            )
            ->persist();
        return Action::uuid($model->getUuid());
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, bool $forceDelete = false): bool
    {
        try {
            $model = $this->findOrFail(
                id: $id,
                columns: ['uuid']
            );
            ActionAggregateRoot::retrieve($model->getUuid())
                ->deleteEntity()
                ->persist();
            return true;
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function restore(int $id): bool
    {
        try {
            $model = $this->findOrFail(
                id: $id,
                columns: ['uuid'],
                withDeleted: true
            );
            ActionAggregateRoot::retrieve($model->getUuid())
                ->restoreEntity()
                ->persist();
            return true;
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function forceDelete(int $id): bool
    {
        return $this->delete($id, true);
    }
}
