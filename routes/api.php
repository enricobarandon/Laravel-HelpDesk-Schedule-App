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

Route::apiResource('/schedules', \App\Http\Controllers\Api\ScheduleController::class);

// Route::apiResource('groups', \App\Http\Controllers\Api\GroupController::class);
Route::get('/groups/view/active', [\App\Http\Controllers\Api\GroupController::class, 'getActiveGroups']);
Route::get('/groups/view/deactivated', [\App\Http\Controllers\Api\GroupController::class, 'getDeactivatedGroups']);
Route::get('/groups/{group}', [\App\Http\Controllers\Api\GroupController::class, 'show'])->name('groups.show');
Route::put('/groups/{group}', [\App\Http\Controllers\Api\GroupController::class, 'update'])->name('groups.update');

Route::post('/requests/groups', [\App\Http\Controllers\Api\RequestController::class, 'storeGroupRequest']);

Route::post('/requests', [\App\Http\Controllers\Api\RequestController::class, 'updateRequest'])->name('requests.update');