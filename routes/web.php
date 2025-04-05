<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::group(['middleware' => ['role:admin_room_911']], function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');

        //Routes to manage users
        Route::controller(\App\Http\Controllers\UsersController::class)->prefix('users')->name('users.')->group(function(){
            Route::get('/', 'index')->name('index');
            Route::post('/search', 'search')->name('search');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{user}', 'edit')->name('edit');
            Route::post('/update', 'update')->name('update');
            Route::post('/block/{user}', 'block')->name('block');
            Route::post('/destroy/{user}', 'destroy')->name('destroy');
            Route::get('/users/{user}/pdf', 'attemptsPdf')->name('pdf');
            Route::post('/import', 'import')->name('import');
        });
    });

    Route::group(['middleware' => ['role:worker']], function () {
        Route::get('/worker', function () {
            return Inertia::render('Welcome');
        })->name('worker.home');
    });
});
