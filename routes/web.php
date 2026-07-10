<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminLokasiController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/about', [PageController::class, 'about'])->name('about');

Route::middleware('session.auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/explore', [LokasiController::class, 'explore'])->name('explore');
    Route::get('/lokasi/{id}', [LokasiController::class, 'detail'])->name('detail');

    Route::get('/favorit', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorit/toggle/{id}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::post('/akun/hapus', [AccountController::class, 'destroy'])->name('account.destroy');
});

Route::prefix('admin')->middleware('admin.only')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/lokasi', [AdminLokasiController::class, 'index'])->name('admin.lokasi');
    Route::post('/lokasi/store', [AdminLokasiController::class, 'store'])->name('admin.lokasi.store');
    Route::post('/lokasi/{id}/rekomendasi', [AdminLokasiController::class, 'toggleRecommendation'])->name('admin.lokasi.recommendation.toggle');
    Route::delete('/lokasi/foto/{id}', [AdminLokasiController::class, 'deleteFoto'])->name('admin.lokasi.foto.delete');
    Route::put('/lokasi/{id}', [AdminLokasiController::class, 'update'])->name('admin.lokasi.update');
    Route::delete('/lokasi/{id}', [AdminLokasiController::class, 'destroy'])->name('admin.lokasi.delete');
    Route::get('/lokasi/delete/{id}', [AdminLokasiController::class, 'destroy']); // kompatibilitas link lama

    Route::post('/fasilitas/store', [AdminLokasiController::class, 'storeFasilitas'])->name('admin.fasilitas.store');
    Route::delete('/fasilitas/{id}', [AdminLokasiController::class, 'deleteFasilitas'])->name('admin.fasilitas.delete');
    Route::get('/fasilitas/delete/{id}', [AdminLokasiController::class, 'deleteFasilitas']); // kompatibilitas link lama

    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::post('/users/store', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.delete');
    Route::get('/users/delete/{id}', [AdminUserController::class, 'destroy']); // kompatibilitas link lama

    Route::get('/reports', [AdminReportController::class, 'index'])->name('admin.reports');
});
