<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\AdController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/ads');
});

Route::get('/home', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('home');

// email verification routing
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
 
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
 
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// StarAds core routing
Route::get('ads', [AdController::class, 'index'])->name('ads.index');
Route::put('ads/create', [AdController::class, 'create'])->name('ads.create')->middleware(['auth', 'verified']);
Route::get('ads/create', [AdController::class, 'create'])->name('ads.create')->middleware(['auth', 'verified']);
Route::post('ads', [AdController::class, 'store'])->name('ads.store')->middleware(['auth', 'verified']);
Route::get('ads/{id}', [AdController::class, 'show'])->name('ads.show')->middleware(['auth', 'verified']);
Route::post('ads/{id}', [AdController::class, 'update'])->name('ads.update')->middleware(['auth', 'verified']);
Route::delete('ads/{id}', [AdController::class, 'destroy'])->name('ads.destroy')->middleware(['auth', 'verified']);

Route::get('user/{id}', [UserController::class, 'show'])->name('user.show')->middleware(['auth', 'verified']);
Route::post('user/{id}', [UserController::class, 'update'])->name('user.update')->middleware(['auth', 'verified']);
Route::delete('user/{id}', [UserController::class, 'destroy'])->name('user.destroy')->middleware(['auth', 'verified']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
