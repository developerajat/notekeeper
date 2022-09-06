<?php

use Illuminate\Support\Facades\Route;

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
    if (Auth::check()) {
        return redirect(route('index'));
    }
    return redirect(route('loginForm'));
})->name('home');

Route::namespace('App\Http\Controllers')->group(function() {

    Route::group(['middleware' => 'guest'], function() {
        Route::get('login', 'AuthController@loginform')->name('loginForm');
        Route::post('login', 'AuthController@login')->name('login');
        Route::get('register', 'AuthController@registerForm')->name('registerForm');
        Route::post('register', 'AuthController@register')->name('register');
        Route::get('forget-password','AuthController@forgetPasswordForm')->name('forgetPasswordForm');
        Route::post('forget-password','AuthController@forgetPassword')->name('forgetPassword');

        Route::get('password/reset/{id}', 'AuthController@resetPasswordForm')->name('password.resetForm');
        Route::post('password/reset/{id}','AuthController@resetPassword')->name('password.reset');

        Route::get('otp-verification/{userId}', 'AuthController@verifyOtpForm')->name('verificationScreen');
        Route::get('verify-otp/resend', 'AuthController@resendVerificationOtp')->name('verify.otp.resend');
        Route::post('verify-otp/{id}', 'AuthController@verifyOtp')->name('verify.otp');
    });

    Route::group(['middleware' => 'auth'], function() {
        Route::get('logout', 'AuthController@logout')->name('logout');
        Route::get('pinned', 'NoteController@pinned')->name('notes.pinned');
        Route::get('trashed', 'NoteController@trashed')->name('notes.trashed');
        Route::post('force-delete/{id}', 'NoteController@forceDelete')->name('forceDelete');
        Route::post('destroy/{note}', 'NoteController@destroy')->name('destroy');
        Route::post('update/{note}', 'NoteController@update')->name('notes.update');
        Route::post('notes/store', 'NoteController@store')->name('store');
        Route::post('sketch-notes/store', 'NoteController@storeSketch')->name('storeSketch');
        Route::get('notes/pin', 'NoteController@pin')->name('notes.pin');
        Route::get('notes/restore', 'NoteController@restore')->name('notes.restore');
        Route::resource('', NoteController::class)->except(['destroy', 'store', 'show']);
    });

});
