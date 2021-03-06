<?php

namespace App\Services;

use Exception;
use Throwable;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use App\Models\DeliveredOrder;
use App\Events\OrderPlacedEvent;
use App\Models\OrderEditHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\OrderResource;
use Symfony\Component\HttpFoundation\Response;

class OrderService
{
    public $order, $orderDetails, $orderEditHistory, $productService, $deliveredOrder;

    public function __construct(Order $order, OrderDetails $orderDetails, OrderEditHistory $orderEditHistory, ProductService $productService, DeliveredOrder $deliveredOrder)
    {
        $this->order = $order;
        $this->orderDetails = $orderDetails;
        $this->orderEditHistory = $orderEditHistory;
        $this->productService = $productService;
        $this->deliveredOrder = $deliveredOrder;
    }

    public function getOrders(Request $request)
    {
        if ($request->has('status') and $request->status == $this->order::DELIVERED) {
            $orders = $this->deliveredOrder->query();
        } else {
            $orders = $this->order->query();
        }
        $orders = $orders->select('id', 'customer_id', 'invoice_no', 'status', 'created_at', 'total_amount')->with(['customer:id,name'])
            ->when($request->invoice_no, function ($q) use ($request) {
                $q->where('invoice_no', 'like', '%' . $request->order_no . '%');
            })->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })->when(auth()->check() and auth()->user()->user_type == 'user', function ($q) {
                $q->where('customer_id', auth()->id());
            })
            ->paginate(10);

        return apiJsonResponse('success', $orders, 'Orders list', Response::HTTP_OK);
    }

    public function placeOrder(Request $request, $id = "")
    {
        try {
            DB::beginTransaction();
            $newOrder = true;
            $order = $this->order;
            if ($id) {
                $order = $this->order->find($id);
                $this->addOrderHistory($order);
                $newOrder = false;
            } else {
                $order->customer_id = auth()->id();
                $order->invoice_no = prefixGenerator($this->order);
                $order->status = $this->order::PENDING;
            }
            $order->total_amount = $request->total_amount;
            $order->instruction = $request->instruction;
            $order->save();
            $this->insertOrderDetails($request->items, $order);
            DB::commit();
            if ($newOrder) {
                $this->sendNotificationToAdmin($order);
            }
            return apiJsonResponse('success', new OrderResource($order), 'Order successfully placed', Response::HTTP_OK);
        } catch (Throwable $ex) {
            DB::rollBack();
            return apiJsonResponse('error', ['message' => $ex->getMessage()], 'Something went wrong', Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    protected function insertOrderDetails($items, $order)
    {
        foreach ($items as $item) {
            $this->orderDetails->create([
                'product_id' => $item['product_id'],
                'deatilsable_type' => get_class($order),
                'deatilsable_id' => $order->id,
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'total_price' => $item['total_price'],
            ]);
        }
    }

    protected function addOrderHistory($order)
    {
        $this->orderEditHistory->create([
            'historyable_type' => get_class($order),
            'historyable_id' => $order->id,
            'order_data' => new OrderResource($order),
        ]);
        $this->orderDetails->where('deatilsable_type', get_class($order))
            ->where('deatilsable_id', $order->id)
            ->delete();
    }

    public function getOrderDetails($id)
    {
        try {
            $order = $this->order->where('id', $id)
                ->when(auth()->user()->user_type == 'user', function ($q) {
                    $q->where('customer_id', auth()->id());
                })->firstOrFail();
            return apiJsonResponse('success', new OrderResource($order), 'Order details', Response::HTTP_OK);
        } catch (Exception $ex) {
            return apiJsonResponse('error', [], 'Something went wrong', Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $order = $this->order->find($id);
        $order->status = $request->status;
        $order->save();
        if ($request->status == $this->order::DELIVERED) {
            $this->updateProductQuantity($order);
        }
        return apiJsonResponse('success', [], 'Order status successfully updated', Response::HTTP_OK);
    }

    protected function updateProductQuantity($order)
    {
        foreach ($order->details as $detail) {
            $this->productService->updateProductStock($detail->product_id, -$detail->quantity);
        }
        return;
    }

    public function moveAllDeliveredOrder()
    {
        $orders = $this->order->where('status', $this->order::DELIVERED)
            ->get();
        foreach ($orders as $order) {
            try {
                DB::beginTransaction();
                //replicate same order table in delivered order table
                $deliveredOrder = (new $this->deliveredOrder)->forceCreate($order->only(
                    'customer_id',
                    'invoice_no',
                    'total_amount',
                    'status',
                    'instruction'
                ));
                //change all order edit history
                $this->orderEditHistory->where([
                    'historyable_type' => get_class($order),
                    'historyable_id' => $order->id
                ])->update([
                    'historyable_type' => get_class($deliveredOrder),
                    'historyable_id' => $deliveredOrder->id
                ]);
                //change all order details 
                $this->orderDetails->where([
                    'deatilsable_type' => get_class($order),
                    'deatilsable_id' => $order->id
                ])->update([
                    'deatilsable_type' => get_class($deliveredOrder),
                    'deatilsable_id' => $deliveredOrder->id
                ]);
                //finally, delete the order
                $order->delete();
                DB::commit();
            } catch (Throwable $ex) {
                DB::rollBack();
            }
        }
    }

    public function sendNotificationToAdmin(Order $order)
    {
        try {
            $message = "Order received {$order->invoice_no} total amount of {$order->total_amount}";
            event(new OrderPlacedEvent($message));
        } catch (Throwable $ex) {
            Log::debug("place order notification issue: " . $ex->getMessage());
        }
    }
}
