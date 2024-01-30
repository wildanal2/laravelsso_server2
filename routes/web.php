<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [App\Http\Controllers\User\ViewController::class, 'index'])->name('users');
        Route::get('/create', [App\Http\Controllers\User\ViewController::class, 'form'])->name('users.form');
        Route::get('/{id}/edit', [App\Http\Controllers\User\ViewController::class, 'form'])->name('users.edit');

        Route::get('/get', [App\Http\Controllers\User\DataController::class, 'get'])->name('users.get');
    });

    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', [App\Http\Controllers\Role\ViewController::class, 'index'])->name('roles');
        Route::get('/create', [App\Http\Controllers\Role\ViewController::class, 'form'])->name('roles.form');
        Route::get('/{id}/detail', [App\Http\Controllers\Role\ViewController::class, 'detail'])->name('roles.detail');
        Route::get('/{id}/edit', [App\Http\Controllers\Role\ViewController::class, 'form'])->name('roles.edit');

        Route::get('/get', [App\Http\Controllers\Role\DataController::class, 'get'])->name('roles.get');
        Route::post('/create', [App\Http\Controllers\Role\DataController::class, 'submit']);
        Route::post('/{id}/edit', [App\Http\Controllers\Role\DataController::class, 'submit']);
    });

    Route::group(['prefix' => 'permission'], function () {
        Route::get('/', [App\Http\Controllers\Permission\ViewController::class, 'index'])->name('permission');
        Route::get('/create', [App\Http\Controllers\Permission\ViewController::class, 'form'])->name('permission.form');
        Route::get('/{id}/detail', [App\Http\Controllers\Permission\ViewController::class, 'detail'])->name('permission.detail');
        Route::get('/{id}/edit', [App\Http\Controllers\Permission\ViewController::class, 'form'])->name('permission.edit');

        Route::get('/get', [App\Http\Controllers\Permission\DataController::class, 'get'])->name('permission.get');
        Route::post('/create', [App\Http\Controllers\Permission\DataController::class, 'submit']);
        Route::post('/{id}/edit', [App\Http\Controllers\Permission\DataController::class, 'submit']);
    });

    Route::group(['prefix' => 'modules'], function () {
        Route::get('/', [App\Http\Controllers\Modules\ViewController::class, 'index'])->name('module');
        Route::get('/{id}/detail', [App\Http\Controllers\Modules\ViewController::class, 'detail'])->name('module.detail');
        Route::get('/create', [App\Http\Controllers\Modules\ViewController::class, 'form'])->name('module.form');
        Route::get('/{id}/edit', [App\Http\Controllers\Modules\ViewController::class, 'form'])->name('module.edit');

        Route::get('/get', [App\Http\Controllers\Modules\DataController::class, 'get'])->name('module.get');
        Route::post('/create', [App\Http\Controllers\Modules\DataController::class, 'submit']);
        Route::post('/{id}/edit', [App\Http\Controllers\Modules\DataController::class, 'submit']);
    });
    
});
