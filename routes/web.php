<?php

use App\Http\Controllers\TicketController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TicketAdminController;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'userRole' => Auth::user()->user_role ?? 'user',
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');


// User routes - only for authenticated users with user_role = user
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::resource('tickets', TicketController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('tickets/{ticket}/comments', [CommentController::class, 'store'])->name('tickets.comments.store');
});

// Admin routes - only for authenticated users with user_role = admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('tickets', [TicketAdminController::class, 'index'])->name('admin.tickets.index');
    Route::get('tickets/{ticket}', [TicketAdminController::class, 'show'])->name('admin.tickets.show');
    Route::post('tickets/{ticket}/status', [TicketAdminController::class, 'updateStatus'])->name('admin.tickets.updateStatus');
    Route::post('tickets/{ticket}/internal-remarks', [TicketAdminController::class, 'addInternalRemark'])->name('admin.tickets.internalRemarks');
});

require __DIR__.'/auth.php';
