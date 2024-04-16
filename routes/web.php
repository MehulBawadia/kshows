<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\TvShowController;
use Illuminate\Support\Facades\Route;

Route::get('/tv/{pageNumber?}', [TvShowController::class, 'index'])->name('tv.index');
Route::get('/tv-show/{id}', [TvShowController::class, 'show'])->name('tv.show');

Route::get('/person/{userId}', [PersonController::class, 'show'])->name('person.show');

Route::get('/{pageNumber?}', [HomeController::class, 'index'])->name('home');
Route::get('/movie/{id}', [MovieController::class, 'show'])->name('movie.show');
