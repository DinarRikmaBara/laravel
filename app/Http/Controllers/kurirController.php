<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Keranjang;
use App\Models\Produk;
use App\Models\kurir;
use App\Models\Pesanan;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class kurirController extends Controller
{
    public function ambilPesanan(Request $request,$id)
    {
        $email=$request->input('email');
        $data = Kurir::where('emailKurir',$email)->get();
        foreach ($data as $datas) {
                Pesanan::where('idPesanan',$id)->update([
                    'emailKurir' => $email,
                    'namaKurir' => $datas->namaKurir,
                    'phoneKurir' => $datas->phoneKurir,
                    'status' => 'perjalanan',
                ]);
                Log::create([
                    'module'=>'Pesanan',
                    'action'=>'add produk id '.$id.' to keranjang',
                    'useraccess'=>$email
                ]);
                return response()->json(["Berhasil"],200);
        }
    }
    public function pesanan()
    {
        $pesananItems = DB::table('pesanan')
                        ->join('keranjang', 'pesanan.idKeranjang', '=', 'keranjang.idKeranjang')
                        ->join('produk', 'keranjang.idProduk', '=', 'produk.idProduk')
                        ->join('seller', 'produk.validateEmail', '=', 'seller.validateEmail')
                        ->where('pesanan.pesananStatus', 'proses')
                        ->get();
        $data=[];
        foreach ($pesananItems as $key) {
            array_push($data,[
                'gambar'=>url($key->gambar),
                'namaToko'=>$key->namaToko,
                'alamatToko'=>$key->alamatToko,
                'namaProduk'=>$key->namaProduk,
                'harga'=>$key->harga,
                'jumlahBarang'=>$key->jumlahBarang,
                'totalHarga'=>$key->totalHarga,
                'namaUser'=>$key->namaUser,
                'phoneUser'=>$key->phoneUser,
                'alamatUser'=>$key->alamatUser,
                'emailKurir'=>$key->emailKurir,
                'namaKurir'=>$key->namaKurir,
                'phoneKurir'=>$key->phoneKurir,
            ]);
            
        }
        return response()->json(['data'=>$data]);
        
    }
    public function ambil()
    {
        $pesananItems = DB::table('pesanan')
                        ->join('keranjang', 'pesanan.idKeranjang', '=', 'keranjang.idKeranjang')
                        ->join('produk', 'keranjang.idProduk', '=', 'produk.idProduk')
                        ->join('seller', 'produk.validateEmail', '=', 'seller.validateEmail')
                        ->where('pesanan.pesananStatus', 'proses')
                        ->get();
        $data=[];
        foreach ($pesananItems as $key) {
            array_push($data,[
                'gambar'=>url($key->gambar),
                'namaToko'=>$key->namaToko,
                'alamatToko'=>$key->alamatToko,
                'namaProduk'=>$key->namaProduk,
                'harga'=>$key->harga,
                'jumlahBarang'=>$key->jumlahBarang,
                'totalHarga'=>$key->totalHarga,
                'namaUser'=>$key->namaUser,
                'phoneUser'=>$key->phoneUser,
                'alamatUser'=>$key->alamatUser,
                'emailKurir'=>$key->emailKurir,
                'namaKurir'=>$key->namaKurir,
                'phoneKurir'=>$key->phoneKurir,
            ]);
            
        }
        return response()->json(['data'=>$data]);
    }


}
