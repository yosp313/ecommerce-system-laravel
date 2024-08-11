<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            "name" => "required|string",
            "description" => "nullable|string",
            "parent_id" => "nullable|exists:categories,id"
        ]);

        if($request->has("parent_id")){
            $parentCategory = Category::find($request->parent_id);
            if($parentCategory->parent_id){
                return response()->json(["error" => "Category can't have more than one parent"], 400);
            }
        }

        $category = Category::create($validatedData);

        return response()->json(["data" => $category], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
