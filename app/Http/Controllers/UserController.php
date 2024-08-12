<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        return response()->json([
            "status" => "failed",
            "data" => $user
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    public function getAllUsers()
    {

        $users = User::where("role", 0)->get();

        return response()->json([
            "status" => "success",
            "data" => $users
        ]);
    }


    public function show(string $id)
    {
        $user = User::where("role", 0)->find($id);

        if (!$user) {
            return response()->json([
                "status" => "success",
                "message" => "No user was found with ID " . $id
            ]);
        }

        return response()->json([
            "status" => "success",
            "data" => $user
        ]);
    }


    public function update(Request $request)
    {
        $user = $request->user();

        $updated = null;

        if ($request->has("password")  && $request->has("current_password")) {
            $request->validate([
                "name" => "required",
                "email" => $user->email === $request->email ? "required" : "required|unique:users,email",
                "current_password" => "required",
                "password" => "required|confirmed"
            ]);

            $password_match = Hash::check($request->current_password, $user->password);

            if (!$password_match) {
                return response()->json([
                    "status" => "failed",
                    "message" => "Current Password is incorrect.",
                ]);
            }

            $updated = $user->update([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password),
            ]);

        } else {
            $request->validate([
                "name" => "required",
                "email" => $user->email === $request->email ? "required" : "required|unique:users,email",
            ]);


            $updated = $user->update($request->all());
        }


        if (!$updated) {
            return response()->json([
                "status" => "failed",
                "message" => "User info was not successfully updated.",
            ]);
        }

        return response()->json([
            "status" => "success",
            "message" => "User info has been successfully updated.",
        ]);
    }


    public function destroy(string $id)
    {
        $user = User::where("role", 0)->find($id);

        if (!$user) {
            return response()->json([
                "status" => "failed",
                "message" => "No user was found with ID " . $id
            ]);
        }


        $deleted =  $user->delete();

        if (!$deleted) {
            return response()->json([
                "status" => "failed",
                "message" => "User was not successfully deleted.",
            ]);
        }

        return response()->json([
            "status" => "success",
            "message" => "User has been successfully deleted.",
        ]);
    }
}
