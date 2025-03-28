<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Controllers\AuthController;

// Public routes
Route::get('/', function () {
    return response()->json(['message' => 'Hello World']);
});


// Protected routes
Route::middleware(EnsureFrontendRequestsAreStateful::class)->group(function () {
    Route::get('/app', function () {
        return response()->json(['message' => 'Hello CPP Gateway']);
    });

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
