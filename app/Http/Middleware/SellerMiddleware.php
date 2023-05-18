<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
// panggil libary jwt
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class SellerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $jwt =$request->bearerToken(); //ambil token
    
            $decoded =JWT::decode($jwt,new Key(env('JWT_SECRET_KEY'),'HS256'));
    
            // kondisi jika role pada token adalah seller,maka lanjut proses selanjutnya
            if($decoded->role =='seller'){
                // ngambil dari token bearer yg di dalam nya ada email 
                $data = ['email'=>$decoded->email];
                $request->merge($data);
                return $next($request);
            }else{
                //jika bukan role seller
                return response()->json("unauthorized",401);
            }

        }
            catch (ExpiredException $e){
                return response()->json($e->getMessage(),400);
            }
    }
}
