<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MemorialController;
use App\Http\Controllers\Admin\MemorialController as AdminMemorialController;
use App\Http\Controllers\Admin\ContributorController;
use App\Http\Controllers\Contributor\MemorialController as ContributorMemorialController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/memorials', [MemorialController::class, 'index'])->name('memorials.index');
Route::get('/memorials/{memorial}', [MemorialController::class, 'show'])->name('memorials.show');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $pendingCount = \App\Models\Memorial::where('status', 'pending')->count();
        $approvedCount = \App\Models\Memorial::where('status', 'approved')->count();
        $contributorCount = \App\Models\User::where('role', 'contributor')->count();
        return view('admin.dashboard', compact('pendingCount', 'approvedCount', 'contributorCount'));
    })->name('dashboard');

    // Memorials Management
    Route::resource('memorials', AdminMemorialController::class);
    Route::post('memorials/{memorial}/approve', [AdminMemorialController::class, 'approve'])->name('memorials.approve');
    Route::post('memorials/{memorial}/reject', [AdminMemorialController::class, 'reject'])->name('memorials.reject');
    Route::delete('memorials/photos/{photo}', [AdminMemorialController::class, 'deletePhoto'])->name('memorials.photos.delete');

    // Contributors Management
    Route::resource('contributors', ContributorController::class);
});

// Contributor Routes
Route::middleware(['auth', 'contributor'])->prefix('contributor')->name('contributor.')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $pendingCount = $user->memorials()->where('status', 'pending')->count();
        $approvedCount = $user->memorials()->where('status', 'approved')->count();
        $rejectedCount = $user->memorials()->where('status', 'rejected')->count();
        return view('contributor.dashboard', compact('pendingCount', 'approvedCount', 'rejectedCount'));
    })->name('dashboard');

    Route::resource('memorials', ContributorMemorialController::class);
    Route::delete('memorials/photos/{photo}', [ContributorMemorialController::class, 'deletePhoto'])->name('memorials.photos.delete');
});
