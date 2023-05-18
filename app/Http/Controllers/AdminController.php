<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Produk;
use App\Models\Log;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showUser()
    {
        $user=User::get();
        return response()->json($user);
    }



    public function register(Request $request)
    {
        $validator = validator::make($request->all(),[
            'username' => 'required',
            'email' => 'required',
            'password' => 'required|min:8',
        ]);
        $user = $validator->validated();
        User::create([
            'username'=>$user['username'],
            'email'=>$user['email'],
            'password'=>$user['password'],
            'role'=>'seller',
        ]);
        // kirim response ke pengguna
        return response()->json(["Berhasil"],200);

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function userdelete($id)
    {
        User::where('idUsers',$id)->delete();
        Log::create([
            'module'=>'hapus user',
            'action'=>'id '.$id,
            'useraccess'=>'admin'
        ]);

        return response()->json([
            "data"=>[
                'Berhasil di hapus produk id '=>$id
            ],
           
        ],200);
    }
}
