<?php

use App\Http\Controllers\Api\AnswerController;
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


Route::group(['prefix' => 'compas-sup-app', 'middleware' => ['checkAuthToken']], function () {
    Route::get('get-answer', [AnswerController::class, 'index']);
    Route::post('answer/create', [AnswerController::class, 'create']);
});
