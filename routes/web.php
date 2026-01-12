<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

// Ruta raíz
Route::get('/', function () {
  return Auth::check()
    ? redirect()->route('dashboard')
    : redirect()->route('login');
});

// Rutas protegidas por autenticación
Route::middleware(['auth', 'nocache'])->group(function () {

  // Dashboard principal, redirige según rol
  Route::get('/dashboard', function () {
    $user = Auth::user();
    return $user->hasRole('admin')
      ? redirect()->route('admin.dashboard')
      : redirect()->route('user.dashboard');
  })->name('dashboard');
});

// Dashboard de administrador
Route::middleware(['auth', 'role:admin', 'nocache'])->group(function () {
  Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
});

// Dashboard de usuario
Route::middleware(['auth', 'role:user', 'nocache'])->group(function () {
  Route::get('/user', [UserController::class, 'index'])->name('user.dashboard');
});

// Rutas de autenticación (Breeze)
require __DIR__ . '/auth.php';
