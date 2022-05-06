<?php

namespace ConsulConfigManager\Tasks\Factories;

use Illuminate\Support\Arr;
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
        $users = [
            'app',
            'cabinet',
            'tracker',
            null,
        ];
        $randomUser = Arr::random($users);

        return [
            'uuid'              =>  $this->faker->uuid(),
            'name'              =>  $this->faker->words(2),
            'description'       =>  $this->faker->realTextBetween(),
            'type'              =>  $this->faker->numberBetween(0, 1),
            'command'           =>  'php',
            'arguments'         =>  ['test.php'],
            'working_dir'       =>  $randomUser !== null ? '/home/' . $randomUser : null,
            'run_as'            =>  $randomUser,
            'use_sudo'          =>  $this->faker->boolean(),
            'fail_on_error'     =>  $this->faker->boolean(),
        ];
    }
}
