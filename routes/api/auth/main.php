<?php
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyOtpController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Backend Routes
|--------------------------------------------------------------------------
|
| Here is where you can register backend web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the prefix "admin" middleware group. Now create something great!
|
*/


Route::group(['prefix' => 'auth', 'middleware' => ['checkAppAuth']], function () {

    Route::post('/signup', [AuthenticationController::class, 'signUp']);
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::post('/logout', [AuthenticationController::class, 'logout']);

    Route::post('password/forgot',[ForgotPasswordController::class,'forgotPassword']);
    Route::post('verify/otp',[VerifyOtpController::class,'verifyOTP']);
    Route::post('password/reset',[ResetPasswordController::class,'resetPassword']);
});



