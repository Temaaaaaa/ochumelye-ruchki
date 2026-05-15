<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CreativityTypeController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MasterClassController;
use App\Http\Controllers\MasterDashboardController;
use App\Models\CreativityType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/creativity/{creativityType}', [CreativityTypeController::class, 'show'])->name('types.show');
Route::get('/types/{creativityType}', fn (CreativityType $creativityType): RedirectResponse => redirect()->route('types.show', $creativityType))
    ->name('types.legacy');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::get('/bookings/{masterClass}/confirm', [EnrollmentController::class, 'create'])
        ->name('bookings.confirm');
    Route::post('/bookings/{masterClass}/confirm', [EnrollmentController::class, 'store'])
        ->name('bookings.store');
    Route::post('/bookings/{masterClass}/cancel', [EnrollmentController::class, 'cancel'])
        ->name('bookings.cancel');
});

Route::middleware(['auth', 'teacher'])->group(function (): void {
    Route::get('/cabinet', [MasterDashboardController::class, 'index'])->name('cabinet.index');
    Route::get('/cabinet/master-classes/{masterClass}', [MasterDashboardController::class, 'show'])->name('cabinet.show');

    Route::get('/master-classes/create', [MasterClassController::class, 'create'])->name('master-classes.create');
    Route::post('/master-classes', [MasterClassController::class, 'store'])->name('master-classes.store');
    Route::get('/master-classes/{masterClass}/edit', [MasterClassController::class, 'edit'])->name('master-classes.edit');
    Route::match(['put', 'patch'], '/master-classes/{masterClass}', [MasterClassController::class, 'update'])->name('master-classes.update');
});
