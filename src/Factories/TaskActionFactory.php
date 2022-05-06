<?php

namespace ConsulConfigManager\Tasks\Factories;

use ConsulConfigManager\Tasks\Models\TaskAction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class TaskActionFactory
 * @package ConsulConfigManager\Tasks\Factories
 */
class TaskActionFactory extends Factory
{
    /**
     * @inheritDoc
     */
    protected $model = TaskAction::class;

    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        return [
            'uuid'          =>  $this->faker->uuid(),
            'action_uuid'   =>  $this->faker->uuid(),
            'task_uuid'     =>  $this->faker->uuid(),
            'order'         =>  $this->faker->numberBetween(1, 10),
        ];
    }
}
