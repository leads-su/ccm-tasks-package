<?php

namespace ConsulConfigManager\Tasks\Factories;

use ConsulConfigManager\Tasks\Models\ActionHost;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class ActionHostFactory
 * @package ConsulConfigManager\Tasks\Factories
 */
class ActionHostFactory extends Factory
{
    /**
     * @inheritDoc
     */
    protected $model = ActionHost::class;

    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        return [
            'action_uuid'   =>  $this->faker->uuid(),
            'service_uuid'  =>  $this->faker->uuid(),
        ];
    }
}
