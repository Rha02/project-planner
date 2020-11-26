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

  Route::get('/projects/{project}/tasks', [App\Http\Controllers\Api\TaskController::class, 'index']);
  Route::post('/projects/{project}/tasks', [App\Http\Controllers\Api\TaskController::class, 'store']);
  Route::patch('/projects/{project}/tasks/{task}', [App\Http\Controllers\Api\TaskController::class, 'update']);
  Route::delete('/projects/{project}/tasks/{task}', [App\Http\Controllers\Api\TaskController::class, 'destroy']);
});
