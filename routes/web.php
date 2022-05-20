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
Route::resource('schedules', \App\Http\Controllers\ScheduleController::class)->middleware('auth');

Route::get('schedules/manage/{id}', [App\Http\Controllers\ScheduleGroupController::class, 'index'])->name('schedules.groups.manage')->middleware('auth');
Route::post('schedules/manage/{id?}', [App\Http\Controllers\ScheduleGroupController::class, 'addGroup'])->name('schedules.groups.addGroup')->middleware('auth');
Route::post('schedules/{scheduleId}/remove/{groupId}', [App\Http\Controllers\ScheduleGroupController::class, 'removeGroup'])->name('schedules.groups.removeGroup')->middleware('auth');
Route::get('schedules/{scheduleId}/groups/{groupId}', [App\Http\Controllers\ScheduleGroupController::class, 'manageGroup'])->name('schedules.groups.manage')->middleware('auth');

Route::delete('scheduledaccount/{scheduledGroupId}/account/{accountId}', [App\Http\Controllers\ScheduleGroupController::class, 'deleteScheduledAccount'])->name('schedules.accounts.delete')->middleware('auth');
Route::post('scheduledaccount/{scheduledGroupId}/account/{accountId}', [App\Http\Controllers\ScheduleGroupController::class, 'storeScheduledAccount'])->name('schedules.accounts.store')->middleware('auth');

Route::get('schedules/view/{id}', [App\Http\Controllers\ScheduleGroupController::class, 'view'])->name('schedules.groups.view')->middleware('auth');