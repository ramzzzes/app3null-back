<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/add', [UserController::class,'add']);
Route::post('/verify', [UserController::class,'verify']);
Route::get('/get', [UserController::class,'get']);
Route::get('/country', [UserController::class,'country']);
