<?php

namespace ConsulConfigManager\Tasks\Test;

use Illuminate\Foundation\Application;
use ConsulConfigManager\Tasks\TaskDomain;
use ConsulConfigManager\Testing\Concerns;
use ConsulConfigManager\Tasks\Providers\TasksServiceProvider;

/**
 * Class TestCase
 * @package ConsulConfigManager\Tasks\Test
 */
abstract class TestCase extends \ConsulConfigManager\Testing\TestCase
{
    use Concerns\WithQueueMigrations;

    /**
     * @inheritDoc
     */
    protected array $packageProviders = [
        TasksServiceProvider::class,
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
    public function runBeforeTearDown(): void
    {
        TaskDomain::ignoreRoutes();
    }

    /**
     * @inheritDoc
     */
    public function setUpEnvironment(Application $app): void
    {
    }
}
