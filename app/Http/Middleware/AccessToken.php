<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header("x-access-token");
    
        if (!$token) {
            return response()->json([
                "status" => "unauthorized"
            ]);
        }
    
        $access_token = [
            "QSBOZXcgV29ybGQ=",
            "R3JlYXRlIEFuZCBUYWxs",
            "QSBCZXR0ZXIgVGltZQ==",
            "VGhlIEdyZWF0IFdhbGw=",
            "V2UgQXJlIEdyZWF0IFRvZ2V0aGVy"
        ];
    
        if (!in_array($token, $access_token)) {
            return response()->json([
                "status" => "unauthorized"
            ]);
        }
    
        return $next($request);
    }
    
}
