<?php

namespace ConsulConfigManager\Tasks\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use ConsulConfigManager\Tasks\Models\PipelineExecution;

/**
 * Class PipelineExecutionFactory
 * @package ConsulConfigManager\Tasks\Factories
 */
class PipelineExecutionFactory extends Factory
{
    /**
     * @inheritDoc
     */
    protected $model = PipelineExecution::class;

    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        return [
            'uuid'              =>  $this->faker->uuid(),
            'pipeline_uuid'     =>  $this->faker->uuid(),
            'state'             =>  $this->faker->numberBetween(1, 5),
        ];
    }
}
