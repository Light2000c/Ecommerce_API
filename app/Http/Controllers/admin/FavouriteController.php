<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Favourite;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $favourites = Favourite::get();

        return response()->json([
            "status" => "success",
            "data" => $favourites
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
