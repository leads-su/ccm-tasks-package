<?php

namespace ConsulConfigManager\Tasks\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

/**
 * Class AbstractRepository
 * @package ConsulConfigManager\Tasks\Repositories
 */
abstract class AbstractRepository
{
    /**
     * Class of the model we are working with
     * @var string
     */
    protected string $modelClass;

    /**
     * Get model instance
     * @return Model
     */
    protected function getModel(): Model
    {
        return new $this->modelClass();
    }

    /**
     * Get model query object instance
     * @return EloquentBuilder|QueryBuilder
     */
    protected function getModelQuery(): EloquentBuilder|QueryBuilder
    {
        return $this->getModel()->newModelQuery();
    }

    /**
     * Get model query object with trashed items
     * @param bool $withTrashed
     * @return EloquentBuilder|QueryBuilder
     */
    protected function getModelQueryWithTrashed(bool $withTrashed = false): EloquentBuilder|QueryBuilder
    {
        $model = $this->getModel();
        if (in_array(SoftDeletes::class, class_uses($model))) {
            /**
             * @var Model|SoftDeletes $model
             */
            return $model->withTrashed($withTrashed);
        }
        return $this->getModelQuery();
    }

    /**
     * Get "casts" parameters for the model
     * @return array
     */
    protected function getModelCasts(): array
    {
        return $this->getModel()->getCasts();
    }

    /**
     * Map model multiple query
     * @param array $fields
     * @param mixed $value
     * @param array $with
     * @param bool $withDeleted
     * @param Model|EloquentBuilder|QueryBuilder|null $query
     * @return EloquentBuilder|QueryBuilder
     */
    protected function mapModelMultipleQuery(array $fields, mixed $value, array $with = [], bool $withDeleted = false, Model|EloquentBuilder|QueryBuilder|null $query = null): EloquentBuilder|QueryBuilder
    {
        if (!$query) {
            $query = $this->getModelQueryWithTrashed($withDeleted);
        }
        if ($query instanceof Model) {
            $query = $query->newModelQuery();
            if ($withDeleted) {
                /**
                 * @var EloquentBuilder|QueryBuilder|SoftDeletes $query
                 */
                $query = $query->withTrashed($withDeleted);
            }
        }

        $query = $query->with($with);

        $casts = $this->getModelCasts();
        $whereCount = 0;

        foreach ($fields as $field) {
            if (isset($casts[$field]) && $this->isValidType($value, $casts[$field])) {
                if ($whereCount === 0) {
                    $query = $query->where($field, '=', $value);
                } else {
                    $query = $query->orWhere($field, '=', $value);
                }
                $whereCount++;
            }
        }
        return $query;
    }

    /**
     * Check if provided value is of valid type for the query
     * @param mixed $value
     * @param string $expected
     * @return bool
     */
    private function isValidType(mixed $value, string $expected): bool
    {
        $received = gettype($value);

        if ($received === $expected) {
            return true;
        }

        if (
            is_numeric($value) &&
            in_array($expected, ['integer', 'float', 'double'])
        ) {
            return true;
        }

        if (
            ($expected === 'bool' || $expected === 'boolean') &&
            ($value === 'true' || $value === 'false')
        ) {
            return true;
        }

        return false;
    }
}
