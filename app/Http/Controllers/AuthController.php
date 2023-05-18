<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;
use Firebase\JWT\JWT;
use App\Models\User;
use App\Models\seller;
use App\Models\Kurir;
use App\Models\Keranjang;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\Log;
use Illuminate\Auth\Events\Validated;

use function PHPUnit\Framework\directoryExists;

class AuthController extends Controller
{
     public function register(Request $request)
    {
        $validator = validator::make($request->all(),[
            'username' => 'required',
            'email' => 'required',
            'password' => 'required|min:8',
        ]);
        $user = $validator->validated();
        User::create($user);

        $payload =[
            'username'=> $user ['username'],
            'email'=> $user ['email'],
            'role'=>'user',
            'iat'=> now()->timestamp, // waktu  di buat
            'exp'=>now()->timestamp + 7200 // waktu toen kadaluarsa (2 jam setelah token dibuat)
        ];
        //generate token dengan algoritma Hs256
        $token = JWT::encode($payload,env('JWT_SECRET_KEY'),'HS256');
    
        //buat login
        log::create([
            'module'=>'Users',
            'action'=>'resgiter user',
            'useraccess'=>$user['username']
        ]);
        // kirim response ke pengguna
        return response()->json(["token"=> "Bearer {$token}"],200);

    }

    public function rSeller(Request $request)
    {
        $validator = validator::make($request->all(),[
            'username' => 'required',
            'email' => 'required',
            'password' => 'required|min:8',
            'namaToko' => 'required',
            'alamatToko' => 'required',
            'gambarSeller' => 'required',
            'phoneSeller' => 'required',

        ]);

        if ($validator->fails()){
            return messageError($validator->messages()->toArray());
        }

        $thumbail = $request->file('gambar');
        $filename = now()->timestamp."_".$request->gambar->getClientOriginalName();
        $thumbail->move('uploads',$filename);

        $user = $validator->validated();

        User::create([
            'username'=>$user['username'],
            'email' => $user['email'],
            'password'=>$user['password'],
            'role'=>'seller',
        ]);
        Seller::create([
            'namaToko'=>$user['namaToko'],
            'alamatToko' => $user['alamatToko'],
            'gambarSeller'=>'uploads/'.$filename,
            'phoneSeller'=>$user['phoneSeller'],
            'valiadateEmail'=>$user['email'],
        ]);

        $payload =[
            'username'=> $user ['username'],
            'email'=> $user ['email'],
            'role'=>'seller',
            'iat'=> now()->timestamp, // waktu  di buat
            'exp'=>now()->timestamp + 7200 // waktu toen kadaluarsa (2 jam setelah token dibuat)
        ];
        //generate token dengan algoritma Hs256
        $token = JWT::encode($payload,env('JWT_SECRET_KEY'),'HS256');
    
        //buat login
        log::create([
            'module'=>'Users',
            'action'=>'resgiter Seller',
            'useraccess'=>$user['email']
        ]);
        log::create([
            'module'=>'Seller',
            'action'=>'resgiter Seller',
            'useraccess'=>$user['email']
        ]);
        // kirim response ke pengguna
        return response()->json(["token"=> "Bearer {$token}"],200);

    }

    public function rKurir(Request $request)
    {
        $validator = validator::make($request->all(),[
            'username' => 'required',
            'email' => 'required',
            'password' => 'required|min:8',
            'namaKurir' => 'required',
            'phoneKurir' => 'required',
        ]);
        $user = $validator->validated();
        User::create([
            'username'=>$user['username'],
            'email' => $user['email'],
            'password'=>$user['password'],
            'role'=>'kurir',
        ]);
        Kurir::create([
            'namaKurir'=>$user['namaKurir'],
            'phoneKurir' => $user['phoneKurir'],
            'emailKurir' => $user['email'],
        ]);

        $payload =[
            'username'=> $user ['username'],
            'email'=> $user ['email'],
            'role'=>'kurir',
            'iat'=> now()->timestamp, // waktu  di buat
            'exp'=>now()->timestamp + 7200 // waktu toen kadaluarsa (2 jam setelah token dibuat)
        ];
        //generate token dengan algoritma Hs256
        $token = JWT::encode($payload,env('JWT_SECRET_KEY'),'HS256');
    
        //buat login
        log::create([
            'module'=>'Users',
            'action'=>'resgiter Seller',
            'useraccess'=>$user['email']
        ]);
        log::create([
            'module'=>'Kurir',
            'action'=>'resgiter Kurir',
            'useraccess'=>$user['email']
        ]);
        // kirim response ke pengguna
        return response()->json(["token"=> "Bearer {$token}"],200);

    }



    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required'
        ]);
        if ((Auth::attempt($validator->validated()))) {
            $payload =[
                'username'=> Auth::user()->username,
                'email'=> Auth::user()->email,
                'role'=>Auth::user()->role,
                'iat'=> now()->timestamp, // waktu  di buat
                'exp'=>now()->timestamp + 7200 // waktu toen kadaluarsa (2 jam setelah token dibuat)
            ];
            $token = JWT::encode($payload,env('JWT_SECRET_KEY'),'HS256');
            Log::create([
                'module'=>'login',
                'action'=>'login_akun',
                'useraccess'=>Auth::user()->email
            ]);
            return response()->json(["token"=> "Bearer {$token}"],200);

        }
        return response()->json("email atau password salah",422);
    }

}
