<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;

Route::get('order', [OrderController::class, 'index']);
// route to store order from frontend
Route::post('order', [OrderController::class, 'store']);
// print route for invoice after order created
Route::get('order/{order}/print', [OrderController::class, 'print'])->name('order.print');

Route::get('/', [HomeController::class, 'index']);
Route::get('product', [ProductController::class, 'index']);
Route::get('category', [CategoryController::class, 'index']);
