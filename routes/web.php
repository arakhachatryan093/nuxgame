<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('register');
});
Route::post('/register', [MainController::class, 'register'])->name('register');
Route::get('/links/{link}', [MainController::class, 'specialPage'])->name('special_page.index');
Route::get('/links/{user}/regenerate', [MainController::class, 'regenerate'])->name('special_page.regenerate');
Route::delete('/links/{user}/delete', [MainController::class, 'deleteLink'])->name('special_page.delete');
Route::get('/links/{user}/lucky', [MainController::class, 'imFeelingLucky'])->name('special_page.lucky');
Route::get('/users/{user}/history', [MainController::class, 'getHistory'])->name('special_page.history');
