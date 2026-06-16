<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ParticipantController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\AnalyticsController;



Route::group(['middleware' => ['throttle:60,1', 'jwt'], 'as' => 'api.'], function() {
    // Communities
    Route::get('/communities', [CommunityController::class, 'index']);
    Route::post('/communities', [CommunityController::class, 'store']);
    Route::get('/communities/{id}/feed', [CommunityController::class, 'feed']);
    Route::post('/communities/{id}/feed', [CommunityController::class, 'storeFeed']);
    Route::post('/communities/{id}/roles', [CommunityController::class, 'updateRoles']);

    // Events
    Route::apiResource('events', EventController::class);

    // Participants
    Route::get('/events/{id}/participants', [ParticipantController::class, 'index']);
    Route::post('/events/{id}/participants', [ParticipantController::class, 'store']);
    Route::get('/events/{id}/participants/export', [ParticipantController::class, 'export']);

    // Attendance
    Route::get('/events/{id}/attendance', [AttendanceController::class, 'index']);
    Route::post('/events/{id}/attendance/check-in', [AttendanceController::class, 'checkIn']);

    // Analytics
    Route::get('/analytics/dashboard', [AnalyticsController::class, 'dashboard']);
    Route::post('/analytics/export', [AnalyticsController::class, 'export']);


});

// Certificates (Public for Web Frontend Access)
Route::get('/certificates/templates', [CertificateController::class, 'templates']);
Route::post('/certificates/generate', [CertificateController::class, 'generate']);
Route::get('/certificates/{id}/download', [CertificateController::class, 'download']);
