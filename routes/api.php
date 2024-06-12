<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DoctorController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::group(['middleware' => ['auth:sanctum']], function() {

    Route::get('user', [UserController::class, 'index']);
    Route::post('user', [UserController::class,'store']);
    Route::get('user/{id}', [UserController::class,'show']);
    Route::put('user/{id}', [UserController::class,'update']);

    Route::get('doctor', [DoctorController::class, 'index']);
    Route::post('doctor', [DoctorController::class,'store']);
    Route::get('doctor/{id}', [DoctorController::class,'show']);
    Route::put('doctor/{id}', [DoctorController::class,'update']);
    Route::delete('doctor/{id}', [DoctorController::class,'destroy']);
});