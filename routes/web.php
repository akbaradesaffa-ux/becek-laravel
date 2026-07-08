<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

use App\Http\Controllers\LokasiController;

Route::get('/explore', [LokasiController::class, 'explore'])->name('explore');

Route::get('/lokasi/{id}', [LokasiController::class, 'detail'])->name('detail');

use App\Http\Controllers\PageController;

Route::get('/about', [PageController::class, 'about'])->name('about');

use App\Http\Controllers\AdminLokasiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminReportController;

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/lokasi', [AdminLokasiController::class, 'index'])->name('admin.lokasi');
    Route::post('/lokasi/store', [AdminLokasiController::class, 'store'])->name('admin.lokasi.store');
    Route::get('/lokasi/delete/{id}', [AdminLokasiController::class, 'destroy'])->name('admin.lokasi.delete');

    Route::post('/fasilitas/store', [AdminLokasiController::class, 'storeFasilitas'])->name('admin.fasilitas.store');
    Route::get('/fasilitas/delete/{id}', [AdminLokasiController::class, 'deleteFasilitas'])->name('admin.fasilitas.delete');

    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::get('/users/delete/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.delete');
    
    Route::get('/reports', [AdminReportController::class, 'index'])->name('admin.reports');
});