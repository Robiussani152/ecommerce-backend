<?php

use App\Events\OrderPlacedEvent;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderPlacedNotification;
use Illuminate\Support\Facades\Notification;
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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/send-notification', function () {
    $order = Order::find(2);
    $message = "Order received {$order->invoice_no} total amount of {$order->total_amount}";
    event(new OrderPlacedEvent($message));
});
