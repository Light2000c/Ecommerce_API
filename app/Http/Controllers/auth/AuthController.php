<?php

namespace App\Http\Controllers\auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {



        $credentials = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
        ]);


        $user = User::create($credentials);

        if(!$user){
            return response([
                "status" => "failed",
                "message" => "Account was not successfully created"
            ], 500);
        }

        $token = $user->createToken("auth-token")->plainTextToken;


        return response([
            "status" => "success",
            "token" => $token,
            "data" => $user
        ], 200);

    }



    public function login(Request $request)
    {
        
        $credentials = $request->validate([
            "email" => "required",
            "password" => "required"
        ]);

        $user = User::where("email", $credentials["email"])->first();

        if(!$user){
            return response([
                "status" => "failed",
                "message" => "No account is associated with the email address ". $credentials["email"]
            ]);
        }

        $passwordCheck = Hash::check($credentials["password"], $user->password);

        if(!$passwordCheck){
            return response([
                "status" => "failed",
                "message" => "Password is incorrect."
            ]);
        }

        $token = $user->createToken("auth-token")->plainTextToken;

        return response([
            "status" => "success",
            "token" => $token,
            "data" => $user
        ], 200);

    }


    public function logout(Request $request)
    {

        $user = $request->user();

        $user->tokens()->delete();

       return response()->json([
        "status" => "success",
        "message" => "logged out"
       ]);
    }
}
