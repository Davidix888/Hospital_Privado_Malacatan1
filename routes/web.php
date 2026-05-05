<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasswordController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::middleware(['auth.custom'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/password/force-change', [PasswordController::class, 'showForceChange'])->name('password.force');
    Route::post('/password/force-change', [PasswordController::class, 'update'])->name('password.update');

    Route::middleware(['password.fresh'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::view('/farmacia', 'modules.farmacia')->middleware('module.access:farmacia')->name('farmacia');
        Route::view('/laboratorio', 'modules.laboratorio')->middleware('module.access:laboratorio')->name('laboratorio');
        Route::view('/reportes', 'modules.reportes')->middleware('module.access:reportes')->name('reportes');
    });
});
