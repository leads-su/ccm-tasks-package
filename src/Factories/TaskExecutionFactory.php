<?php

namespace ConsulConfigManager\Tasks\Factories;

use ConsulConfigManager\Tasks\Models\TaskExecution;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class TaskExecutionFactory
 * @package ConsulConfigManager\Tasks\Factories
 */
class TaskExecutionFactory extends Factory
{
    /**
     * @inheritDoc
     */
    protected $model = TaskExecution::class;

    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        return [
            'task_uuid'                 =>  $this->faker->uuid(),
            'pipeline_uuid'             =>  $this->faker->uuid(),
            'pipeline_execution_uuid'   =>  $this->faker->uuid(),
            'state'                     =>  $this->faker->numberBetween(1, 5),
        ];
    }
}
