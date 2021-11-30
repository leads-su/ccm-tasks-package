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

//        Route::patch('', '')
//            ->name('domain.tasks.actions.update');
    });
});
