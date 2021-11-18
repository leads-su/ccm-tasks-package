<?php

namespace ConsulConfigManager\Tasks\Providers;

use Illuminate\Support\Facades\Route;
use ConsulConfigManager\Tasks\Interfaces;
use ConsulConfigManager\Tasks\Projectors;
use ConsulConfigManager\Tasks\TaskDomain;
use ConsulConfigManager\Tasks\Repositories;
use Spatie\EventSourcing\Facades\Projectionist;
use ConsulConfigManager\Domain\DomainServiceProvider;

/**
 * Class TasksServiceProvider
 * @package ConsulConfigManager\Tasks\Providers
 */
class TasksServiceProvider extends DomainServiceProvider
{
    /**
     * List of commands provided by package
     * @var array
     */
    protected array $packageCommands = [];

    /**
     * List of repositories provided by package
     * @var array
     */
    protected array $packageRepositories = [
        Interfaces\ActionRepositoryInterface::class     =>  Repositories\ActionRepository::class,
        Interfaces\TaskRepositoryInterface::class       =>  Repositories\TaskRepository::class,
        Interfaces\PipelineRepositoryInterface::class   =>  Repositories\PipelineRepository::class,
    ];


    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        $this->registerRoutes();
        $this->offerPublishing();
        $this->registerMigrations();
        $this->registerCommands();
        parent::boot();
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
        if (TaskDomain::shouldRunMigrations()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        }
    }

    /**
     * Register package commands
     * @return void
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands($this->packageCommands);
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
        foreach ($this->packageRepositories as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }

    /**
     * @inheritDoc
     */
    protected function registerInterceptors(): void
    {
        Projectionist::addProjectors([
            Projectors\ActionProjector::class,
            Projectors\TaskProjector::class,
            Projectors\PipelineProjector::class,
        ]);
    }
}
