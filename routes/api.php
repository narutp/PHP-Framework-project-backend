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

// Route::apiResource('user', 'UserController');

Route::get('/tasks', 'TasksController@index');
Route::middleware('auth:api')->post('/create_task', 'TasksController@store');
Route::delete('/task', 'TasksController@delete');
Route::middleware('auth:api')->put('/user', 'UserController@update');
Route::middleware('auth:api')->get('/task/incomplete', 'TasksController@indexIncomplete');
Route::post('/login', 'Auth\LoginController@login');

// Route::resource('user', 'UserController')->only(['show']);
Route::middleware('auth:api')->post('/user', 'UserController@store');
Route::get('/users', 'UserController@index');
Route::middleware('auth:api')->get('/user/subordinates', 'UserController@indexSubordinates');
Route::middleware('auth:api')->get('/user/colleague', 'UserController@indexColleague');
Route::middleware('auth:api')->get('/supervisor/tasks', 'UserController@subordinateTask');
Route::middleware('auth:api')->put('/user/set_role', 'UserController@setRole');
Route::middleware('auth:api')->put('/user/set_hierarchy', 'UserController@setHierarchy');
Route::middleware('auth:api')->get('/supervisor/leaves', 'LeavesController@leaveHistory');
Route::middleware('auth:api')->put('/user/set_department', 'UserController@setDepartment');

Route::apiResource('leave', 'LeavesController');
Route::middleware('auth:api')->get('/leaves', 'LeavesController@index');
Route::middleware('auth:api')->post('/leave', 'LeavesController@store');
Route::middleware('auth:api')->post('/leave/{leave}/approve', 'LeavesController@approve');
Route::middleware('auth:api')->post('/leave/{leave}/reject', 'LeavesController@reject');
Route::middleware('auth:api')->post('/leave/{leave}/cancel', 'LeavesController@cancel');

