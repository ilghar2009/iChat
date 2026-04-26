<?php

use Illuminate\Support\Facades\Route;

Route::get('/auth',[\App\Models\User::class,'auth'])->name('auth');