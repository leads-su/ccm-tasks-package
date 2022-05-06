<?php

namespace ConsulConfigManager\Tasks\Factories;

use ConsulConfigManager\Tasks\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class TaskFactory
 * @package ConsulConfigManager\Tasks\Factories
 */
class TaskFactory extends Factory
{
    /**
     * @inheritDoc
     */
    protected $model = Task::class;

    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        return [

        ];
    }
}
