<?php

namespace ConsulConfigManager\Tasks\Test;

use Illuminate\Foundation\Application;
use ConsulConfigManager\Tasks\TaskDomain;
use ConsulConfigManager\Testing\Concerns;
use ConsulConfigManager\Users\Models\User;
use Spatie\EventSourcing\EventSourcingServiceProvider;
use ConsulConfigManager\Tasks\Providers\TasksServiceProvider;
use ConsulConfigManager\Users\Providers\UsersServiceProvider;
use ConsulConfigManager\Tasks\Test\Concerns\WithConsulServices;
use ConsulConfigManager\Users\Domain\ValueObjects\EmailValueObject;
use ConsulConfigManager\Users\Domain\ValueObjects\PasswordValueObject;
use ConsulConfigManager\Users\Domain\ValueObjects\UsernameValueObject;

/**
 * Class TestCase
 * @package ConsulConfigManager\Tasks\Test
 */
abstract class TestCase extends \ConsulConfigManager\Testing\TestCase
{
    use Concerns\WithQueueMigrations;
    use Concerns\WithEventSourcingMigrations;
    use WithConsulServices;

    /**
     * @inheritDoc
     */
    protected array $packageProviders = [
        EventSourcingServiceProvider::class,
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
        $this->createConsulServicesTables();
    }


    /**
     * @inheritDoc
     */
    public function runBeforeTearDown(): void
    {
        TaskDomain::ignoreRoutes();
        $this->dropConsulServicesTables();
    }

    /**
     * @inheritDoc
     */
    public function setUpEnvironment(Application $app): void
    {
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
