<?php namespace ConsulConfigManager\Tasks\Test;

use ReflectionMethod;
use ReflectionException;
use ConsulConfigManager\Tasks\TaskDomain;
use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use ConsulConfigManager\Tasks\Providers\TasksServiceProvider;

/**
 * Class TestCase
 * @package ConsulConfigManager\Tasks\Test
 */
abstract class TestCase extends Orchestra {
    use DatabaseMigrations;

    /**
     * @inheritDoc
     */
    public function setUp(): void {
        parent::setUp();
        TaskDomain::registerRoutes();
    }

    /**
     * Call protected/private method from class
     * @param string $className
     * @param string $methodName
     * @param array  $arguments
     *
     * @throws ReflectionException
     * @return mixed
     */
    public function callMethod(string $className, string $methodName, array $arguments = []): mixed {
        $mockedInstance = $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->getMock();

        $reflectedMethod = new ReflectionMethod(
            $className,
            $methodName
        );
        $reflectedMethod->setAccessible(true);

        return $reflectedMethod->invokeArgs(
            $mockedInstance,
            $arguments
        );
    }

    /**
     * @inheritDoc
     */
    protected function getPackageProviders($app): array {
        return [
            TasksServiceProvider::class,
        ];
    }

    /**
     * @inheritDoc
     */
    public function ignorePackageDiscoveriesFrom(): array {
        return [];
    }


    /**
     * @inheritDoc
     */
    protected function getEnvironmentSetUp($app): void {
        $app['config']->set('app.env', 'testing');
        $app['config']->set('app.debug', true);
        $app['config']->set('auth.default', 'api');
        $app['config']->set('cache.default', 'array');
        $app['config']->set('hashing.bcrypt.round', 4);
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'        =>  'sqlite',
            'database'      =>  ':memory:',
            'prefix'        =>  '',
        ]);
    }

}
