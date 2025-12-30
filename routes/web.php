<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\TipeSuratController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect('/login');
    });
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // AJAX Search API
    Route::get('/api/search', [DashboardController::class, 'search'])->name('api.search');

    // Change Password
    Route::get('/change-password', [PasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/change-password', [PasswordController::class, 'change'])->name('password.update');

    // User Management (Admin Only)
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('tipe-surat', TipeSuratController::class);

        // Settings
        Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
        Route::get('/settings/remove-logo', [\App\Http\Controllers\SettingController::class, 'removeLogo'])->name('settings.remove-logo');

        // Role Permissions
        Route::get('/admin/permissions', [\App\Http\Controllers\RolePermissionController::class, 'index'])->name('admin.permissions');
        Route::put('/admin/permissions', [\App\Http\Controllers\RolePermissionController::class, 'update'])->name('admin.permissions.update');
    });

    // Pegawai Management (Admin, Admin Surat Keluar, Pemimpin, Operator)
    Route::middleware('role:admin,admin_surat_keluar,pemimpin,operator')->group(function () {
        Route::resource('pegawai', PegawaiController::class);
    });

    // Laporan (Admin, Pemimpin, Operator)
    Route::middleware('role:admin,pemimpin,operator')->group(function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');
        Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
    });

    // Surat Masuk (Admin, Admin Surat Masuk, Pemimpin)
    Route::middleware('role:admin,admin_surat_masuk,pemimpin')->group(function () {
        Route::resource('surat-masuk', SuratMasukController::class);
    });

    // Surat routes (all authenticated users can view, policy controls create/update)
    Route::resource('surat', SuratController::class);

    // Approve/Reject - Admin, Admin Surat Keluar, Pemimpin, Operator
    Route::middleware('role:admin,admin_surat_keluar,pemimpin,operator')->group(function () {
        Route::get('/surat/{surat}/approve', [SuratController::class, 'showApprove'])->name('surat.approve.show');
        Route::post('/surat/{surat}/approve', [SuratController::class, 'approve'])->name('surat.approve');
        Route::get('/surat/{surat}/reject', [SuratController::class, 'showReject'])->name('surat.reject.show');
        Route::post('/surat/{surat}/reject', [SuratController::class, 'reject'])->name('surat.reject');
    });

    // Resubmit rejected letter (Pegawai can resubmit their own)
    Route::post('/surat/{surat}/resubmit', [SuratController::class, 'resubmit'])->name('surat.resubmit');

    // Print (cetak) - All authenticated users (policy checks if approved)
    Route::get('/surat/{surat}/cetak', [SuratController::class, 'print'])->name('surat.cetak');

    // Download file surat
    Route::get('/surat/{surat}/download', [SuratController::class, 'download'])->name('surat.download');
});
