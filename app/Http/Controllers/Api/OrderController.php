<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function placeOrder(OrderRequest $orderRequest)
    {
        return $this->orderService->placeOrder($orderRequest);
    }

    public function updateOrder(OrderRequest $orderRequest, $id)
    {
        return $this->orderService->placeOrder($orderRequest, $id);
    }

    public function getOrder($id)
    {
        return $this->orderService->getOrderDetails($id);
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'bail|required|in:approved,rejected,processing,shipped,delivered'
        ]);
        return $this->orderService->updateStatus($request, $id);
    }
}
