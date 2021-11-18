<?php

namespace ConsulConfigManager\Tasks\Factories;

use ConsulConfigManager\Tasks\Models\Pipeline;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class PipelineFactory
 * @package ConsulConfigManager\Tasks\Factories
 */
class PipelineFactory extends Factory
{
    /**
     * @inheritDoc
     */
    protected $model = Pipeline::class;

    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        return [
            'uuid'              =>  $this->faker->uuid(),
            'name'              =>  $this->faker->words(2),
            'description'       =>  $this->faker->realTextBetween(),
        ];
    }
}
