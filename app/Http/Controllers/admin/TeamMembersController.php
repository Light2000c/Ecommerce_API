<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeamMembersController extends Controller
{

    public function index()
    {
       
        $users = User::where("role", "1")->get();

        return response()->json([
            "status" => "success",
            "data" => $users
        ]);
    }

    
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "email" => "required|unique:users,email",
            "password" => "required|confirmed"
        ]);

        $user = User::create([
            ...$request->all(),
            "role" => 1
        ]);

        if(!$user){
            return response()->json([
                "status" => "failed",
                "message" => "Account was not successfully created."
            ]);
        }

        return response()->json([
            "status" => "success",
            "message" => "Account has successfully created."
        ]);
    }


    public function show(string $id)
    {
        $user = User::where("role", 1)->find($id);

        if (!$user) {
            return response()->json([
                "status" => "failed",
                "message" => "No member was found with ID " . $id
            ]);
        }

        return response()->json([
            "status" => "success",
            "data" => $user
        ]);
    }


    public function update(Request $request, string $id)
    {
        $user = User::where("role", 1)->find($id);

        if (!$user) {
            return response()->json([
                "status" => "failed",
                "message" => "No member was found with ID " . $id
            ]);
        }

        $updated = null;

        if ($request->has("password")  && $request->has("current_password")) {
            $request->validate([
                "name" => "required",
                "email" => $user->email === $request->email ? "required" : "required|unique:users,email",
                "current_password" => "required",
                "password" => "required|confirmed"
            ]);

            $password_match = Hash::check($request->current_password, $user->password);

            if ($password_match) {
                $updated = $user->update([
                    "name" => $request->name,
                    "email" => $request->email,
                    "password" => Hash::make($request->password),
                ]);
            }
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
        $user = User::where("role", 1)->find($id);

        if (!$user) {
            return response()->json([
                "status" => "failed",
                "message" => "No member was found with ID " . $id
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
