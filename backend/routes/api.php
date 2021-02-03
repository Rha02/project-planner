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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/refresh', [App\Http\Controllers\AuthController::class, 'refresh']);
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);

Route::middleware('auth:api')->group(function ($router) {
  Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
  Route::get('/user', [App\Http\Controllers\AuthController::class, 'user']);

  Route::get('/projects', [App\Http\Controllers\Api\ProjectController::class, 'index']);
  Route::get('/projects/{project}', [App\Http\Controllers\Api\ProjectController::class, 'show']);
  Route::post('/projects', [App\Http\Controllers\Api\ProjectController::class, 'store']);
  Route::delete('/projects/{project}', [App\Http\Controllers\Api\ProjectController::class, 'destroy']);
  Route::patch('/projects/{project}', [App\Http\Controllers\Api\ProjectController::class, 'update']);

  Route::get('/projects/{project}/goals', [App\Http\Controllers\Api\GoalController::class, 'index']);
  Route::post('/projects/{project}/goals', [App\Http\Controllers\Api\GoalController::class, 'store']);
  Route::get('/projects/{project}/goals/{goal}', [App\Http\Controllers\Api\GoalController::class, 'show']);
  Route::patch('/projects/{project}/goals/{goal}', [App\Http\Controllers\Api\GoalController::class, 'update']);
  Route::delete('/projects/{project}/goals/{goal}', [App\Http\Controllers\Api\GoalController::class, 'destroy']);

  Route::get('/projects/{project}/goals/{goal}/tasks', [App\Http\Controllers\Api\TaskController::class, 'index']);
  Route::post('/projects/{project}/goals/{goal}/tasks', [App\Http\Controllers\Api\TaskController::class, 'store']);
  Route::patch('/projects/{project}/goals/{goal}/tasks/{task}', [App\Http\Controllers\Api\TaskController::class, 'update']);
  Route::delete('/projects/{project}/goals/{goal}/tasks/{task}', [App\Http\Controllers\Api\TaskController::class, 'destroy']);

  Route::post('/projects/{project}/sequence', [App\Http\Controllers\Api\SequenceController::class, 'store']);
  Route::delete('/projects/{project}/sequence/{task}', [App\Http\Controllers\Api\SequenceController::class, 'destroy']);

  Route::get('/projects/{project}/members', [App\Http\Controllers\Api\ProjectUserController::class, 'index']);
  Route::post('/projects/{project}/members', [App\Http\Controllers\Api\ProjectUserController::class, 'store']);
  Route::delete('/projects/{project}/members', [App\Http\Controllers\Api\ProjectUserController::class, 'destroy']);

  Route::get('/users', [App\Http\Controllers\Api\UserController::class, 'show']);

  Route::get('/user/tasks', [App\Http\Controllers\Api\UserTaskController::class, 'index']);
});
