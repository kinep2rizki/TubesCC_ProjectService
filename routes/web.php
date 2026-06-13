<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Pages.Dashboard');
})->name('dashboard');

Route::get('/events', function () {
    return view('Pages.EventManagement');
})->name('events');

Route::get('/event-detail', function () {
    return view('Pages.EventDetail');
})->name('event-detail');

Route::get('/participants', function () {
    return view('Pages.ParticipantsPage');
})->name('participants');

Route::get('/attendance', function () {
    return view('Pages.Attendance');
})->name('attendance');

Route::get('/certificates', function () {
    return view('Pages.Certificate');
})->name('certificates');

Route::get('/communities', function () {
    return view('Pages.Community');
})->name('communities');

Route::get('/analytics', function () {
    return view('Pages.Analytics');
})->name('analytics');

Route::get('/settings', function () {
    return view('Pages.PlatformSettings');
})->name('settings');
