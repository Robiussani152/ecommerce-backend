<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ProductService
{
    public $product;
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function getAllProducts($search_query = "", $order_column = "name", $order_by = "asc")
    {
        $products = $this->product
            ->when($search_query, function ($q) use ($search_query) {
                $q->where('name', 'like', '%' . $search_query . '%');
            })->when($order_column and $order_by, function ($q) use ($order_column, $order_by) {
                $q->orderBy($order_column, $order_by);
            })
            ->get();
        return apiJsonResponse('success', ProductResource::collection($products), __('custom.list', ['key' => 'product']), Response::HTTP_OK);
    }

    public function createNewProduct()
    {
    }

    public function updateProduct()
    {
    }

    public function deleteProduct()
    {
    }

    public function updateProductStock()
    {
    }
}
