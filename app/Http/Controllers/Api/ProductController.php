<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{

    public $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        return $this->productService->getAllProducts($request->query_string, $request->order_col, $request->order);
    }

    public function store(ProductRequest $request)
    {
        return $this->productService->addOrUpdateProduct($request);
    }

    public function update(ProductRequest $request, $id)
    {
        return $this->productService->addOrUpdateProduct($request, $id);
    }

    public function show($id)
    {
        return $this->productService->getSpecificProduct($id);
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
        $this->productService->updateProductStock($request->product_id, $request->quantity);
        return apiJsonResponse('success', [], __('custom.creation_success'), Response::HTTP_OK);
    }
}
