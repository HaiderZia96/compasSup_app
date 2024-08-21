<?php

use App\Http\Controllers\Api\BasketController;
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


Route::group(['prefix' => 'compassup-app', 'middleware' => ['auth:sanctum']], function () {
    Route::get('get-basket', [BasketController::class, 'index']);
    Route::post('basket/create', [BasketController::class, 'create']);
    Route::post('basket/{id}/formation', [BasketController::class, 'formationType']);

});
