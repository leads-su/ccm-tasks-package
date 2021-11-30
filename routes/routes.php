<?php

use Illuminate\Support\Facades\Route;

Route::prefix('task-manager')->group(static function (): void {
    Route::prefix('actions')->group(static function (): void {
        Route::get('', \ConsulConfigManager\Tasks\Http\Controllers\Action\ActionListController::class)
            ->name('domain.tasks.actions.list');

        Route::get('{identifier}', \ConsulConfigManager\Tasks\Http\Controllers\Action\ActionGetController::class)
            ->name('domain.tasks.actions.information');

        Route::delete('{identifier}', \ConsulConfigManager\Tasks\Http\Controllers\Action\ActionDeleteController::class)
            ->name('domain.tasks.actions.delete');

        Route::post('', \ConsulConfigManager\Tasks\Http\Controllers\Action\ActionCreateController::class)
            ->name('domain.tasks.actions.create');

        Route::patch('{identifier}', \ConsulConfigManager\Tasks\Http\Controllers\Action\ActionUpdateController::class)
            ->name('domain.tasks.actions.update');
    });

    Route::prefix('pipelines')->group(static function (): void {
        Route::get('', \ConsulConfigManager\Tasks\Http\Controllers\Pipeline\PipelineListController::class)
            ->name('domain.tasks.pipelines.list');

        Route::get('{identifier}', \ConsulConfigManager\Tasks\Http\Controllers\Pipeline\PipelineGetController::class)
            ->name('domain.tasks.pipelines.information');

        Route::delete('{identifier}', \ConsulConfigManager\Tasks\Http\Controllers\Pipeline\PipelineDeleteController::class)
            ->name('domain.tasks.pipelines.delete');

        Route::post('', \ConsulConfigManager\Tasks\Http\Controllers\Pipeline\PipelineCreateController::class)
            ->name('domain.tasks.pipelines.create');

        Route::patch('{identifier}', \ConsulConfigManager\Tasks\Http\Controllers\Pipeline\PipelineUpdateController::class)
            ->name('domain.tasks.pipelines.update');
    });

    Route::prefix('tasks')->group(static function (): void {
        Route::get('', \ConsulConfigManager\Tasks\Http\Controllers\Task\TaskListController::class)
            ->name('domain.tasks.tasks.list');

        Route::get('{identifier}', \ConsulConfigManager\Tasks\Http\Controllers\Task\TaskGetController::class)
            ->name('domain.tasks.tasks.information');

        Route::delete('{identifier}', \ConsulConfigManager\Tasks\Http\Controllers\Task\TaskDeleteController::class)
            ->name('domain.tasks.tasks.delete');

        Route::post('', \ConsulConfigManager\Tasks\Http\Controllers\Task\TaskCreateController::class)
            ->name('domain.tasks.tasks.create');

        Route::patch('{identifier}', \ConsulConfigManager\Tasks\Http\Controllers\Task\TaskUpdateController::class)
            ->name('domain.tasks.tasks.update');
    });
});
