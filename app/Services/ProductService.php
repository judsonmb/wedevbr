<?php 

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function createProduct(array $data)
    {
        $newProduct = new Product();
        $newProduct->name = $data['name'];
        $newProduct->price = $data['price'];
        $newProduct->status = $data['status'];
        $newProduct->merchant_id = $data['merchant_id'];
        $newProduct->save();
    }

    public function readProduct(int $id)
    {
        return Product::where('id', $id)
                ->with('merchant')
                ->with('order_items')
                ->get();
    }

    public function updateProduct(array $data, Product $product)
    {
        $product->name = $data['name'] ?? $product->name;
        $product->price = $data['price'] ?? $product->price;
        $product->status = $data['status'] ?? $product->status;
        $product->merchant_id = $data['merchant_id'] ?? $product->merchant_id;
        $product->save();
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
    }
}