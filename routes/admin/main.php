<?php


use App\Http\Controllers\Admin\ProgramController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    Route::resource('program',ProgramController::class);
    Route::get('get-program',[ProgramController::class,'getIndex'])->name('get-program');

});

