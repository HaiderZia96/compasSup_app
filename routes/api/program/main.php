<?php

use App\Http\Controllers\Api\ProgramController;
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
    Route::get('get-program', [ProgramController::class, 'index']);
    Route::get('program/{id}/get', [ProgramController::class, 'programById']);
    Route::get('get-program-with-filters', [ProgramController::class, 'indexFilters']);
    Route::get('program/{id}/seen', [ProgramController::class, 'programSeenById']);
    Route::post('program/{id}/apply', [ProgramController::class, 'apply']);
});

