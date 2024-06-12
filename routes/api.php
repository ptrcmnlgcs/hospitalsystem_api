<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalRecordController;


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

    Route::get('patient', [PatientController::class, 'index']);
    Route::post('patient', [PatientController::class,'store']);
    Route::get('patient/{id}', [PatientController::class,'show']);
    Route::put('patient/{id}', [PatientController::class,'update']);
    Route::delete('patient/{id}', [PatientController::class,'destroy']);

    Route::get('appointments', [AppointmentController::class, 'index']);
    Route::post('appointments', [AppointmentController::class,'store']);
    Route::get('appointments/{id}', [AppointmentController::class,'show']);
    Route::put('appointments/{id}', [AppointmentController::class,'update']);
    Route::delete('appointments/{id}', [AppointmentController::class,'destroy']);

    Route::get('medical_records', [MedicalRecordController::class, 'index']);
    Route::post('medical_records', [MedicalRecordController::class,'store']);
    Route::get('medical_records/{id}', [MedicalRecordController::class,'show']);
    Route::put('medical_records/{id}', [MedicalRecordController::class,'update']);
    Route::delete('medical_records/{id}', [MedicalRecordController::class,'destroy']);
});
