<?php

namespace App\Http\Controllers\product;

use App\Models\Product;
use App\Models\Favourite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FavouriteController extends Controller
{

    public function index(Request $request)
    {
        // Don't forget to try 
        // $user = Favourite::whereBelongsTo(auth()->user());

        $favourites = auth()->user()->favourite()->get();


        return response()->json([
            "status" => "success",
            "data" => $favourites
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "product_id" => "required"
        ]);

        $product = Product::find($request->product_id);
        $user = $request->user();

        if (!$product) {
            return response()->json([
                "status" => "failed",
                "message" => "No product was found with ID " . $request->product_id,
            ]);
        }

        if($product->hasFavourite($user)){
            return response()->json([
                "status" => "failed",
                "message" => "Product already exist in user favourite",
            ]);
        }

        $favourite = $request->user()->favourite()->create($request->all());

        if (!$favourite) {
            return response()->json([
                "status" => "success",
                "message" => "product was not successfully added to favourite"
            ]);
        }

        return response()->json([
            "status" => "success",
            "message" => "product was successfully added to favourite"
        ]);
    }


    public function show(string $id)
    {

        $favourite = Favourite::find($id);

        if (!$favourite) {
            return response()->json([
                "status" => "failed",
                "message" => "No favourites was found with ID " . $id
            ]);
        }


        return response()->json([
            "status" => "success",
            "data" => $favourite
        ]);
    }

    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(Request $request,string $id)
    {
        $favourite = Favourite::find($id);
        $user = $request->user();

        if (!$favourite) {
            return response()->json([
                "status" => "failed",
                "message" => "No favourites was found with ID " . $id
            ]);
        }

        if($user && !$favourite->user()->is($user)){
            return response()->json([
                "status" => "failed",
                "message" => "Favourite does not belong to the user making the request"
            ]);
        }

        $delete = $favourite->delete($id);


        if ($delete) {
            return response()->json([
                "status" => "success",
                "message" => "Product has been successfully removed from favourite"
            ]);
        }
    }
}
