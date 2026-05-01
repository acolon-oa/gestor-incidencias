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
    
    // Notification Routes
    Route::get('/notifications', function () {
        $notifications = auth()->user()->notifications()->paginate(15);
        return view('notifications.index', compact('notifications'));
    })->name('notifications.index');

    Route::post('/notifications/{id}/read', function ($id) {
        auth()->user()->notifications()->where('id', $id)->first()?->markAsRead();
        return back();
    })->name('notifications.read');

    Route::post('/notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    })->name('notifications.read-all');

    // Profile Routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/attachments/{attachment}/download', [\App\Http\Controllers\AttachmentController::class, 'download'])->name('attachments.download');
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

        // Bulk Actions
        Route::delete('/tickets/bulk-delete', [AdminTicketController::class, 'bulkDelete'])->name('tickets.bulk-delete');

        Route::get('/tickets/{ticket}/export-pdf', [AdminTicketController::class, 'exportPdf'])->name('tickets.export-pdf');

        // Admin puede: listar, ver, crear y guardar tickets
        Route::resource('tickets', AdminTicketController::class)->only([
            'index',
            'show',
            'create',
            'store',
            'update',
            'destroy',
        ]);

        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::get('/statistics', [\App\Http\Controllers\Admin\StatisticsController::class, 'index'])->name('statistics.index');
        Route::get('/statistics/export-pdf', [\App\Http\Controllers\Admin\StatisticsController::class, 'exportPdf'])->name('statistics.export-pdf');


        Route::resource('canned-responses', \App\Http\Controllers\Admin\CannedResponseController::class)->except(['show']);
        Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-logs.index');

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
            'show',
            'update',
        ]);
    });

// Auth (Breeze)
require __DIR__.'/auth.php';
