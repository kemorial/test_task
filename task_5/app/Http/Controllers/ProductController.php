<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products =  Product::paginate(50);
        return ProductResource::collection($products);
    }

    public function store(ProductRequest $request): JsonResponse
    {
        $data = $request->validated();

        $product = Product::create($data);

        return response(ProductResource::make($product), 201);
    }

    public function update(ProductRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();

        $product = Product::findOrFail($id);
        $product->update($data);

        return ProductResource::make($product);
    }

    public function destroy(int $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['deleted' => true]);
    }
}
