<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
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

    # untuk verifikasi email
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect('/');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    })->middleware(['throttle:3,1'])->name('verification.send');

    Route::resource('order',OrderController::class);
    Route::resource('user',UserController::class);

    Route::middleware('verified')->group(function(){
        Route::get('transaction/{transaction}/snap',[TransactionController::class,'snap'])->name('transaction.snap');
        Route::resource('transaction',TransactionController::class);
    });

});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class,'index'])->name('login');
    Route::post('login', [AuthController::class,'login'])->name('login');
    Route::get('register', [AuthController::class,'create'])->name('register');
    Route::post('register', [AuthController::class,'store'])->name('register');
});

