<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\TipeSuratController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect('/login');
    });
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Change Password
    Route::get('/change-password', [PasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/change-password', [PasswordController::class, 'change'])->name('password.update');

    // User Management (Admin Only)
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('tipe-surat', TipeSuratController::class);
    });

    // Reports (Admin & Pemimpin only)
    Route::middleware('role:admin,pemimpin')->group(function () {
        Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
    });

    // Surat routes
    Route::resource('surat', SuratController::class);

    // Approve/Reject - Admin and Pemimpin only
    Route::middleware('role:admin,pemimpin')->group(function () {
        Route::post('/surat/{surat}/approve', [SuratController::class, 'approve'])->name('surat.approve');
        Route::post('/surat/{surat}/reject', [SuratController::class, 'reject'])->name('surat.reject');
    });

    // Print (cetak) - All authenticated users (policy checks if approved)
    Route::get('/surat/{surat}/cetak', [SuratController::class, 'print'])->name('surat.cetak');
});
