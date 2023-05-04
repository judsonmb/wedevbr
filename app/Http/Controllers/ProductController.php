<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Models\Product;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    public function create(CreateProductRequest $request)
    {
        (new ProductService)->createProduct($request->all());
        return response()->json(['message' => 'created successfully!'], 200);
    }

    public function read(Request $request, int $id)
    {
        $product = (new ProductService)->readProduct($id);
        return response()->json(['data' => $product], 200);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        (new ProductService)->updateProduct($request->all(), $product);
        return response()->json(['message' => 'updated successfully!'], 200);
    }

    public function delete(Request $request, Product $product)
    {
        (new ProductService)->deleteProduct($product);
        return response()->json(['message' => 'deleted successfully!'], 200);
    }
}
