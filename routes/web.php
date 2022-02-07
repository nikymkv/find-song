<?php

use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('songs/{song}', [App\Http\Controllers\HomeController::class, 'view'])->name('songs.view')->whereNumber('song')->middleware('viewed');
Route::get('/authors/{user}', [App\Http\Controllers\HomeController::class, 'author'])->whereNumber('user')->name('authors.view');
Route::get('/search', [App\Http\Controllers\HomeController::class, 'search'])->name('songs.search');


Route::middleware('auth')
    ->group(function () {
        Route::prefix('songs')
            ->name('songs.')
            ->group(function () {
                Route::get('/create', [App\Http\Controllers\Front\SongController::class, 'create'])->name('create');
                Route::post('/create', [App\Http\Controllers\Front\SongController::class, 'store'])->name('store');
                Route::get('/{song}/edit', [App\Http\Controllers\Front\SongController::class, 'edit'])->name('edit');
                Route::put('/{song}/edit', [App\Http\Controllers\Front\SongController::class, 'update'])->name('update');
                Route::put('/{song}/rate', [App\Http\Controllers\Front\SongController::class, 'rate'])->name('rate');
                Route::put('/{song}/favourite', [App\Http\Controllers\Front\SongController::class, 'favourite'])->name('favourite');
            });

        Route::prefix('authors')
            ->name('authors.')
            ->group(function () {
                Route::put('/{author}/rate', [App\Http\Controllers\Front\AuthorController::class, 'rate'])->name('rate');
            });

        Route::prefix('account')
            ->name('account.')
            ->group(function () {
                Route::get('/', [App\Http\Controllers\Front\Account\AccountController::class, 'index'])->name('index');
                Route::get('/songs/favourites', [App\Http\Controllers\Front\Account\AccountController::class, 'favouritesSongs'])->name('songs.favourites');
                Route::get('/settings', [App\Http\Controllers\Front\Account\SettingsController::class, 'index'])->name('settings.index');
                Route::put('/settings', [App\Http\Controllers\Front\Account\SettingsController::class, 'update'])->name('settings.update');
                Route::put('/settings/password', [App\Http\Controllers\Front\Account\SettingsController::class, 'updatePassword'])->name('settings.password.update');
                Route::post('/settings/preview-image', [App\Http\Controllers\Front\Account\SettingsController::class, 'updateImageProfile'])->name('settings.preview_image.update');
            });
    });

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'checkRole'])
    ->group(function () {
        Route::get('/', [App\Http\Controllers\Moderator\ModeratorController::class, 'index'])->name('index');
        Route::put('/songs/{song}/moderate', [App\Http\Controllers\Moderator\ModeratorController::class, 'moderateSong'])->name('moderate');
        Route::get('/statistics', [App\Http\Controllers\Moderator\ModeratorController::class, 'getStatistic'])->name('statistics');
    });
