<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/login', 'Auth\LoginController@login');

// Route::resource('user', 'UserController')->only(['show']);
Route::apiResource('user', 'UserController');
Route::get('/users', 'UserController@index');
Route::middleware('auth:api')->put('/user', 'UserController@update');
Route::middleware('auth:api')->post('/user', 'UserController@store');
Route::middleware('auth:api')->get('/user/subordinates', 'UserController@indexSubordinates');
Route::middleware('auth:api')->get('/user/colleague', 'UserController@indexColleague');
Route::middleware('auth:api')->get('/supervisor/tasks', 'UserController@subordinateTask');
Route::middleware('auth:api')->put('/user/set_roll', 'UserController@setRoll');
Route::middleware('auth:api')->get('/supervisor/leaves', 'LeavesController@leaveHistory');
Route::middleware('auth:api')->put('/user/set_department', 'UserController@setDepartment');

Route::apiResource('tasks', 'TasksController');
Route::delete('/task', 'TasksController@delete');
Route::middleware('auth:api')->post('/create_task', 'TasksController@store');
Route::middleware('auth:api')->get('/task/incomplete', 'TasksController@indexIncomplete');
Route::middleware('auth:api')->post('/task/{task}/reassign/{user}', 'TasksController@reassign');
Route::middleware('auth:api')->get('/tasks/reassigned', 'TasksController@indexReassigned');
Route::middleware('auth:api')->post('/task/{task}/approve_reassigned', 'TasksController@approveReassign');
Route::middleware('auth:api')->post('/task/{task}/reject_reassigned', 'TasksController@rejectReassign');

Route::apiResource('leave', 'LeavesController');
Route::middleware('auth:api')->get('/leaves', 'LeavesController@index');
Route::middleware('auth:api')->post('/leave', 'LeavesController@store');
Route::middleware('auth:api')->post('/leave/{leave}/approve', 'LeavesController@approve');
Route::middleware('auth:api')->post('/leave/{leave}/reject', 'LeavesController@reject');
Route::middleware('auth:api')->post('/leave/{leave}/cancel', 'LeavesController@cancel');

