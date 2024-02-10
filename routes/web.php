<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index'])->name('index');

Auth::routes();
Route::get('/different-account', [App\Http\Controllers\HomeController::class, 'getDifferentAccount'])->name('different-account');

Route::group(['middleware' => ['auth', 'auth.admin']], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [App\Http\Controllers\User\ViewController::class, 'index'])->name('users');
        Route::get('/create', [App\Http\Controllers\User\ViewController::class, 'form'])->name('users.form');
        Route::get('/{id}/detail', [App\Http\Controllers\User\ViewController::class, 'detail'])->name('users.detail');
        Route::get('/{id}/edit', [App\Http\Controllers\User\ViewController::class, 'form'])->name('users.edit');

        Route::get('/get', [App\Http\Controllers\User\DataController::class, 'get'])->name('users.get');
        Route::post('/create', [App\Http\Controllers\User\DataController::class, 'submit']);
        Route::post('/{id}/edit', [App\Http\Controllers\User\DataController::class, 'submit']);
        Route::post('/{id}/resetAttempt', [App\Http\Controllers\User\DataController::class, 'resetAttempt']);
        Route::put('/{id}/restore', [App\Http\Controllers\User\DataController::class, 'restore'])->name('users.restore');
        Route::delete('/{id}', [App\Http\Controllers\User\DataController::class, 'destroy'])->name('users.destroy');
    });

    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', [App\Http\Controllers\Role\ViewController::class, 'index'])->name('roles');
        Route::get('/create', [App\Http\Controllers\Role\ViewController::class, 'form'])->name('roles.form');
        Route::get('/{id}/detail', [App\Http\Controllers\Role\ViewController::class, 'detail'])->name('roles.detail');
        Route::get('/{id}/edit', [App\Http\Controllers\Role\ViewController::class, 'form'])->name('roles.edit');

        Route::get('/get', [App\Http\Controllers\Role\DataController::class, 'get'])->name('roles.get');
        Route::post('/create', [App\Http\Controllers\Role\DataController::class, 'submit']);
        Route::post('/{id}/edit', [App\Http\Controllers\Role\DataController::class, 'submit']);
        Route::delete('/{id}', [App\Http\Controllers\Role\DataController::class, 'destroy']);
    });

    Route::group(['prefix' => 'permission'], function () {
        Route::get('/', [App\Http\Controllers\Permission\ViewController::class, 'index'])->name('permission');
        Route::get('/create', [App\Http\Controllers\Permission\ViewController::class, 'form'])->name('permission.form');
        Route::get('/{id}/detail', [App\Http\Controllers\Permission\ViewController::class, 'detail'])->name('permission.detail');
        Route::get('/{id}/edit', [App\Http\Controllers\Permission\ViewController::class, 'form'])->name('permission.edit');

        Route::get('/get', [App\Http\Controllers\Permission\DataController::class, 'get'])->name('permission.get');
        Route::post('/create', [App\Http\Controllers\Permission\DataController::class, 'submit']);
        Route::post('/{id}/edit', [App\Http\Controllers\Permission\DataController::class, 'submit']);
        Route::delete('/{id}', [App\Http\Controllers\Permission\DataController::class, 'destroy']);
    });

    Route::group(['prefix' => 'modules'], function () {
        Route::get('/', [App\Http\Controllers\Modules\ViewController::class, 'index'])->name('module');
        Route::get('/{id}/detail', [App\Http\Controllers\Modules\ViewController::class, 'detail'])->name('module.detail');
        Route::get('/create', [App\Http\Controllers\Modules\ViewController::class, 'form'])->name('module.form');
        Route::get('/{id}/edit', [App\Http\Controllers\Modules\ViewController::class, 'form'])->name('module.edit');

        Route::get('/get', [App\Http\Controllers\Modules\DataController::class, 'get'])->name('module.get');
        Route::post('/create', [App\Http\Controllers\Modules\DataController::class, 'submit']);
        Route::post('/{id}/edit', [App\Http\Controllers\Modules\DataController::class, 'submit']);
        Route::post('/{id}/module-feature', [App\Http\Controllers\Modules\DataController::class, 'submitFeature']);
        Route::delete('/{id}', [App\Http\Controllers\Modules\DataController::class, 'destroy']);
    });

    Route::group(['prefix' => 'entity'], function () {
        Route::get('/', [App\Http\Controllers\Entity\ViewController::class, 'index'])->name('entity');
        Route::get('/{id}/detail', [App\Http\Controllers\Entity\ViewController::class, 'detail'])->name('entity.detail');
        Route::get('/create', [App\Http\Controllers\Entity\ViewController::class, 'form'])->name('entity.form');
        Route::get('/{id}/edit', [App\Http\Controllers\Entity\ViewController::class, 'form'])->name('entity.edit');

        Route::get('/get', [App\Http\Controllers\Entity\DataController::class, 'get'])->name('entity.get');
        Route::post('/create', [App\Http\Controllers\Entity\DataController::class, 'submit']);
        Route::post('/{id}/edit', [App\Http\Controllers\Entity\DataController::class, 'submit']);
        Route::delete('/{id}', [App\Http\Controllers\Entity\DataController::class, 'destroy']);
    });

    Route::group(['prefix' => 'oclient'], function () {
        Route::get('/', [App\Http\Controllers\OAuthClient\ViewController::class, 'index'])->name('oclient');
        Route::get('/{id}/detail', [App\Http\Controllers\OAuthClient\ViewController::class, 'detail'])->name('oclient.detail');
        Route::get('/create', [App\Http\Controllers\OAuthClient\ViewController::class, 'form'])->name('oclient.form');
        Route::get('/{id}/edit', [App\Http\Controllers\OAuthClient\ViewController::class, 'form'])->name('oclient.edit');

        Route::get('/get', [App\Http\Controllers\OAuthClient\DataController::class, 'get'])->name('oclient.get');
        Route::post('/create', [App\Http\Controllers\OAuthClient\DataController::class, 'submit']);
        Route::post('/{id}/edit', [App\Http\Controllers\OAuthClient\DataController::class, 'submit']);
        Route::delete('/{id}', [App\Http\Controllers\OAuthClient\DataController::class, 'destroy']);
    });

    Route::group(['prefix' => 'logs'], function () {
        // Route::get('/system-logs', [App\Http\Controllers\Logs\SystemLogsController::class, 'index'])->name('logs.system');
        Route::get('system-logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->name('logs.system');
    });

    Route::group(['prefix' => 'select2'], function () {
        Route::get('/get-entity', [\App\Http\Controllers\Entity\DataController::class, 'select2'])->name('select2.get-entity');
        Route::get('/get-module', [\App\Http\Controllers\Modules\DataController::class, 'select2'])->name('select2.get-module');
    });
});
