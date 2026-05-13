<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FarmaciaController;
use App\Http\Controllers\LaboratorioController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::get('/forgot-password', [PasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/reset-password', [PasswordController::class, 'resetWithToken'])->name('password.reset.update');
});

Route::middleware(['auth.custom'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/password/force-change', [PasswordController::class, 'showForceChange'])->name('password.force');
    Route::post('/password/force-change', [PasswordController::class, 'update'])->name('password.update');

    Route::middleware(['password.fresh'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/farmacia', [FarmaciaController::class, 'index'])
            ->middleware('module.access:farmacia')
            ->name('farmacia');
        Route::post('/farmacia/compras', [FarmaciaController::class, 'storeCompra'])
            ->middleware('module.access:farmacia')
            ->name('farmacia.compras.store');
        Route::post('/farmacia/ventas', [FarmaciaController::class, 'storeVenta'])
            ->middleware('module.access:farmacia')
            ->name('farmacia.ventas.store');
        Route::post('/farmacia/devoluciones', [FarmaciaController::class, 'storeDevolucion'])
            ->middleware('module.access:farmacia')
            ->name('farmacia.devoluciones.store');
        Route::post('/farmacia/medicamentos', [FarmaciaController::class, 'storeMedicamento'])
            ->middleware('module.access:farmacia')
            ->name('farmacia.medicamentos.store');
        Route::put('/farmacia/medicamentos/{id}', [FarmaciaController::class, 'updateMedicamento'])
            ->middleware('module.access:farmacia')
            ->name('farmacia.medicamentos.update');
        Route::post('/farmacia/medicamentos/{id}/toggle', [FarmaciaController::class, 'toggleMedicamento'])
            ->middleware('module.access:farmacia')
            ->name('farmacia.medicamentos.toggle');
        Route::delete('/farmacia/medicamentos/{id}', [FarmaciaController::class, 'destroyMedicamento'])
            ->middleware('module.access:farmacia')
            ->name('farmacia.medicamentos.destroy');

        Route::get('/laboratorio', [LaboratorioController::class, 'index'])->middleware('module.access:laboratorio')->name('laboratorio');
        Route::post('/laboratorio/examenes', [LaboratorioController::class, 'storeExamen'])->middleware('module.access:laboratorio')->name('laboratorio.examenes.store');
        Route::put('/laboratorio/examenes/{id}', [LaboratorioController::class, 'updateExamen'])->middleware('module.access:laboratorio')->name('laboratorio.examenes.update');
        Route::post('/laboratorio/examenes/{id}/toggle', [LaboratorioController::class, 'toggleExamen'])->middleware('module.access:laboratorio')->name('laboratorio.examenes.toggle');
        Route::delete('/laboratorio/examenes/{id}', [LaboratorioController::class, 'destroyExamen'])->middleware('module.access:laboratorio')->name('laboratorio.examenes.destroy');
        Route::post('/laboratorio/pacientes', [LaboratorioController::class, 'storePaciente'])->middleware('module.access:laboratorio')->name('laboratorio.pacientes.store');
        Route::post('/laboratorio/solicitudes/{id}/estado', [LaboratorioController::class, 'updateSolicitudEstado'])->middleware('module.access:laboratorio')->name('laboratorio.solicitudes.estado');
        Route::post('/laboratorio/solicitudes/estado-general', [LaboratorioController::class, 'updateSolicitudEstadoGeneral'])->middleware('module.access:laboratorio')->name('laboratorio.solicitudes.estado.general');
        Route::view('/reportes', 'modules.reportes')->middleware('module.access:reportes')->name('reportes');

        Route::middleware('admin.only')->group(function () {
            Route::get('/usuarios', [UserManagementController::class, 'index'])->name('users.index');
            Route::get('/usuarios/crear', [UserManagementController::class, 'create'])->name('users.create');
            Route::post('/usuarios', [UserManagementController::class, 'store'])->name('users.store');
            Route::get('/usuarios/{usuario}/editar', [UserManagementController::class, 'edit'])->name('users.edit');
            Route::put('/usuarios/{usuario}', [UserManagementController::class, 'update'])->name('users.update');
            Route::post('/usuarios/{usuario}/toggle', [UserManagementController::class, 'toggle'])->name('users.toggle');
            Route::delete('/usuarios/{usuario}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        });
    });
});

