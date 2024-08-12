<?php

namespace App\Http\Controllers\product;

use Exception;
use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $carts = auth()->user()->cart()->get();

        return response()->json([
            "status" => "success",
            "data" => $carts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            "product_id" => "required"
        ]);

        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json([
                "status" => "failed",
                "message" => "No product was found with ID " . $request->product_id
            ]);
        }

        if ($product->hasCart($request->user())) {
            return response()->json([
                "status" => "failed",
                "message" => "Product already exist in user cart",
            ]);
        }

        $cart =  $request->user()->cart()->create($request->all());


        if (!$cart) {
            return response()->json([
                "status" => "failed",
                "message" => "Product was not successfully added to cart",
            ]);
        }

        return response()->json([
            "status" => "success",
            "message" => "Product was  successfully added to cart",
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cart = Cart::find($id);

        if (!$cart) {
            return response()->json([
                "status" => "failed",
                "message" => "No cart was found with ID " . $id
            ]);
        }

        return response()->json([
            "status" => "success",
            "data" => $cart
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "quantity" => "required|numeric|min:1",
        ]);

        $cart = Cart::find($id);
        $user = $request->user();

        if (!$cart) {
            return response()->json([
                "status" => "failed",
                "message" => "No cart was found with ID " . $id
            ]);
        }


        if ($cart && !$cart->user->is($user)) {
            return response()->json([
                "status" => "failed",
                "message" => "cart doesn't belong to the user making the request"
            ]);
        }

        $update = $cart->update([
            "quanity" => $request->quantity,
        ]);

        if (!$update) {
            return response()->json([
                "status" => "failed",
                "message" => "cart was not successfully updated"
            ]);
        }

        return response()->json([
            "status" => "success",
            "message" => "cart was successfully updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {

        $cart = Cart::find($id);
        $user = $request->user();

        if (!$cart) {
            return response()->json([
                "status" => "failed",
                "message" => "No cart was found with ID " . $id
            ]);
        }


        if ($cart && !$cart->user->is($user)) {
            return response()->json([
                "status" => "failed",
                "message" => "cart doesn't belong to the user making the request"
            ]);
        }

        $delete = $cart->delete();

        if ($delete) {
            return response()->json([
                "status" => "success",
                "message" => "cart has been successfully delete"
            ]);
        }
    }
}
