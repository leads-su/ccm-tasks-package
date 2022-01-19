<?php

namespace ConsulConfigManager\Tasks\Test;

use Illuminate\Foundation\Application;
use ConsulConfigManager\Tasks\TaskDomain;
use ConsulConfigManager\Testing\Concerns;
use ConsulConfigManager\Users\Models\User;
use Spatie\EventSourcing\EventSourcingServiceProvider;
use ConsulConfigManager\Tasks\Providers\TasksServiceProvider;
use ConsulConfigManager\Users\Providers\UsersServiceProvider;
use ConsulConfigManager\Users\Domain\ValueObjects\EmailValueObject;
use ConsulConfigManager\Users\Domain\ValueObjects\PasswordValueObject;
use ConsulConfigManager\Users\Domain\ValueObjects\UsernameValueObject;
use ConsulConfigManager\Consul\Agent\Providers\ConsulAgentServiceProvider;

/**
 * Class TestCase
 * @package ConsulConfigManager\Tasks\Test
 */
abstract class TestCase extends \ConsulConfigManager\Testing\TestCase
{
    use Concerns\WithQueueMigrations;
    use Concerns\WithEventSourcingMigrations;

    /**
     * @inheritDoc
     */
    protected array $packageProviders = [
        EventSourcingServiceProvider::class,
        ConsulAgentServiceProvider::class,
        TasksServiceProvider::class,
        UsersServiceProvider::class,
    ];

    /**
     * @inheritDoc
     */
    protected bool $configurationFromEnvironment = true;

    /**
     * @inheritDoc
     */
    protected string $configurationFromFile = __DIR__ . '/..';

    /**
     * @inheritDoc
     */
    public function runBeforeSetUp(): void
    {
        TaskDomain::registerRoutes();
    }

    /**
     * @inheritDoc
     */
    public function runAfterSetUp(): void
    {
        User::create([
            'first_name'    =>  'System',
            'last_name'     =>  'User',
            'username'      =>  new UsernameValueObject('system'),
            'email'         =>  new EmailValueObject('admin@leads.su'),
            'password'      =>  new PasswordValueObject('1234567890'),
        ]);
    }


    /**
     * @inheritDoc
     */
    public function runBeforeTearDown(): void
    {
        TaskDomain::ignoreRoutes();
    }

    /**
     * @inheritDoc
     */
    public function setUpEnvironment(Application $app): void
    {
        $this->setConfigurationValue('permission', [
            'models' => [
                'permission' => \Spatie\Permission\Models\Permission::class,
                'role' => \Spatie\Permission\Models\Role::class,
            ],
            'table_names' => [
                'roles' => 'roles',
                'permissions' => 'permissions',
                'model_has_permissions' => 'model_has_permissions',
                'model_has_roles' => 'model_has_roles',
                'role_has_permissions' => 'role_has_permissions',
            ],
            'column_names' => [
                'role_pivot_key' => 'role_id',
                'permission_pivot_key' => 'permission_id',
                'model_morph_key' => 'model_id',
                'team_foreign_key' => 'team_id',
            ],
            'teams' => false,
            'display_permission_in_exception' => false,
            'display_role_in_exception' => false,
            'enable_wildcard_permission' => false,
            'cache' => [
                'expiration_time' => \DateInterval::createFromDateString('24 hours'),
                'key' => 'spatie.permission.cache',
                'store' => 'default',
            ],
        ], $app);
        $this->setConfigurationValue(
            'event-sourcing.snapshot_repository',
            \Spatie\EventSourcing\Snapshots\EloquentSnapshotRepository::class,
            $app
        );

        $this->setConfigurationValue(
            'event-sourcing.stored_event_repository',
            \Spatie\EventSourcing\StoredEvents\Repositories\EloquentStoredEventRepository::class,
            $app
        );
    }
}
