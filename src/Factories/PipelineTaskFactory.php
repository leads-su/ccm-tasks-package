<?php

namespace ConsulConfigManager\Tasks\Factories;

use ConsulConfigManager\Tasks\Models\PipelineTask;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class PipelineTaskFactory
 * @package ConsulConfigManager\Tasks\Factories
 */
class PipelineTaskFactory extends Factory
{
    /**
     * @inheritDoc
     */
    protected $model = PipelineTask::class;

    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        return [
            'uuid'          =>  $this->faker->uuid(),
            'pipeline_uuid' =>  $this->faker->uuid(),
            'task_uuid'     =>  $this->faker->uuid(),
            'order'         =>  $this->faker->numberBetween(1, 10),
        ];
    }
}
