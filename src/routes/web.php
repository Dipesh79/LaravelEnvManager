<?php

use Dipesh79\LaravelEnvManager\Controller\EnvManagerController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('/env', [EnvManagerController::class, 'index'])->name('laravel-env-manager');
    Route::post('/env/update', [EnvManagerController::class, 'update'])->name('laravel-env-manager.update');

    // Backup
    Route::post('/env/backup', [EnvManagerController::class, 'backup'])->name('laravel-env-manager.backup');

});
