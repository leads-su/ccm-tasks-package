<?php

namespace ConsulConfigManager\Tasks\Providers;

use Illuminate\Support\Facades\Route;
use ConsulConfigManager\Tasks\TaskDomain;
use ConsulConfigManager\Domain\DomainServiceProvider;

/**
 * Class TasksServiceProvider
 * @package ConsulConfigManager\Tasks\Providers
 */
class TasksServiceProvider extends DomainServiceProvider
{
    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        $this->registerRoutes();
        $this->offerPublishing();
        $this->registerMigrations();
        $this->registerCommands();
    }

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->registerConfiguration();
        parent::register();
    }

    /**
     * Register package routes
     * @return void
     */
    protected function registerRoutes(): void
    {
        if (TaskDomain::shouldRegisterRoutes()) {
            Route::group([
                'prefix'        =>  config('domain.tasks.prefix'),
                'middleware'    =>  config('domain.tasks.middleware'),
            ], function (): void {
                $this->loadRoutesFrom(__DIR__ . '/../../routes/routes.php');
            });
        }
    }

    /**
     * Register package configuration
     * @return void
     */
    protected function registerConfiguration(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/tasks.php', 'domain.tasks');
    }

    /**
     * Register package migrations
     * @return void
     */
    protected function registerMigrations(): void
    {
    }

    /**
     * Register package commands
     * @return void
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
        }
    }

    /**
     * Offer resources for publishing
     * @return void
     */
    protected function offerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/tasks.php'         =>  config_path('domain/tasks.php'),
            ], 'ccm-tasks-config');
        }
    }

    /**
     * @inheritDoc
     */
    protected function registerFactories(): void
    {
    }

    /**
     * @inheritDoc
     */
    protected function registerRepositories(): void
    {
    }

    /**
     * @inheritDoc
     */
    protected function registerInterceptors(): void
    {
    }
}
