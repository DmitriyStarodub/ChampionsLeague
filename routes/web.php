<?php

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
Route::get('/', function () { return view('league');});

Route::get('/next-week', [\App\Http\Controllers\LeaguesController::class, 'nextWeek'])->name('next_week');
Route::get('/all', [\App\Http\Controllers\LeaguesController::class, 'playAll'])->name('all');

Route::get('/test', function () {
    return view('league');
});
