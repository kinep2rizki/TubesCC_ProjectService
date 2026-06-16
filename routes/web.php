<?php

use Illuminate\Support\Facades\Route;

// Project Service is now a Pure API Backend.
// All endpoints are located in routes/api.php

Route::get('/', function () {
    return response()->json([
        'service' => 'Project Service',
        'status' => 'OK',
        'message' => 'Backend is running. Please access via /api'
    ]);
});