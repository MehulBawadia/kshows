<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/{pageNumber?}', [HomeController::class, 'index'])->name('home');
Route::get('/movie/{id}', [MovieController::class, 'show'])->name('movie.show');
Route::get('/search/{type}/{id}', [SearchController::class, 'show'])->name('search.show');
