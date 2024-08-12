<?php

namespace App\Http\Controllers\product;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
 
    public function index()
    {
        $products = Product::get();
        return response()->json([
        "status" => "success",
        "data" => $products
        ]);
    }


    public function store(Request $request)
    {
        //
    }


    public function show(string $id)
    {
        $product =  Product::find($id);

        if (!$product) {
            return response()->json([
                "status" => "failed",
                "message" => "No product was found with ID ". $id,
            ]);
        }

        return response()->json([
            "status" => "success",
            "data" => $product,
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

    public function search($keyword){

        $products = Product::where("name", "LIKE", "%".$keyword."%")->orWhere("description", "LIKE", "%".$keyword."%")->orWhere("brand", "LIKE", "%".$keyword."%")->get();

        return response()->json([
            "status" => "success",
            "data" => $products
        ]);

    }
}
