<?php

use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::group(['prefix' => 'user', 'middleware' => ['checkAuthToken']], function () {
    Route::post('change-password', [ProfileController::class, 'changePassword']);
    Route::post('profile/edit', [ProfileController::class, 'editProfile']);
    Route::post('profile/complete', [ProfileController::class, 'completeProfile']);
    Route::get('detail', [ProfileController::class, 'userDetail']);

    // Statistics
    Route::get('statistics', [ProfileController::class, 'statistics']);
});
