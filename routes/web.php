<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('auth')->group(function() {
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    Route::post('/logout', [LogoutController::class, 'store'])->name('logout.store');
});

Route::middleware('auth')->prefix('email')->group(function() {
    Route::get('/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('dashboard')->with('success', '!Tu cuenta ha sido verificada con éxito! Ya puedes administrar tus presupuestos y gastos.');
    })->middleware('signed')->name('verification.verify');

    Route::get('/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::post('/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', '¡Se ha reenviado un nuevo enlace de verificación a tu correo electrónico!');
    })->middleware('throttle:1,1')->name('verification.send');
});

Route::prefix('dashboard')->group(function () {
    Route::get('/', [BudgetController::class, 'index'])->name('dashboard');
    Route::get('/budgets/create', [BudgetController::class, 'create'])->name('budgets.create');
    Route::post('/budgets', [BudgetController::class, 'store'])->name('budgets.store');
    Route::get('/budgets/{budget}', [BudgetController::class, 'show'])->name('budgets.show');
    Route::get('/budgets/{budget}/edit', [BudgetController::class, 'edit'])->name('budgets.edit');
    Route::put('/budgets/{budget}', [BudgetController::class, 'update'])->name('budgets.update');
    Route::delete('/budgets/{budget}', [BudgetController::class, 'destroy'])->name('budgets.destroy');

    Route::post('/budgets/{budget}/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::put('/budgets/{budget}/expenses/${expense}', [ExpenseController::class, 'update'])->name('expenses.update');
});