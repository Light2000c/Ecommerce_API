<?php

namespace App\Http\Controllers\product;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = auth()->user()->order()->get();

        return response()->json([
            "status" => "success",
            "data" => $orders
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $random_number = $this->generateReference();


        $cart = auth()->user()->cart()->get();

        if ($cart->isEmpty()) {
            return response()->json([
                "status" => "failed",
                "message" => "User has not added any product to cart yet"
            ]);
        }


        $transaction = $request->user()->transaction()->create([
            "reference" => $random_number,
        ]);


        if (!$transaction) {
            return response()->json([
                "status" => "failed",
                "message" => "Something went wrong, please try again."
            ]);
        }

        $transactionId = $transaction->id;

        try {
            DB::transaction(function () use ($transactionId) {
                DB::table('carts')->orderBy('id')->chunk(1000, function ($carts) use ($transactionId) {
                    $orders = $carts->map(function ($item) use ($transactionId) {
                        $productPrice = DB::table('products')
                            ->where('id', $item->product_id)
                            ->value('price');

                        $total = $productPrice * $item->quantity;

                        return [
                            'user_id' => $item->user_id,
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity,
                            'transaction_id' => $transactionId,
                            'total' => $total,
                            // 'created_at' => now(),
                            // 'updated_at' => now(),
                        ];
                    });

                    DB::table('orders')->insert($orders->toArray());

                    // Optionally, delete the processed cart items
                    DB::table('carts')->whereIn('id', $carts->pluck('id'))->delete();
                });
            });


            return response()->json([
                "status" => "success",
                "message" => "Order has been successfully created."
            ]);
        } catch (\Exception $e) {
            // Handle exceptions and rollback
            return response()->json([
                "status" => "failed",
                "message" => "Something went wrong, please try again.",
            ]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with("product")->find($id);

        if(!$order){
            return response()->json([
                "status" => "failed",
                "message" => "No order was found with ID " . $id
            ]);
        }

        return response()->json([
            "status" => "success",
            "data" => $order
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
        //
    }

    public function generateReference()
    {
        $random_number = "TN" . random_int(100000, 999999);
        return $random_number;
    }
}
