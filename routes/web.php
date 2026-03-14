<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SetPasswordController;


Route::get('/debug-manifest', function() {
    $manifestPath = public_path('build/manifest.json');
    if (file_exists($manifestPath)) {
        return response()->json([
            'exists' => true,
            'content' => json_decode(file_get_contents($manifestPath)),
            'build_files' => glob(public_path('build/assets/*'))
        ]);
    }
    return response()->json(['exists' => false]);
});

// Add this BEFORE the auth routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Set Password
Route::get('/set-password', [SetPasswordController::class, 'show'])->name('set-password.show');
Route::post('/set-password', [SetPasswordController::class, 'store'])->name('set-password.store');
// Password Reset
Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'resetPassword'])->name('password.update');

// Messages (all roles)
Route::middleware(['auth'])->group(function () {
    Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
    Route::get('/digital-books', [App\Http\Controllers\DigitalBookController::class, 'index'])->name('digital.index');
    Route::get('/digital-books/{book}/read', [App\Http\Controllers\DigitalBookController::class, 'read'])->name('digital.read');
    Route::post('/digital-sessions/{session}/expire', [App\Http\Controllers\DigitalBookController::class, 'expire'])->name('digital.expire');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [App\Http\Controllers\ProfileController::class, 'changePassword'])->name('profile.change-password');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // MUST be before resource route
    Route::get('/users/password-links', [App\Http\Controllers\Admin\UserController::class, 'passwordLinks'])->name('users.password-links');
    Route::post('/users/import', [App\Http\Controllers\Admin\UserController::class, 'import'])->name('users.import');
    
    // Resource route AFTER custom routes
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::get('/books/template', [App\Http\Controllers\Admin\BookController::class, 'template'])->name('books.template');
    Route::post('/books/import', [App\Http\Controllers\Admin\BookController::class, 'import'])->name('books.import');
    Route::resource('books', App\Http\Controllers\Admin\BookController::class);
    
    Route::post('/users/{user}/toggle', [App\Http\Controllers\Admin\UserController::class, 'toggle'])->name('users.toggle');
    Route::post('/users/{user}/resend', [App\Http\Controllers\Admin\UserController::class, 'resendSetPassword'])->name('users.resend');
    
    Route::get('/borrows', [App\Http\Controllers\Admin\BorrowController::class, 'index'])->name('borrows.index');
    Route::post('/borrows/{borrow}/approve', [App\Http\Controllers\Admin\BorrowController::class, 'approve'])->name('borrows.approve');
    Route::post('/borrows/{borrow}/claim', [App\Http\Controllers\Admin\BorrowController::class, 'claim'])->name('borrows.claim');
    Route::post('/borrows/{borrow}/return', [App\Http\Controllers\Admin\BorrowController::class, 'returning'])->name('borrows.return');
    Route::post('/borrows/{borrow}/cancel', [App\Http\Controllers\Admin\BorrowController::class, 'cancel'])->name('borrows.cancel');
    Route::get('/borrows/{borrow}', [App\Http\Controllers\Admin\BorrowController::class, 'show'])->name('borrows.show');
    Route::get('/penalties', [App\Http\Controllers\Admin\PenaltyController::class, 'index'])->name('penalties.index');
    Route::post('/penalties/{penalty}/mark-paid', [App\Http\Controllers\Admin\PenaltyController::class, 'markPaid'])->name('penalties.mark-paid');
    Route::post('/penalties/mark-all-paid', [App\Http\Controllers\Admin\PenaltyController::class, 'markAllPaid'])->name('penalties.mark-all-paid');
    Route::get('/penalties/{penalty}/receipt', [App\Http\Controllers\Admin\PenaltyController::class, 'receipt'])->name('penalties.receipt');
    Route::get('/attendance', [App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/kiosk', [App\Http\Controllers\Admin\AttendanceController::class, 'kiosk'])->name('attendance.kiosk');
    Route::post('/attendance/kiosk/scan', [App\Http\Controllers\Admin\AttendanceController::class, 'kioskScan'])->name('attendance.kiosk.scan');
    Route::post('/attendance/scan', [App\Http\Controllers\Admin\AttendanceController::class, 'scan'])->name('attendance.scan');
    Route::get('/attendance/export', [App\Http\Controllers\Admin\AttendanceController::class, 'export'])->name('attendance.export');
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');
});

// Faculty Routes
Route::middleware(['auth', 'role:faculty'])->prefix('faculty')->name('faculty.')->group(function () {
     Route::get('/dashboard', [App\Http\Controllers\Faculty\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/borrows', [App\Http\Controllers\Faculty\BorrowController::class, 'index'])->name('borrows.index');
    Route::get('/borrows/create', [App\Http\Controllers\Faculty\BorrowController::class, 'create'])->name('borrows.create');
    Route::post('/borrows', [App\Http\Controllers\Faculty\BorrowController::class, 'store'])->name('borrows.store');
    Route::get('/penalties', [App\Http\Controllers\Faculty\PenaltyController::class, 'index'])->name('penalties.index');
    Route::get('/books', [App\Http\Controllers\Faculty\BorrowController::class, 'books'])->name('books.index');
});

// Student Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/borrows', [App\Http\Controllers\Student\BorrowController::class, 'index'])->name('borrows.index');
    Route::get('/borrows/create', [App\Http\Controllers\Student\BorrowController::class, 'create'])->name('borrows.create');
    Route::post('/borrows', [App\Http\Controllers\Student\BorrowController::class, 'store'])->name('borrows.store');
    Route::get('/penalties', [App\Http\Controllers\Student\PenaltyController::class, 'index'])->name('penalties.index');
    Route::get('/books', [App\Http\Controllers\Student\BorrowController::class, 'books'])->name('books.index');
});

// Digital Books
Route::middleware(['auth'])->group(function () {
    Route::get('/digital-books', [App\Http\Controllers\DigitalBookController::class, 'index'])->name('digital.index');
    Route::get('/digital-books/{book}/read', [App\Http\Controllers\DigitalBookController::class, 'read'])->name('digital.read');
    Route::post('/digital-sessions/{session}/expire', [App\Http\Controllers\DigitalBookController::class, 'expire'])->name('digital.expire');
});


