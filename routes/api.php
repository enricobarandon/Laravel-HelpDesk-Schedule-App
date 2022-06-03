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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::apiResource('/schedules', \App\Http\Controllers\Api\ScheduleController::class)->middleware('auth:sanctum');

// Route::apiResource('groups', \App\Http\Controllers\Api\GroupController::class);
Route::get('/groups/view/active', [\App\Http\Controllers\Api\GroupController::class, 'getActiveGroups'])->middleware('auth:sanctum');
Route::get('/groups/view/deactivated', [\App\Http\Controllers\Api\GroupController::class, 'getDeactivatedGroups'])->middleware('auth:sanctum');
Route::get('/groups/{group}', [\App\Http\Controllers\Api\GroupController::class, 'show'])->name('groups.show')->middleware('auth:sanctum');
Route::put('/groups/{group}', [\App\Http\Controllers\Api\GroupController::class, 'update'])->name('groups.update')->middleware('auth:sanctum');

Route::post('/requests/groups', [\App\Http\Controllers\Api\RequestController::class, 'storeGroupRequest'])->middleware('auth:sanctum');



Route::post('/requests', [\App\Http\Controllers\Api\RequestController::class, 'updateRequest'])->name('requests.update');

Route::put('/ocbs', [\App\Http\Controllers\Api\OcbsController::class, 'update'])->name('ocbs.update');
Route::post('/ocbs', [\App\Http\Controllers\Api\OcbsController::class, 'create'])->name('ocbs.create');