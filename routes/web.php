<?php

use Illuminate\Support\Facades\Route;

//Front page
Route::group([], function (){
    Route::get('/auth',[\App\Models\User::class,'index'])->name('authP');
});

//Back controller
Route::group([], function (){
    Route::post('/authenticate',[\App\Models\User::class,'authenticate'])->name('auth');
});

//Auth
Route::middleware('Auth')->group(function () {

    //Admin access
    Route::middleware('Admin')->group(function () {
        Route::get('/dashboard/user', [\App\Models\User::class, 'index'])->name('user.index');
    });
});

