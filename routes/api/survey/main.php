<?php

use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\SubQuestionController;
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
    Route::post('question/create', [QuestionController::class, 'create']);
    Route::get('get-question', [QuestionController::class, 'index']);
    Route::post('question/edit/{id}', [QuestionController::class, 'edit']);

    Route::post('sub-question/create', [SubQuestionController::class, 'create']);
});
