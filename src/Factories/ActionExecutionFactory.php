<?php

namespace ConsulConfigManager\Tasks\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use ConsulConfigManager\Tasks\Models\ActionExecution;

/**
 * Class ActionExecutionFactory
 * @package ConsulConfigManager\Tasks\Factories
 */
class ActionExecutionFactory extends Factory
{
    /**
     * @inheritDoc
     */
    protected $model = ActionExecution::class;

    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        return [
            'action_uuid'       =>  $this->faker->uuid(),
            'task_uuid'         =>  $this->faker->uuid(),
            'pipeline_uuid'     =>  $this->faker->uuid(),
            'state'             =>  $this->faker->numberBetween(1, 5),
        ];
    }
}
