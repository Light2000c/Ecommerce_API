<?php

namespace App\Http\Controllers\admin;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carts = Cart::get();

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cart = Cart::find($id);

        if (!$cart) {
            return response()->json([
                "status" => "failed",
                "message" => "No cart was found with ID " . $id
            ]);
        }

        $delete = $cart->delete();

        if(!$delete){
            return response()->json([
                "status" => "failed",
                "message" => "Cart was not successfully deleted"
            ]);
        }

        return response()->json([
            "status" => "success",
            "message" => "Cart has been successfully deleted"
        ]);
    }
}
