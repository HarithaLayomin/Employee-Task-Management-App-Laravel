<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route for Admin Dashboard
Route::get('/admin/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');

// Employee resource routes (CRUD operations)
Route::resource('employees', EmployeeController::class);

// Logout route
Route::post('/logout', function () {
    Auth::logout();
    return response()->json(['message' => 'Logged out']);
})->name('logout');

// Route for the "Add Employee" page
Route::get('/employees/add', function () {
    return view('admin.add-employee');
})->name('employees.add');

Route::middleware('auth:api')->group(function () {
    // Get assigned tasks for the employee
    Route::get('/employee/tasks', [TaskController::class, 'getAssignedTasks']);

    // Update task status (pending/completed)
    Route::patch('/employee/tasks/{taskId}/update-status', [TaskController::class, 'updateStatus']);
});


require __DIR__.'/auth.php';
