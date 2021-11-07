<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        return $this->productService->getAllProducts($request->q, $request->order_col, $request->order);
    }

    public function store(ProductRequest $request)
    {
        return $this->productService->addOrUpdateProduct($request);
    }

    public function update(ProductRequest $request, $id)
    {
        return $this->productService->addOrUpdateProduct($request, $id);
    }

    public function destroy($id)
    {
        return $this->productService->deleteProduct($id);
    }

    public function updateStock(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'bail|required|exists:products,id',
            'quantity' => 'bail|required|numeric'
        ]);
        return $this->productService->updateProductStock($request);
    }
}
