<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('admin/login', [AuthController::class, 'login']);
Route::post('employee/login', [AuthController::class, 'login']);

Route::middleware('auth:api,admin')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
    
    // Push Notifications
    Route::post('push-subscriptions', [\App\Http\Controllers\Api\PushSubscriptionController::class, 'store']);
    Route::delete('push-subscriptions', [\App\Http\Controllers\Api\PushSubscriptionController::class, 'destroy']);
    
    // Admin Routes
    Route::middleware(['role:super_admin|admin'])->prefix('admin')->group(function () {
        
        // Admin Anonymous Chat Routes
        Route::middleware('permission:manage_support')->prefix('anonymous-chat')->group(function() {
            Route::get('sessions', [\App\Http\Controllers\Api\Admin\AnonymousChatController::class, 'index']);
            Route::post('sessions/{sessionToken}/reply', [\App\Http\Controllers\Api\Admin\AnonymousChatController::class, 'reply']);
            Route::get('sessions/{sessionToken}/messages', [\App\Http\Controllers\Api\Admin\AnonymousChatController::class, 'getMessages']);
        });

        Route::middleware('permission:manage_employees')->group(function() {
            Route::apiResource('employees', \App\Http\Controllers\Admin\EmployeeController::class);
        });

        Route::middleware('permission:manage_projects')->group(function() {
            Route::apiResource('projects', \App\Http\Controllers\Admin\ProjectController::class);
            Route::post('projects/{project}/assign-employees', [\App\Http\Controllers\Admin\ProjectController::class, 'assignEmployees']);
        });

        Route::middleware('permission:manage_tasks')->group(function() {
            Route::apiResource('tasks', \App\Http\Controllers\Admin\TaskController::class);
            Route::post('tasks/{task}/assign-employees', [\App\Http\Controllers\Admin\TaskController::class, 'assignEmployees']);
        });

        Route::middleware('permission:manage_support')->group(function() {
            Route::apiResource('daily-logs', \App\Http\Controllers\Admin\DailyLogController::class)->only(['index', 'show', 'update', 'destroy']);
            Route::apiResource('support-tickets', \App\Http\Controllers\Admin\SupportTicketController::class)->only(['index', 'show', 'update']);
            Route::post('support-tickets/{ticket}/reply', [\App\Http\Controllers\Admin\SupportTicketController::class, 'reply']);
        });

        // Lookup Routes
        Route::get('departments', [\App\Http\Controllers\Api\Admin\DepartmentController::class, 'index']);
        Route::get('designations', [\App\Http\Controllers\Api\Admin\DesignationController::class, 'index']);
    });

    // Employee Routes
    Route::prefix('employee')->group(function () {
        Route::prefix('anonymous-chat')->group(function () {
            Route::post('start', [\App\Http\Controllers\Api\Employee\AnonymousChatController::class, 'startSession']);
            // Rate limit sending messages: 10 per minute max to prevent spamming
            Route::post('{sessionToken}/message', [\App\Http\Controllers\Api\Employee\AnonymousChatController::class, 'sendMessage'])->middleware('throttle:10,1');
            Route::get('{sessionToken}/messages', [\App\Http\Controllers\Api\Employee\AnonymousChatController::class, 'getMessages']);
        });
    });

    // Test route
    Route::get('test', function () {
        return response()->json([
            'message' => 'You have successfully accessed the protected test route using a valid JWT token.',
            'user' => auth('api')->user()
        ]);
    });
});
