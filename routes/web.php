<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

// Página principal: login
Route::get('/', function () {
    return view('auth.login');
});

// Rutas de autenticación y perfil (Breeze)
Route::middleware('auth')->group(function () {
    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Redirección tras login según rol
    Route::get('/dashboard', function () {
        $user = Auth::user(); // aseguramos el facade
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    })->name('dashboard');
});

// Dashboard del admin (solo para admin)
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
});

// Dashboard del usuario (solo para user)
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':user'])->group(function () {
    Route::get('/user', [UserController::class, 'index'])->name('user.dashboard');
});

// Rutas de autenticación generadas por Breeze
require __DIR__ . '/auth.php';
