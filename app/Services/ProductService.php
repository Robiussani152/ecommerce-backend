<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\ProductResource;
use App\Models\ProductQuantityUpdateHistory;
use Symfony\Component\HttpFoundation\Response;

class ProductService
{
    public $product, $fileUploadService, $productQuantityUpdateHistory;
    public function __construct(Product $product, FileUploadService $fileUploadService, ProductQuantityUpdateHistory $productQuantityUpdateHistory)
    {
        $this->product = $product;
        $this->fileUploadService = $fileUploadService;
        $this->productQuantityUpdateHistory = $productQuantityUpdateHistory;
    }

    public function getAllProducts($search_query = "", $order_column = "name", $order_by = "asc", $limit = 10)
    {
        $products = $this->product
            ->when($search_query, function ($q) use ($search_query) {
                $q->where('name', 'like', '%' . $search_query . '%');
            })->when($order_column and $order_by, function ($q) use ($order_column, $order_by) {
                $q->orderBy($order_column, $order_by);
            })
            ->simplePaginate($limit);
        return ProductResource::collection($products);
    }

    public function addOrUpdateProduct(Request $request, $id = "")
    {
        $product = $this->product;
        $deleteImage = null;
        if ($id) {
            $product = $this->product->find($id);
            $deleteImage = $product->image;
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->quantity = $request->quantity;

        if ($request->hasFile('image'))
            $product->image = $this->fileUploadService->uploadFile($request, 'image', 'images', $deleteImage);
        $product->save();
        return apiJsonResponse('success', ['data' => new ProductResource($product)], __('custom.creation_success'), Response::HTTP_OK);
    }

    public function deleteProduct($id)
    {
        $this->product->delete($id);
        return apiJsonResponse('success', [], __('custom.delete_success'), Response::HTTP_OK);
    }

    public function updateProductStock(Request $request)
    {
        $product = $this->product->find($request->product_id);
        $this->productStockUpdateHistory($request->product_id, $product->quantity, $request->quantity);
        $product->quantity = $request->quantity + $product->quantity;
        $product->save();
        return apiJsonResponse('success', [], __('custom.creation_success'), Response::HTTP_OK);
    }

    protected function productStockUpdateHistory($productId, $oldQuantity, $inputQuantity)
    {
        $this->productQuantityUpdateHistory->create([
            'product_id' => $productId,
            'user_id' => auth()->id(),
            'old_quantity' => $oldQuantity,
            'new_quantity' => $oldQuantity + $inputQuantity,
            'input_quantity' => $inputQuantity,
        ]);
    }
}
