<?php

namespace ConsulConfigManager\Tasks\Test\Unit;

use ConsulConfigManager\Tasks\TaskDomain;
use ConsulConfigManager\Tasks\Test\TestCase;

/**
 * Class TaskDomainTest
 * @package ConsulConfigManager\Tasks\Test\Unit
 */
class TaskDomainTest extends TestCase
{
    /**
     * @return void
     */
    public function testMigrationsShouldRunByDefault(): void
    {
        $this->assertTrue(TaskDomain::shouldRunMigrations());
    }

    /**
     * @return void
     */
    public function testMigrationsPublishingCanBeDisabled(): void
    {
        TaskDomain::ignoreMigrations();
        $this->assertFalse(TaskDomain::shouldRunMigrations());
        TaskDomain::registerMigrations();
    }

    /**
     * @return void
     */
    public function testRoutesShouldNotBeRegisteredByDefault(): void
    {
        TaskDomain::ignoreRoutes();
        $this->assertFalse(TaskDomain::shouldRegisterRoutes());
        TaskDomain::registerRoutes();
    }

    /**
     * @return void
     */
    public function testRoutesRegistrationCanBeEnabled(): void
    {
        TaskDomain::registerRoutes();
        $this->assertTrue(TaskDomain::shouldRegisterRoutes());
        TaskDomain::ignoreRoutes();
    }
}
