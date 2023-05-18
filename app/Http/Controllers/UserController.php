<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Keranjang;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\Transaksi;
use App\Models\Seller;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function pesanan($id)
    {
        $pesananItems = DB::table('pesanan')
                        ->join('keranjang', 'pesanan.idKeranjang', '=', 'keranjang.idKeranjang')
                        ->join('produk', 'keranjang.idProduk', '=', 'produk.idProduk')
                        ->join('seller', 'produk.validateEmail', '=', 'seller.validateEmail')
                        ->where('pesanan.createBy', $id)
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
                'status'=>$key->pesananStatus,
            ]);
            
        }
        return response()->json(['data'=>$data]);
        
    }

    public function total($id)
    {
        $data=[];
        $result = DB::table('keranjang')
        ->join('produk', 'keranjang.idProduk', '=', 'produk.idProduk')
        ->where('keranjang.status', 'keranjang')
        ->where('keranjang.createBy', 'LIKE','%'.$id.'%')
        ->select('produk.namaProduk', 'produk.harga', 'keranjang.jumlahBarang', DB::raw('SUM(keranjang.totalHarga) AS subtotal'))
        ->groupBy('produk.namaProduk', 'produk.harga', 'keranjang.jumlahBarang')
        ->get();
        foreach ($result as $key) {
            array_push($data,[
                'namaProduk'=>$key->namaProduk,
                'harga'=>$key->harga,
                'jumlahBarang'=>$key->jumlahBarang,
                'subtotal'=>$key->subtotal

            ]);
        }
        return response()->json(['data'=>$data]);
        
    }
    
     public function showkeranjang($id)
    {
        $data = DB::table('keranjang')
        ->join('produk', 'keranjang.idProduk', '=', 'produk.idProduk')
        ->where('createBy', 'LIKE','%'.$id.'%')
        ->where('status', 'keranjang')
        ->get();
        $keranjang = [];
        foreach ($data as $key) {
            array_push($keranjang,[
                'idKeranjang'=>$key->idKeranjang,
                'namaProduk'=>$key->namaProduk,
                'jumlahBarang'=>$key->idKeranjang,
                'gambar'=>url($key->gambar)
            ]);
        }
        return response()->json(['data'=>$keranjang]);
    }

    public function toko()
    {
        $data = seller::select('*')
                ->where('status', '=', 'buka')
                ->get();
        $produk = [];
                foreach ($data as $datas) {
                    array_push($produk,[
                        'idseller'=>$datas->idSeller,
                        'gambarSeller'=>url($datas->gambarSeller),
                        'namaToko'=>$datas->namaToko,
                        'phoneSeller'=>$datas->phoneSeller,
                        'alamatToko'=>$datas->alamatToko,

                    ]);
                }
                return response()->json(['data'=>$produk]);

    }


    public function keranjang(Request $request,$id)
    {
        $validator = validator::make($request->all(),[
            'jumlahBarang' => 'required'
        ]);

        $email=$request->input('email');
        $keranjang = $validator->validated();
        $data = Produk::where('idProduk',$id)->get();
        foreach ($data as $datas) {
                Keranjang::create([
                    'idProduk' => $id,
                    'jumlahBarang' => $keranjang['jumlahBarang'],
                    'totalharga' => $datas->harga * $keranjang['jumlahBarang'],
                    'status' => 'keranjang',
                    'createBy' => $email
                ]);
                Log::create([
                    'module'=>'Keranjang',
                    'action'=>'add produk id '.$id.' to keranjang',
                    'useraccess'=>$email
                ]);
                return response()->json(["Berhasil"],200);
        }
    }

    public function checkout(Request $request)
    {
        $validator = validator::make($request->all(),[
            'namaUser' => 'required',
            'alamat' => 'required',
            'noHp' => 'required'
        ]);
        $cek = $validator->validated();

        $data=$request->input('email');
        $cari = Keranjang::where('createBy','LIKE','%'.$data.'%')->where('status','keranjang')->get();
        foreach ($cari as $datas) {
                    Pesanan::create([
                        'createBy' => $data,
                        'idKeranjang' => $datas->idKeranjang,
                        'namaUser' => $cek['namaUser'],
                        'alamatUser' => $cek['alamat'],
                        'phoneUser' => $cek['noHp'],
                        'status' => 'proses',
                    ]);
                    Log::create([
                        'module'=>'Pesanan',
                        'action'=>'checkOut keranjang id '.$datas->idKeranjang,
                        'useraccess'=>$data
                    ]);
                    $update = Keranjang::where('idKeranjang','LIKE','%'.$datas->idKeranjang.'%')->where('status','keranjang')->update(['status'=>'proses']);
        }
        return response()->json('berhasil',200);
    }

    public function selesai($id)
    {
        $keranjang = Pesanan::where('idPesanan',$id);
        $keranjang->update([
            'pesananStatus'=>'selesai'
        ]);
        $pesanan = DB::table('pesanan')
    ->join('keranjang', 'pesanan.idKeranjang', '=', 'keranjang.idKeranjang')
    ->where('pesanan.idPesanan', $id)
    ->get();


        foreach ($pesanan as $key) {
            Transaksi::create([
                'idPesanan'=>$key->idPesanan,
                'totalHarga'=>$key->totalHarga
            ]);
            Log::create([
                'module'=>'Transaksi',
                'action'=>'Selesai',
                'useraccess'=>$key->createBy
            ]);
            Log::create([
                'module'=>'Pesanan',
                'action'=>'selesai',
                'useraccess'=>$key->createBy
            ]);
        }
        return response()->json(["Berhasil"],200);
    }

    public function destroy($id)
    {
        $delete = keranjang::where('idKeranjang',$id);
        $data = $delete->get();
            foreach ($data as $key) {
                Log::create([
                    'module'=>'Produk',
                    'action'=>'delete '.$id,
                    'useraccess'=>$key->createBy
                ]);
                $delete = $delete->delete();
                return response()->json(["Berhasil"],200);
            }
    }
}
