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

    //chatroom
    Route::get('/index', []);

    //Admin access
    Route::middleware('Admin')->group(function () {
        Route::get('/dashboard/user', [\App\Models\User::class, 'index'])->name('user.index');

        //user change access and role
        Route::put('/user/{user}/access', [\App\Http\Controllers\UserController::class, 'access_change'])->name('users.toggleAccess');
        Route::put('/user/{user}/role', [\App\Http\Controllers\UserController::class, 'role_change'])->name('users.changeRole');
        Route::delete('/user/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
    });
});

