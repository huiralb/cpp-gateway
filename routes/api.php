<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

Route::get('/user', function (Request $request) {
    return response()->json(['message' => 'Hello World']);
    // return $request->user();
})->middleware(EnsureFrontendRequestsAreStateful::class);
