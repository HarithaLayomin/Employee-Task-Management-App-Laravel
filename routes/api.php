<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TopEmployeeController;

// Testing route (ensure the API is working)
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

// Public routes for employees and tasks
Route::apiResource('employees', EmployeeController::class);
Route::apiResource('tasks', TaskController::class); 

// Top employees route
Route::get('/employees/top', [TopEmployeeController::class, 'index']);

// User route for testing (requires authentication)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


