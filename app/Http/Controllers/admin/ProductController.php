<?php

namespace App\Http\Controllers\admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResources;

class ProductController extends Controller
{

    public function index()
    {
        $product = Product::all();
        // return ProductResources::collection($product);

        return response()->json([
            "status" => "success",
            "data" => $product
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|max:50|unique:products,name",
            "price" => "required",
            "brand" => "required|max:50",
            "category" => "required|max:50",
            "image" => "required|mimes:jpeg,jpg,png,jfif,avif,webp",
            "description" => "required",
        ]);

        $image_name = time() . '-' . $request->name . '.' . $request->file("image")->guessExtension();

        $upload = $request->file("image")->move("products", $image_name);



        if (!$upload) {
            return response()->json([
                "status" => "failed",
                "message" => "Something went wrong, please try again."
            ]);
        }


        $product = Product::create([
            ...$request->except("image"),
            "image" => $image_name
        ]);


        if (!$product) {
            return response()->json([
                "status" => "failed",
                "message" => "Product was not successfully created."
            ]);
        }


        return response()->json([
            "status" => "success",
            "message" => "Product has been successfully created.",
        ]);
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
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                "status" => "failed",
                "message" => "Product with id " . $id . " was not found",
            ]);
        }

        if ($request->has("image")) {
            $request->validate([
                "name" => $product->name !== $request->name ? "required|max:50|unique:products,name" : "required|max:50",
                "price" => "required",
                "brand" => "required|max:50",
                "category" => "required|max:50",
                "image" => "required|mimes:jpeg,jpg,png,jfif,avif,webp",
                "description" => "required",
            ]);

            $image_name = time() . '-' . $request->name . '.' . $request->file("image")->guessExtension();

            $upload = $request->file("image")->move("products", $image_name);
    
    
    
            if (!$upload) {
                return response()->json([
                    "status" => "failed",
                    "message" => "Something went wrong, please try again."
                ]);
            }
    
    
            $update = $product->update([
                ...$request->except("image"),
                "image" => $image_name
            ]);
    
    
            if (!$update) {
                return response()->json([
                    "status" => "failed",
                    "message" => "Product was not successfully updated."
                ]);
            }
    
    
            return response()->json([
                "status" => "success",
                "message" => "Product has been successfully updated.",
            ]);


        } else {

            $request->validate([
                "name" => $product->name !== $request->name ? "required|max:50|unique:products,name" : "required|max:50",
                "price" => "required",
                "brand" => "required|max:50",
                "category" => "required|max:50",
                "description" => "required",
            ]);


            $update = $product->update($request->all());
    
    
            if (!$update) {
                return response()->json([
                    "status" => "failed",
                    "message" => "Product was not successfully updated."
                ]);
            }
    
    
            return response()->json([
                "status" => "success",
                "message" => "Product has been successfully updated.",
            ]);

        }
    }


    public function destroy(string $id)
    {

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                "status" => "failed",
                "message" => "Product with id " . $id . " was not found",
            ]);
        }

        $delete = $product->delete();

        if(!$delete){
            return response()->json([
                "status" => "failed",
                "message" => "Product was not successfully deleted.",
            ]);
        }

        return response()->json([
            "status" => "success",
            "message" => "Product has been successfully deleted.",
        ]);
    }

    public function search($keyword){

        $products = Product::where("name", "LIKE", "%".$keyword."%")->orWhere("description", "LIKE", "%".$keyword."%")->orWhere("brand", "LIKE", "%".$keyword."%")->get();

        return response()->json([
            "status" => "success",
            "data" => $products
        ]);

    }
}
