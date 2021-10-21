<?php namespace ConsulConfigManager\Tasks;

/**
 * Class TaskDomain
 * @package ConsulConfigManager\Tasks
 */
class TaskDomain {

    /**
     * Indicates if package will run its migrations
     * @var bool
     */
    public static bool $runsMigrations = true;

    /**
     * Indicates if package will register its routes
     * @var bool
     */
    public static bool $registersRoutes = false;

    /**
     * Determine if package should run its migrations
     * @return bool
     */
    public static function shouldRunMigrations(): bool {
        return static::$runsMigrations;
    }

    /**
     * Determine if package should register its routes
     * @return bool
     */
    public static function shouldRegisterRoutes(): bool {
        return static::$registersRoutes;
    }

    /**
     * Configure package to not register its migrations
     * @return static
     */
    public static function ignoreMigrations(): static {
        static::$runsMigrations = false;
        return new static;
    }

    /**
     * Configure package to register its migrations
     * @return static
     */
    public static function registerMigrations(): static {
        static::$runsMigrations = true;
        return new static;
    }

    /**
     * Configure package to not register its routes
     * @return static
     */
    public static function ignoreRoutes(): static {
        static::$registersRoutes = false;
        return new static;
    }

    /**
     * Configure package to register its routes
     * @return static
     */
    public static function registerRoutes(): static {
        static::$registersRoutes = true;
        return new static;
    }

}
