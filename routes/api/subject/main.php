<?php

use App\Http\Controllers\Api\SubjectController;
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
    Route::get('get-subject', [SubjectController::class, 'index']);
    Route::get('get-subject-category/{id}', [SubjectController::class, 'subCategory']);

});
