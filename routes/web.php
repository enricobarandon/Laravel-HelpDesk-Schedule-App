<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::view('/{any}', 'schedules.index')->middleware('auth')->where('any','.*');

Route::middleware(['auth'])->group(function(){
    
    Route::resource('schedules', \App\Http\Controllers\ScheduleController::class);

    Route::get('schedules/manage/{id}', [App\Http\Controllers\ScheduleGroupController::class, 'index'])->name('schedules.groups.manage');
    Route::post('schedules/manage/{id?}', [App\Http\Controllers\ScheduleGroupController::class, 'addGroup'])->name('schedules.groups.addGroup');
    Route::post('schedules/{scheduleId}/remove/{groupId}', [App\Http\Controllers\ScheduleGroupController::class, 'removeGroup'])->name('schedules.groups.removeGroup');
    Route::get('schedules/{scheduleId}/groups/{groupId}', [App\Http\Controllers\ScheduleGroupController::class, 'manageGroup'])->name('schedules.groups.manage');

    Route::delete('scheduledaccount/{scheduledGroupId}/account/{accountId}', [App\Http\Controllers\ScheduleGroupController::class, 'deleteScheduledAccount'])->name('schedules.accounts.delete');
    Route::post('scheduledaccount/{scheduledGroupId}/account/{accountId}', [App\Http\Controllers\ScheduleGroupController::class, 'storeScheduledAccount'])->name('schedules.accounts.store');

    Route::get('schedules/view/{id}', [App\Http\Controllers\ScheduleGroupController::class, 'view'])->name('schedules.groups.view');
    Route::put('schedules/{scheduleId}/groups/{groupId}', [App\Http\Controllers\ScheduleGroupController::class, 'updateGroup'])->name('schedules.groups.update');

    Route::get('accounts', [App\Http\Controllers\AccountController::class, 'index'])->name('accounts.index');

    Route::get('data', [App\Http\Controllers\DataController::class, 'index'])->name('data.index');
    Route::post('groups-data', [App\Http\Controllers\DataController::class, 'initialGroupsTransfer'])->name('data.initialGroupsTransfer');
    Route::post('users-data', [App\Http\Controllers\DataController::class, 'initialUsersTransfer'])->name('data.initialUsersTransfer');

    Route::get('users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');

});