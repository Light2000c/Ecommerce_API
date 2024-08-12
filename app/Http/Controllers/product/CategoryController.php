<?php

namespace App\Http\Controllers\product;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::get();

        return response()->json([
            "status" => "success",
            "data" => $categories
        ]);
    }


    public function store(Request $request)
    {
        //
    }


    public function show(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                "status" => "failed",
                "message" => "No category was found with ID " . $id
            ]);
        }


        return response()->json([
            "status" => "success",
            "data" => $category
        ]);
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
