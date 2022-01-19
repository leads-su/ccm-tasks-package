<?php

namespace ConsulConfigManager\Tasks\Test\backup\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use ConsulConfigManager\Tasks\Test\TestCase;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class AbstractRepositoryTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Repositories
 */
abstract class AbstractRepositoryTest extends TestCase
{
    /**
     * Create array without dates from entity
     *
     * @param array|Model|Collection $entity
     * @param bool                     $nested
     *
     * @return array
     */
    protected function exceptDates(array|Model|Collection $entity, bool $nested = false): array
    {
        if (!is_array($entity)) {
            $entity = $entity->toArray();
        }

        if ($nested) {
            $data = [];
            foreach ($entity as $index => $value) {
                $data[$index] = Arr::except($value, [
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]);
            }
            return $data;
        }

        return Arr::except($entity, [
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
    }
}
