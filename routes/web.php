<?php

use App\Http\Controllers\DetailController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'index'])->name('main.index');
Route::get('/details', [DetailController::class, 'allDetails'])->name('detail.allDetails');
Route::get('/detail/create', [DetailController::class, 'create'])->name('detail.create');
Route::post('/detail', [DetailController::class, 'store'])->name('detail.store');
Route::get('/submit-order', [OrderController::class, 'create'])->name('order.create');
Route::post('/submit-order', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/{id}/results', [OrderController::class, 'show'])->name('order.show');
Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
Route::get('/order/{id}/download', [OrderController::class, 'download'])->name('order.download');
Route::get('/upload', [OrderController::class, 'showUploadForm'])->name('upload.form');
Route::post('/upload', [OrderController::class, 'uploadAndProcessFile'])->name('upload.process');
Route::get('/data', [OrderController::class, 'listData'])->name('data.list');
Route::get('/data/{id}', [OrderController::class, 'showinfoData'])->name('data.show');
