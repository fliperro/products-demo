<?php

use App\Http\Controllers\ProductController;
use App\Models\Product;
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

Route::redirect('/', 'products');

Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class)->only('create', 'edit');
});

Route::resource('products', ProductController::class)->only('index', 'show');



require __DIR__.'/auth.php';
