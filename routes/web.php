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
    return view('auth.login');
    // return view('welcome');
})->name('login.page');

Auth::routes();


Route::middleware(['auth'])->group(function(){
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    Route::resource('/schedules', \App\Http\Controllers\ScheduleController::class);

    Route::get('/schedules-past', [App\Http\Controllers\ScheduleController::class, 'pastSchedules'])->name('schedules.pastSchedules');
    Route::get('/schedules/manage/{id}', [App\Http\Controllers\ScheduleGroupController::class, 'index'])->name('schedules.groups.manage');
    Route::post('/schedules/manage/{id?}', [App\Http\Controllers\ScheduleGroupController::class, 'addGroup'])->name('schedules.groups.addGroup');
    Route::post('/schedules/{scheduleId}/remove/{groupId}', [App\Http\Controllers\ScheduleGroupController::class, 'removeGroup'])->name('schedules.groups.removeGroup');
    Route::get('/schedules/{scheduleId}/groups/{groupId}', [App\Http\Controllers\ScheduleGroupController::class, 'manageGroup'])->name('schedules.groups.manage');

    Route::delete('/scheduledaccount/{scheduledGroupId}/account/{accountId}', [App\Http\Controllers\ScheduleGroupController::class, 'deleteScheduledAccount'])->name('schedules.accounts.delete');
    Route::post('/scheduledaccount/{scheduledGroupId}/account/{accountId}', [App\Http\Controllers\ScheduleGroupController::class, 'storeScheduledAccount'])->name('schedules.accounts.store');

    Route::get('/schedules/view/{id}', [App\Http\Controllers\ScheduleGroupController::class, 'view'])->name('schedules.groups.view');
    Route::put('/schedules/{scheduleId}/groups/{groupId}', [App\Http\Controllers\ScheduleGroupController::class, 'updateGroup'])->name('schedules.groups.update');

    Route::get('/accounts', [App\Http\Controllers\AccountController::class, 'index'])->name('accounts.index');
    Route::get('/accounts/deactivated', [App\Http\Controllers\AccountController::class, 'accountsDeactivated'])->name('accounts.accounts-deactivated');
    Route::get('/accounts/create', [App\Http\Controllers\AccountController::class, 'create'])->name('accounts.create');
    Route::get('/accounts/{account}', [App\Http\Controllers\AccountController::class, 'show'])->name('accounts.edit');
    Route::post('/accounts/update-password/{account}', [App\Http\Controllers\AccountController::class, 'updatePassword'])->name('updatePassword');
    Route::post('/accounts/update-status/{account}', [App\Http\Controllers\AccountController::class, 'updateStatus'])->name('updateStatus');

    Route::get('/data', [App\Http\Controllers\DataController::class, 'index'])->name('data.index');
    Route::post('/groups-data', [App\Http\Controllers\DataController::class, 'initialGroupsTransfer'])->name('data.initialGroupsTransfer');
    Route::post('/users-data', [App\Http\Controllers\DataController::class, 'initialUsersTransfer'])->name('data.initialUsersTransfer');

    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');

    Route::get('/groups/view/{status}', [\App\Http\Controllers\GroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/create', [App\Http\Controllers\GroupController::class, 'create'])->name('groups.create');
    Route::get('groups/request/edit/{$group}',[App\Http\Controllers\GroupController::class, 'show'])->name('groups.show');

    Route::post('/requests/groups', [\App\Http\Controllers\RequestController::class, 'groupStatusUpdate'])->name('requests.groups.update');

    Route::get('requests', [\App\Http\Controllers\RequestController::class, 'index'])->name('requests.index');
    
    Route::get('logs', [\App\Http\Controllers\ActivityLogsController::class, 'index'])->name('logs.index');

    Route::get('users/update/{id}/info', [\App\Http\Controllers\UserController::class, 'updateUser'])->name('users.updateUsers');
    Route::get('users/update/{id}/password', [\App\Http\Controllers\UserController::class, 'updateUser'])->name('users.updateUsers');
    Route::post('submitUser', [\App\Http\Controllers\UserController::class, 'submitUser'])->name('users.updateUsers');
    Route::post('users/{user}',[\App\Http\Controllers\UserController::class, 'changeUserStatus'])->name('users.changeUserStatus');

    Route::get('cband',[\App\Http\Controllers\CbandController::class, 'index'])->name('cband.index');
    Route::post('cband',[\App\Http\Controllers\CbandController::class, 'changeViewingStatus'])->name('cband.changeViewingStatus');
});

// Route::view('/{any}', 'schedules.index')
//     ->middleware('auth')
//     ->where('any', '.*');

Route::get('/{any}', [\App\Http\Controllers\SpaController::class, 'index'])->middleware('auth')
    ->where('any', '.*');