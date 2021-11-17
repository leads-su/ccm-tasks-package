<?php

namespace ConsulConfigManager\Tasks\Factories;

use ConsulConfigManager\Tasks\Models\Action;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class ActionFactory
 * @package ConsulConfigManager\Tasks\Factories
 */
class ActionFactory extends Factory
{
    /**
     * @inheritDoc
     */
    protected $model = Action::class;

    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        return [

        ];
    }
}
