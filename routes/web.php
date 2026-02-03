<?php

use App\Http\Controllers\AdminTicketController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CommentController;

// Ruta raíz
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// Dashboard general (redirige según rol)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return auth()->user()->hasRole('admin')
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.dashboard');
    })->name('dashboard');

    Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])->name('comments.store');
});

// ====================
// ADMIN
// ====================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [AdminController::class, 'index'])
            ->name('dashboard');

        // Admin puede: listar, ver, crear y guardar tickets
        Route::resource('tickets', AdminTicketController::class)->only([
            'index',
            'show',
            'create',
            'store',
            'update',
            'destroy',
        ]);
    });

// ====================
// USER
// ====================
Route::middleware(['auth', 'role:user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('/', [UserController::class, 'index'])
            ->name('dashboard');

        // Usuario: listar, crear y guardar tickets
        Route::resource('tickets', TicketController::class)->only([
            'index',
            'create',
            'store',
        ]);
    });

// Auth (Breeze)
require __DIR__.'/auth.php';
