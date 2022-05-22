<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('schedules', \App\Http\Controllers\Api\ScheduleController::class);

// Route::apiResource('groups', \App\Http\Controllers\Api\GroupController::class);
Route::get('/groups/active', [\App\Http\Controllers\Api\GroupController::class, 'getActiveGroups']);
Route::get('/groups/deactivated', [\App\Http\Controllers\Api\GroupController::class, 'getDeactivatedGroups']);