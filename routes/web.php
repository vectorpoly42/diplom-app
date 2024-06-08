<?php

use App\Http\Controllers\DetailController;
use App\Http\Controllers\DetailsController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'index'])->name('main.index');
Route::get('/details', [DetailsController::class, 'index'])->name('details.index');
Route::get('/detail/create', [DetailController::class, 'create'])->name('detail.create');
Route::post('/detail', [DetailController::class, 'store'])->name('detail.store');
Route::get('/submit-order', [OrderController::class, 'create'])->name('order.create');
Route::post('/submit-order', [OrderController::class, 'store'])->name('order.store');
Route::get('/request/{id}/results', [OrderController::class, 'showResults'])->name('request.results');
