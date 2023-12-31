<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
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
    })->name('logout');

    # untuk verifikasi email
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect('/');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    })->middleware(['throttle:3,1'])->name('verification.send');

    Route::get('order/{order}/schedule-create',[OrderController::class,'scheduleCreate'])
    ->name('order.schedule.create');
    Route::resource('order',OrderController::class);
    Route::resource('user',UserController::class);
    Route::resource('report',ReportController::class);

    Route::middleware('verified')->group(function(){
        Route::get('transaction/{transaction}/transactionpaid',[TransactionController::class,'transactionpaid']);
        Route::get('transaction/{transaction}/snap',[TransactionController::class,'snap'])->name('transaction.snap');
        Route::resource('transaction',TransactionController::class);
    });

    Route::get('/schedule/{schedule}/createreport',[DashboardController::class,'scheduleReportCreate'])
    ->name('schedule.report.create');
    Route::post('/schedule/{schedule}/createreport',[DashboardController::class,'scheduleReportStore'])
    ->name('schedule.report.store');
    Route::get('/schedule/{schedule}/edit',[DashboardController::class,'scheduleEdit'])
    ->name('schedule.edit');
    Route::delete('/schedule/{schedule}',[DashboardController::class,'scheduleDelete'])
    ->name('schedule.delete');
    Route::put('/schedule/{schedule}',[DashboardController::class,'scheduleUpdate'])
    ->name('schedule.update');

});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class,'index'])->name('login');
    Route::post('login', [AuthController::class,'login'])->name('login');
    Route::get('register', [AuthController::class,'create'])->name('register');
    Route::post('register', [AuthController::class,'store'])->name('register');
});

