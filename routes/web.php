<?php

//use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\AuthenticateSession;

//Front page
Route::group([], function (){
    Route::get('/auth',[\App\Http\Controllers\UserController::class,'sign'])->name('authP');
});

//Back controller
Route::group([AuthenticateSession::class], function (){
    Route::post('/authenticate',[\App\Http\Controllers\UserController::class,'authenticate'])->name('auth');
});

//Auth
Route::middleware('Auth')->group(function () {

    //chatroom
    Route::get('/', [\App\Http\Controllers\MessageController::class, 'index'])->name('chat.index');

    //Admin access
    Route::middleware('Admin')->group(function () {
        Route::get('/dashboard/user', [\App\Models\User::class, 'index'])->name('user.index');
        Route::post('/chat/send', [\App\Http\Controllers\MessageController::class, 'store'])->name('chat.store');
        Route::get('/chat/stream', [\App\Http\Controllers\SseController::class, 'stream']);

        //user change access and role
        Route::put('/user/{user}/access', [\App\Http\Controllers\UserController::class, 'access_change'])->name('users.toggleAccess');
        Route::put('/user/{user}/role', [\App\Http\Controllers\UserController::class, 'role_change'])->name('users.changeRole');
        Route::delete('/user/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
    });
});

//// نمایش صفحه چت
//Route::get('/chat', [ChatController::class, 'index']);
//
//// ذخیره پیام جدید (فرانت با AJAX این را صدا می‌زند)
//Route::post('/chat/send', [ChatController::class, 'store']);
//
//// کانال SSE (فرانت با EventSource این را صدا می‌زند)
//Route::get('/chat/stream', [ChatController::class, 'stream']);

