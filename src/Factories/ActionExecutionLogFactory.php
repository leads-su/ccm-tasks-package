<?php

namespace ConsulConfigManager\Tasks\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use ConsulConfigManager\Tasks\Models\ActionExecutionLog;

/**
 * Class ActionExecutionLogFactory
 * @package ConsulConfigManager\Tasks\Factories
 */
class ActionExecutionLogFactory extends Factory
{
    /**
     * @inheritDoc
     */
    protected $model = ActionExecutionLog::class;

    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        $exitCode = $this->faker->randomDigit();
        return [
            'action_execution_id'       =>  $this->faker->randomDigitNotZero(),
            'exit_code'                 =>  $exitCode,
            'output'                    =>  [
                [
                    'message'               =>  'Hello World',
                    'timestamp'             =>  1642592730,
                ],
                [
                    'message'               =>  'exit status ' . $exitCode,
                    'timestamp'             =>  1642592731,
                ],
            ],
        ];
    }
}
