<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthManager;
use App\Http\Controllers\TaskManager;


Route::get('/login', function () {
  return view('auth.login');
})->name('login');


Route::post('/login', [AuthManager::class, 'LoginPost'])->name('login.post');


Route::get('/register', [AuthManager::class, 'register'])->name('register');
Route::post('/register', [AuthManager::class, 'registerPost'])->name('register.post');
Route::middleware('auth')->group(function () {
  //Route::get('/tasks', [TaskManager::class, 'index']);
  Route::resource('tasks', TaskManager::class);
  Route::get('/', [TaskManager::class, 'index'])->name('home');

  // Home (task list)
  // Route::get('/', [TaskManager::class, 'listTask'])->name('home');

  // Add Task
  // Route::get('/task/add', [TaskManager::class, 'addTask'])->name('task.add');
  //Route::post('/task/add', [TaskManager::class, 'addTaskPost'])->name('task.add.post');

  // Update task status (e.g., mark as completed)
  // Route::post('/task/status/{id}', [TaskManager::class, 'updateTaskStatus'])->name('task.status.update');

  // Delete task
  //  Route::delete('/task/delete/{id}', [TaskManager::class, 'deleteTask'])->name('task.delete');
});
