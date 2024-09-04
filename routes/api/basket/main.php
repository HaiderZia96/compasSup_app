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


Route::group(['prefix' => 'compas-sup-app', 'middleware' => ['checkAuthToken']], function () {
    Route::get('get-basket', [BasketController::class, 'index']);
    Route::post('basket/add', [BasketController::class, 'create']);
    Route::post('basket/remove', [BasketController::class, 'destroy']);
    Route::post('basket/{id}/checked-formation', [BasketController::class, 'checkedFormation']);

});
