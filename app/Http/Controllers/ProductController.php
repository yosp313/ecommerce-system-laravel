<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function store(Request $request): JsonResponse{
        $validatedData = $request->validate([
            "name" => "required|string",
            "description" => "required|string",
            "price" => "required|numeric",
            "category_id" => "required|exists:categories,id",
            "stock" => "required|integer",
            "image_url" => "nullable|string"
        ]);

        $product = Product::create($validatedData);

        return response()->json(["product" => $product]);
    }
}
