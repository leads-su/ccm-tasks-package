<?php

use Illuminate\Support\Facades\Route;

Route::prefix('task-manager')->group(static function (): void {
    Route::prefix('actions')->group(static function (): void {
        Route::get('', \ConsulConfigManager\Tasks\Http\Controllers\Action\ActionListController::class)
            ->name('domain.tasks.actions.list');

        Route::post('', \ConsulConfigManager\Tasks\Http\Controllers\Action\ActionCreateController::class)
            ->name('domain.tasks.actions.create');

        Route::prefix('{identifier}')->group(static function (): void {
            Route::get('', \ConsulConfigManager\Tasks\Http\Controllers\Action\ActionGetController::class)
                ->name('domain.tasks.actions.information');

            Route::delete('', \ConsulConfigManager\Tasks\Http\Controllers\Action\ActionDeleteController::class)
                ->name('domain.tasks.actions.delete');

            Route::patch('', \ConsulConfigManager\Tasks\Http\Controllers\Action\ActionUpdateController::class)
                ->name('domain.tasks.actions.update');

            Route::patch('restore', \ConsulConfigManager\Tasks\Http\Controllers\Action\ActionRestoreController::class)
                ->name('domain.tasks.actions.restore');

            Route::prefix('executions')->group(static function (): void {
                Route::get('', \ConsulConfigManager\Tasks\Http\Controllers\ActionExecution\ActionExecutionListController::class)
                    ->name('domain.task.actions.executions');

                Route::get('{execution}', \ConsulConfigManager\Tasks\Http\Controllers\ActionExecution\ActionExecutionGetController::class)
                    ->name('domain.task.actions.executions.information');
            });
        });
    });

    Route::prefix('pipelines')->group(static function (): void {
        Route::get('', \ConsulConfigManager\Tasks\Http\Controllers\Pipeline\PipelineListController::class)
            ->name('domain.tasks.pipelines.list');

        Route::post('', \ConsulConfigManager\Tasks\Http\Controllers\Pipeline\PipelineCreateController::class)
            ->name('domain.tasks.pipelines.create');

        Route::get('executions', \ConsulConfigManager\Tasks\Http\Controllers\PipelineExecution\PipelineExecutionListController::class)
            ->name('domain.tasks.pipelines.executions');

        Route::prefix('{identifier}')->group(static function (): void {
            Route::get('', \ConsulConfigManager\Tasks\Http\Controllers\Pipeline\PipelineGetController::class)
                ->name('domain.tasks.pipelines.information');

            Route::delete('', \ConsulConfigManager\Tasks\Http\Controllers\Pipeline\PipelineDeleteController::class)
                ->name('domain.tasks.pipelines.delete');

            Route::patch('', \ConsulConfigManager\Tasks\Http\Controllers\Pipeline\PipelineUpdateController::class)
                ->name('domain.tasks.pipelines.update');

            Route::patch('restore', \ConsulConfigManager\Tasks\Http\Controllers\Pipeline\PipelineRestoreController::class)
                ->name('domain.tasks.pipelines.restore');

            Route::get('execution', \ConsulConfigManager\Tasks\Http\Controllers\PipelineExecution\PipelineExecutionGetController::class)
                ->name('domain.tasks.pipelines.execution');

            Route::get('run', \ConsulConfigManager\Tasks\Http\Controllers\Pipeline\PipelineRunController::class)
                ->name('domain.tasks.pipelines.run');

            Route::prefix('tasks')->group(static function (): void {
                Route::get('', \ConsulConfigManager\Tasks\Http\Controllers\PipelineTask\PipelineTaskListController::class)
                    ->name('domain.tasks.pipeline.tasks.list');

                Route::post('', \ConsulConfigManager\Tasks\Http\Controllers\PipelineTask\PipelineTaskCreateController::class)
                    ->name('domain.tasks.pipeline.tasks.create');

                Route::prefix('{action_identifier}')->group(static function (): void {
                    Route::get('', \ConsulConfigManager\Tasks\Http\Controllers\PipelineTask\PipelineTaskGetController::class)
                        ->name('domain.tasks.pipeline.tasks.information');

                    Route::patch('', \ConsulConfigManager\Tasks\Http\Controllers\PipelineTask\PipelineTaskUpdateController::class)
                        ->name('domain.tasks.pipeline.tasks.update');

                    Route::delete('', \ConsulConfigManager\Tasks\Http\Controllers\PipelineTask\PipelineTaskDeleteController::class)
                        ->name('domain.tasks.pipeline.tasks.delete');

                    Route::patch('restore', \ConsulConfigManager\Tasks\Http\Controllers\PipelineTask\PipelineTaskRestoreController::class)
                        ->name('domain.tasks.pipeline.tasks.restore');
                });
            });
        });
    });

    Route::prefix('tasks')->group(static function (): void {
        Route::get('', \ConsulConfigManager\Tasks\Http\Controllers\Task\TaskListController::class)
            ->name('domain.tasks.tasks.list');

        Route::post('', \ConsulConfigManager\Tasks\Http\Controllers\Task\TaskCreateController::class)
            ->name('domain.tasks.tasks.create');

        Route::prefix('{identifier}')->group(static function (): void {
            Route::get('', \ConsulConfigManager\Tasks\Http\Controllers\Task\TaskGetController::class)
                ->name('domain.tasks.tasks.information');

            Route::delete('', \ConsulConfigManager\Tasks\Http\Controllers\Task\TaskDeleteController::class)
                ->name('domain.tasks.tasks.delete');

            Route::patch('', \ConsulConfigManager\Tasks\Http\Controllers\Task\TaskUpdateController::class)
                ->name('domain.tasks.tasks.update');

            Route::patch('restore', \ConsulConfigManager\Tasks\Http\Controllers\Task\TaskRestoreController::class)
                ->name('domain.tasks.tasks.restore');

            Route::prefix('actions')->group(static function (): void {
                Route::get('', \ConsulConfigManager\Tasks\Http\Controllers\TaskAction\TaskActionListController::class)
                    ->name('domain.tasks.task.actions.list');

                Route::post('', \ConsulConfigManager\Tasks\Http\Controllers\TaskAction\TaskActionCreateController::class)
                    ->name('domain.tasks.task.actions.create');

                Route::prefix('{action_identifier}')->group(static function (): void {
                    Route::get('', \ConsulConfigManager\Tasks\Http\Controllers\TaskAction\TaskActionGetController::class)
                        ->name('domain.tasks.task.actions.information');

                    Route::patch('', \ConsulConfigManager\Tasks\Http\Controllers\TaskAction\TaskActionUpdateController::class)
                        ->name('domain.tasks.task.actions.update');

                    Route::delete('', \ConsulConfigManager\Tasks\Http\Controllers\TaskAction\TaskActionDeleteController::class)
                        ->name('domain.tasks.task.actions.delete');

                    Route::patch('restore', \ConsulConfigManager\Tasks\Http\Controllers\TaskAction\TaskActionRestoreController::class)
                        ->name('domain.tasks.task.actions.restore');
                });
            });
        });
    });

    Route::prefix('services')->group(static function (): void {
        Route::get('', \ConsulConfigManager\Tasks\Http\Controllers\Service\ServiceListController::class)
            ->name('domain.tasks.servers.list');
    });
});
