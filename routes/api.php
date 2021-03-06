<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('login', [AuthController::class, 'login'])->middleware('guest');
Route::post('register', [AuthController::class, 'register'])->middleware('guest');

/**
 * Products
 */

Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'getAuthUser']);
    //Products related route
    Route::post('products', [ProductController::class, 'store'])->middleware('ability:add-product');
    Route::put('products/{id}', [ProductController::class, 'update'])->middleware('ability:update-product');
    Route::delete('products/{id}', [ProductController::class, 'destroy'])->middleware('ability:delete-product');
    Route::post('update-stock', [ProductController::class, 'updateStock'])->middleware('ability:product-stock-update');
    //order related routes
    Route::get('orders', [OrderController::class, 'getAllOrders']);
    Route::post('place-order', [OrderController::class, 'placeOrder']);
    Route::post('update-order/{id}', [OrderController::class, 'updateOrder']);
    Route::get('order-details/{id}', [OrderController::class, 'getOrder']);
    Route::post('order-status-update/{id}', [OrderController::class, 'updateOrderStatus'])->middleware('ability:order-status-update');
    //logout
    Route::post('logout', [AuthController::class, 'logout']);
});
