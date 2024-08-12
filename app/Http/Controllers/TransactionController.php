<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = auth()->user()->transaction()->with("order")->get();

        return response()->json([
            "status" => "success",
            "data" => $transactions
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
        $transaction = Transaction::with("order")->find($id);

        if(!$transaction){
            return response()->json([
                "status" => "failed",
                "message" => "No transaction was found with ID " . $id
            ]);
        }

       return response()->json([
            "status" => "success",
            "data" => $transaction
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
}
