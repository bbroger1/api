<?php

use App\Http\Controllers\TasksController;
use App\Http\Controllers\TaskListController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JwtMiddleware;

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

Route::post('register', [UserController::class, 'store'])->name('users.store');
Route::post('login', [UserController::class, 'login'])->name('users.login');

/*middleware mudou para jwt.auth ao invés de jwt.verify, não é preciso fazer
de registro no kernel do laravel*/

Route::group(['prefix' => 'v1', 'middleware' => 'jwt.auth'], function () {
    Route::apiResources([
        'tasklist'  =>  TaskListController::class,
        'tasks' => TasksController::class
    ]);

    Route::put('task/close/id', [TasksController::class, 'closeTask'])->name('tasks.closeTask');
    Route::get('list/task/{id}', [TaskListController::class, 'tasksByList'])->name('tasks.tasksByList');
    Route::post('completedTaskList', [TaskListController::class, 'completedTaskList'])->name('tasklist.completedTaskList');
    Route::post('logout', [UserController::class, 'logout'])->name('users.logout');
});
