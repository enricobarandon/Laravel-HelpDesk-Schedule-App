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

Route::middleware(['auth:sanctum'])->group(function(){

    Route::apiResource('/schedules', \App\Http\Controllers\Api\ScheduleController::class);
    
    // Route::apiResource('groups', \App\Http\Controllers\Api\GroupController::class);
    Route::get('/groups/view/active', [\App\Http\Controllers\Api\GroupController::class, 'getActiveGroups']);
    Route::get('/groups/view/deactivated', [\App\Http\Controllers\Api\GroupController::class, 'getDeactivatedGroups']);
    Route::get('/groups/view/pullout', [\App\Http\Controllers\Api\GroupController::class, 'getPulledOutGroups']);
    Route::get('/groups/{group}', [\App\Http\Controllers\Api\GroupController::class, 'show'])->name('groups.show');
    Route::put('/groups/{group}', [\App\Http\Controllers\Api\GroupController::class, 'update'])->name('groups.update');
    
    Route::post('/requests/groups', [\App\Http\Controllers\Api\RequestController::class, 'storeGroupRequest'])->name('storeGroupRequest');
    Route::post('/requests/accounts', [\App\Http\Controllers\Api\RequestController::class, 'storeAccountRequest'])->name('storeAccountRequest');

    Route::get('/requests', [\App\Http\Controllers\Api\RequestController::class, 'index'])->name('requests.index.getData');
});

Route::post('/requests', [\App\Http\Controllers\Api\RequestController::class, 'updateRequest'])->name('requests.update');

Route::put('/ocbs', [\App\Http\Controllers\Api\OcbsController::class, 'update'])->name('ocbs.update');
Route::post('/ocbs', [\App\Http\Controllers\Api\OcbsController::class, 'create'])->name('ocbs.create');