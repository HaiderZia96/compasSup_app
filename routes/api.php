<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
require __DIR__ . '/api/auth/main.php';
require __DIR__ . '/api/profile/main.php';
require __DIR__ . '/api/program/main.php';
require __DIR__ . '/api/basket/main.php';
require __DIR__ . '/api/subject/main.php';
require __DIR__ . '/api/subject-sub-category/main.php';
require __DIR__ . '/api/survey/main.php';
require __DIR__ . '/api/answer/main.php';

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
