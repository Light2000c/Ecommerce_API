<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

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
        
        $request->validate([
            "name" => "required|unique:categories,name",
        ]);

        $category = Category::create([
            "name" => $request->name,
        ]);

        if(!$category){
            return response()->json([
                "status" => "failed",
                "message" => "Category was not successfully created"
            ]);
        }

        return response()->json([
            "status" => "success",
            "message" => "Category has been successfully created"
        ]);
    }


    public function show(string $id)
    {
        $category = Category::find($id);

        if(!$category){
            return response()->json([
                "status" => "failed",
                "message" => "No category was found with ID " .$id
            ]);
        }


        return response()->json([
            "status" => "success",
            "data" => $category
        ]);
    }


    public function update(Request $request, string $id)
    {

        $category = Category::find($id);

        if(!$category){
            return response()->json([
                "status" => "failed",
                "message" => "No category was found with ID " .$id
            ]);
        }

        $request->validate([
            "name" => $category->name !== $request->name?  "required|unique:categories,name" : "required",
        ]);


        $category->name = $request->name;
        $updated = $category->save();

        if(!$updated){
            return response()->json([
                "status" => "failed",
                "message" => "Category was not successfully updated"
            ]);
        }

        return response()->json([
            "status" => "success",
            "message" => "category has been successfully updated"
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if(!$category){
            return response()->json([
                "status" => "failed",
                "message" => "No category was found with ID " .$id
            ]);
        }

        $deleted = $category->delete();

        if(!$deleted){
            return response()->json([
                "status" => "failed",
                "message" => "Category was not successfully deleted"
            ]);
        }

        return response()->json([
            "status" => "success",
            "message" => "category has been successfully deleted"
        ]);
    }
}
