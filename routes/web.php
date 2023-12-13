<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class,'index']);
    Route::get('/logout', function () {
        auth()->logout();
        request()->session()->regenerate();
        return redirect(route('login'));
    });
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class,'index'])->name('login');
    Route::post('login', [AuthController::class,'login'])->name('login');
    Route::get('register', [AuthController::class,'create'])->name('register');
    Route::post('register', [AuthController::class,'store'])->name('register');
});

