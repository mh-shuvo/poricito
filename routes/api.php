<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LocationController;

Route::get('/locations', [LocationController::class, 'locations']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
