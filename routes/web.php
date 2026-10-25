<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserAdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini adalah tempat untuk mendaftarkan web routes untuk aplikasi Anda.
| Routes ini dimuat oleh RouteServiceProvider dan semuanya akan dimasukkan
| ke dalam grup middleware "web".
|
*/

// Halaman utama: Menampilkan daftar barang hilang & temuan, form pencarian, dan filter
Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [ItemController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/admin/admins', [UserAdminController::class, 'indexAdmins'])->name('admin.admins.index');
    Route::post('/admin/admins', [UserAdminController::class, 'storeAdmin'])->name('admin.admins.store');
    Route::patch('/admin/admins/{user}', [UserAdminController::class, 'updateAdmin'])->name('admin.admins.update');
    Route::delete('/admin/admins/{user}', [UserAdminController::class, 'destroyAdmin'])->name('admin.admins.destroy');
});

// Detail barang: Menampilkan detail lengkap dan forum diskusi
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
Route::post('/items/{item}/comments', [ItemController::class, 'comment'])->name('items.comment')->middleware('auth');
Route::patch('/profile', [ItemController::class, 'updateProfile'])->name('profile.update')->middleware('auth');

// Rute Autentikasi (Login, Register & Logout)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetLink'])->middleware('throttle:5,1')->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1')->name('password.update');
Route::get('/password/change', [AuthController::class, 'showPasswordChangeForm'])->middleware('auth')->name('password.change');
Route::post('/password/change', [AuthController::class, 'updatePassword'])->middleware('auth')->name('password.change.update');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::delete('/admin/users/{user}', [UserAdminController::class, 'destroy'])->middleware(['auth', 'admin'])->name('admin.users.destroy');
// Route POST: Untuk memproses penyimpanan laporan barang hilang/temuan baru (Form Request Validation) - Wajib Login
Route::post('/items', [ItemController::class, 'store'])->name('items.store')->middleware('auth');

// Route PATCH: Untuk memperbarui status klaim barang (misalnya mengubah status dari 'ditemukan' ke 'diklaim') - Wajib Login
Route::patch('/items/{id}', [ItemController::class, 'update'])->name('items.update')->middleware('auth');

// Route DELETE: Untuk menghapus laporan barang (hanya admin atau reporter) - Wajib Login
Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy')->middleware('auth');

// Rute klaim barang: mahasiswa mengajukan klaim, admin memverifikasi, dan klaim bisa dibatalkan/unclaim
Route::post('/items/{item}/claim', [ItemController::class, 'claim'])->name('items.claim')->middleware('auth');
Route::delete('/items/{item}/claim', [ItemController::class, 'unclaim'])->name('items.unclaim')->middleware('auth');
Route::patch('/items/{item}/claim/verify', [ItemController::class, 'verifyClaim'])->name('items.claim.verify')->middleware('auth');
Route::patch('/items/{item}/claim/reject', [ItemController::class, 'rejectClaim'])->name('items.claim.reject')->middleware('auth');
