<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Exports\ComplaintExport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', fn() => "laravel vercel deployment works!");
// Route untuk pengguna umum
Route::get('/', [ComplaintController::class, 'create'])->name('complaint.create');
Route::post('/complaint', [ComplaintController::class, 'store'])->name('complaint.store');

// Route untuk login & registrasi admin (hanya untuk yang belum login)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.forgot');
    Route::post('/forgot-password', [AuthController::class, 'resetPasswordWithoutEmail'])->name('password.reset.direct');
});

Route::middleware('auth')->group(function () {
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.update');
});
// Logout hanya untuk yang sudah login
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Route untuk admin dengan autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/complaint/{id}', [AdminController::class, 'show'])->name('admin.show');
    // Route::post('/admin/complaint/{id}/validate', [AdminController::class, 'validateComplaint'])->name('admin.validate');
    Route::put('admin/complaint/{id}/validate', [AdminController::class, 'validateComplaint'])->name('admin.validate');
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/filter', [AdminController::class, 'filter'])->name('admin.filter');

    // Route::get('/admin/complaint/{id}/edit', [AdminController::class, 'edit'])->name('admin.complaint.edit');
    // Route::put('/admin/complaint/{id}', [AdminController::class, 'update'])->name('admin.complaint.update');
    // routes/web.php

    Route::get('/admin/complaint/{complaint}/edit', [AdminController::class, 'edit'])->name('admin.complaint.edit');
    Route::put('/admin/complaints/{id}', [AdminController::class, 'update'])->name('admin.complaint.update');
    Route::delete('/admin/complaints/{id}', [AdminController::class, 'destroy'])->name('complaint.destroy');
    Route::get('/admin/export-jenis-aduan', [AdminController::class, 'exportByJenisAduan'])->name('admin.export.jenis');


});

Route::get('/check-status', [ComplaintController::class, 'checkStatusForm'])->name('complaint.checkStatusForm');
Route::post('/check-status', [ComplaintController::class, 'checkStatus'])->name('complaint.checkStatus');

Route::get('/complaint/{nama}/donwload', [ComplaintController::class, 'downloadResi'])->name('complaint.download');
Route::post('/complaint/{id}/selesai', [ComplaintController::class, 'markAsDone'])->name('complaint.markAsDone');
Route::get('/admin/complaints/pdf', [AdminController::class, 'exportPDF'])->name('admin.complaint.exportPDF');
Route::get('/admin/complaints/export', [AdminController::class, 'exportExcel'])->name('admin.complaint.export');

Route::get('/admin/complaints/pdf', [AdminController::class, 'exportPDF'])->name('admin.complaint.exportPDF');

