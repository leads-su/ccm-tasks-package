<?php

namespace ConsulConfigManager\Tasks\Providers;

use ConsulConfigManager\Tasks\Http;
use Illuminate\Support\Facades\Route;
use ConsulConfigManager\Tasks\UseCases;
use ConsulConfigManager\Tasks\Interfaces;
use ConsulConfigManager\Tasks\Presenters;
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
        Interfaces\ActionRepositoryInterface::class             =>  Repositories\ActionRepository::class,
        Interfaces\TaskRepositoryInterface::class               =>  Repositories\TaskRepository::class,
        Interfaces\PipelineRepositoryInterface::class           =>  Repositories\PipelineRepository::class,
        Interfaces\PipelineExecutionRepositoryInterface::class  =>  Repositories\PipelineExecutionRepository::class,
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
        $this->registerActionInterceptors();
        $this->registerPipelineInterceptors();
        $this->registerTaskInterceptors();
    }

    /**
     * Register action specific interceptors
     * @return void
     */
    private function registerActionInterceptors(): void
    {
        $this->registerInterceptorFromParameters(
            UseCases\Action\List\ActionListInputPort::class,
            UseCases\Action\List\ActionListInteractor::class,
            Http\Controllers\Action\ActionListController::class,
            Presenters\Action\ActionListHttpPresenter::class,
        );

        $this->registerInterceptorFromParameters(
            UseCases\Action\Get\ActionGetInputPort::class,
            UseCases\Action\Get\ActionGetInteractor::class,
            Http\Controllers\Action\ActionGetController::class,
            Presenters\Action\ActionGetHttpPresenter::class,
        );

        $this->registerInterceptorFromParameters(
            UseCases\Action\Create\ActionCreateInputPort::class,
            UseCases\Action\Create\ActionCreateInteractor::class,
            Http\Controllers\Action\ActionCreateController::class,
            Presenters\Action\ActionCreateHttpPresenter::class,
        );

        $this->registerInterceptorFromParameters(
            UseCases\Action\Update\ActionUpdateInputPort::class,
            UseCases\Action\Update\ActionUpdateInteractor::class,
            Http\Controllers\Action\ActionUpdateController::class,
            Presenters\Action\ActionUpdateHttpPresenter::class,
        );

        $this->registerInterceptorFromParameters(
            UseCases\Action\Delete\ActionDeleteInputPort::class,
            UseCases\Action\Delete\ActionDeleteInteractor::class,
            Http\Controllers\Action\ActionDeleteController::class,
            Presenters\Action\ActionDeleteHttpPresenter::class,
        );

        $this->registerInterceptorFromParameters(
            UseCases\Action\Restore\ActionRestoreInputPort::class,
            UseCases\Action\Restore\ActionRestoreInteractor::class,
            Http\Controllers\Action\ActionRestoreController::class,
            Presenters\Action\ActionRestoreHttpPresenter::class,
        );
    }

    /**
     * Register pipeline specific interceptors
     * @return void
     */
    private function registerPipelineInterceptors(): void
    {
        $this->registerInterceptorFromParameters(
            UseCases\Pipeline\List\PipelineListInputPort::class,
            UseCases\Pipeline\List\PipelineListInteractor::class,
            Http\Controllers\Pipeline\PipelineListController::class,
            Presenters\Pipeline\PipelineListHttpPresenter::class,
        );

        $this->registerInterceptorFromParameters(
            UseCases\Pipeline\Get\PipelineGetInputPort::class,
            UseCases\Pipeline\Get\PipelineGetInteractor::class,
            Http\Controllers\Pipeline\PipelineGetController::class,
            Presenters\Pipeline\PipelineGetHttpPresenter::class,
        );

        $this->registerInterceptorFromParameters(
            UseCases\Pipeline\Create\PipelineCreateInputPort::class,
            UseCases\Pipeline\Create\PipelineCreateInteractor::class,
            Http\Controllers\Pipeline\PipelineCreateController::class,
            Presenters\Pipeline\PipelineCreateHttpPresenter::class,
        );

        $this->registerInterceptorFromParameters(
            UseCases\Pipeline\Update\PipelineUpdateInputPort::class,
            UseCases\Pipeline\Update\PipelineUpdateInteractor::class,
            Http\Controllers\Pipeline\PipelineUpdateController::class,
            Presenters\Pipeline\PipelineUpdateHttpPresenter::class,
        );

        $this->registerInterceptorFromParameters(
            UseCases\Pipeline\Delete\PipelineDeleteInputPort::class,
            UseCases\Pipeline\Delete\PipelineDeleteInteractor::class,
            Http\Controllers\Pipeline\PipelineDeleteController::class,
            Presenters\Pipeline\PipelineDeleteHttpPresenter::class,
        );

        $this->registerInterceptorFromParameters(
            UseCases\Pipeline\Restore\PipelineRestoreInputPort::class,
            UseCases\Pipeline\Restore\PipelineRestoreInteractor::class,
            Http\Controllers\Pipeline\PipelineRestoreController::class,
            Presenters\Pipeline\PipelineRestoreHttpPresenter::class,
        );
    }

    /**
     * Register task specific interceptors
     * @return void
     */
    private function registerTaskInterceptors(): void
    {
        $this->registerInterceptorFromParameters(
            UseCases\Task\List\TaskListInputPort::class,
            UseCases\Task\List\TaskListInteractor::class,
            Http\Controllers\Task\TaskListController::class,
            Presenters\Task\TaskListHttpPresenter::class,
        );

        $this->registerInterceptorFromParameters(
            UseCases\Task\Get\TaskGetInputPort::class,
            UseCases\Task\Get\TaskGetInteractor::class,
            Http\Controllers\Task\TaskGetController::class,
            Presenters\Task\TaskGetHttpPresenter::class,
        );

        $this->registerInterceptorFromParameters(
            UseCases\Task\Create\TaskCreateInputPort::class,
            UseCases\Task\Create\TaskCreateInteractor::class,
            Http\Controllers\Task\TaskCreateController::class,
            Presenters\Task\TaskCreateHttpPresenter::class,
        );

        $this->registerInterceptorFromParameters(
            UseCases\Task\Update\TaskUpdateInputPort::class,
            UseCases\Task\Update\TaskUpdateInteractor::class,
            Http\Controllers\Task\TaskUpdateController::class,
            Presenters\Task\TaskUpdateHttpPresenter::class,
        );

        $this->registerInterceptorFromParameters(
            UseCases\Task\Delete\TaskDeleteInputPort::class,
            UseCases\Task\Delete\TaskDeleteInteractor::class,
            Http\Controllers\Task\TaskDeleteController::class,
            Presenters\Task\TaskDeleteHttpPresenter::class,
        );

        $this->registerInterceptorFromParameters(
            UseCases\Task\Restore\TaskRestoreInputPort::class,
            UseCases\Task\Restore\TaskRestoreInteractor::class,
            Http\Controllers\Task\TaskRestoreController::class,
            Presenters\Task\TaskRestoreHttpPresenter::class,
        );
    }

    /**
     * @inheritDoc
     */
    protected function registerProjectors(): void
    {
        Projectionist::addProjectors([
            Projectors\ActionProjector::class,
            Projectors\TaskProjector::class,
            Projectors\PipelineProjector::class,
            Projectors\PipelineExecutionProjector::class,
        ]);
    }
}
