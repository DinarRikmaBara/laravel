<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Produk;
use App\Models\Seller;
use App\Models\Pesanan;
use App\Models\Log;


class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $data = Produk::where('validateEmail','LIKE','%'.$id.'%')->get();

        $produk = [];
        foreach ($data as $datas) {
            array_push($produk,[
                'idProduk'=>$datas->idProduk,
                'gambar'=>url($datas->gambar),
                'jenis'=>$datas->jenis,
                'role'=>$datas->role,
                'harga'=>$datas->harga,
                'deskripsi'=>$datas->deskripsi,
                'validateEmail'=>$datas->validateEmail,
            ]);
        }
        return response()->json(['data'=>$produk]);
    }
    public function cari($id)
    {
        if (is_numeric($id)) {
            $show = Produk::where('idProduk',$id)->get();
            if ($show==null) {
                return "data tidak ditemukan";
            } else {
                $produk = [];
                foreach ($show as $datas) {
                    array_push($produk,[
                        'idProduk'=>$datas->idProduk,
                        'gambar'=>url($datas->gambar),
                        'jenis'=>$datas->jenis,
                        'role'=>$datas->role,
                        'harga'=>$datas->harga,
                        'deskripsi'=>$datas->deskripsi,
                        'validateEmail'=>$datas->validateEmail,
                    ]);
                }
                return response()->json(['data'=>$produk]);
            }
        } else {
            $show = Produk::where('namaProduk','LIKE','%'.$id.'%')->get();
            if($show->isEmpty()){
                return "data tidak ditemukan";
            } else {
                $produk = [];
                foreach ($show as $datas) {
                    array_push($produk,[
                        'idProduk'=>$datas->idProduk,
                        'gambar'=>url($datas->gambar),
                        'jenis'=>$datas->jenis,
                        'role'=>$datas->role,
                        'harga'=>$datas->harga,
                        'deskripsi'=>$datas->deskripsi,
                        'validateEmail'=>$datas->validateEmail,
                    ]);
                }
                return response()->json(['data'=>$produk]);
            }
        } 
    }

    public function create(Request $request)
    {
        $validator = validator::make($request->all(),[
            'namaProduk' => 'required',
            'gambar' => 'required|mimes:png,jpg,jpeg|max:2048',
            'jenis' => 'required',
            'role' => 'required',
            'harga' => 'required',
            'deskripsi' => 'required',
        ]);
        if ($validator->fails()){
            return messageError($validator->messages()->toArray());
        }

        // ambil data dari adminMiddlaware
        $data=$request->input('email');
        $thumbail = $request->file('gambar');
        $filename = now()->timestamp."_".$request->gambar->getClientOriginalName();
        $thumbail->move('uploads',$filename);
        $produk = $validator->validated();
        Produk::create([
            'namaProduk' => $produk['namaProduk'],
            'gambar' => 'uploads/'.$filename,
            'jenis' => $produk['jenis'],
            'role' => $produk['role'],
            'harga' => $produk['harga'],
            'deskripsi' => $produk['deskripsi'],
            'validateEmail' => $data
        ]);
        Log::create([
            'module'=>'Produk',
            'action'=>'add '.$produk['namaProduk'],
            'useraccess'=>$data
        ]);
        return response()->json([
            "data"=> [
                "msg"=>"produk berhasil disimpan"
            ]
        ],200);
    }

    public function update(Request $request,$id)
    {
        ;

        $validator = validator::make($request->all(),[
            'namaProduk' => 'required',
            'gambar' => 'required|mimes:png,jpg,jpeg|max:2048',
            'jenis' => 'required',
            'role' => 'required',
            'harga' => 'required',
            'deskripsi' => 'required',
        ]);
        if ($validator->fails()){
            return messageError($validator->messages()->toArray());
        }

        // ambil data dari adminMiddlaware
        $data=$request->input('email');
        $thumbail = $request->file('gambar');
        $filename = now()->timestamp."_".$request->gambar->getClientOriginalName();
        $thumbail->move('uploads',$filename);
        $produk = $validator->validated();
        $update = Produk::where('idProduk',$id)->update([
            'namaProduk' => $produk['namaProduk'],
            'gambar' => 'uploads/'.$filename,
            'jenis' => $produk['jenis'],
            'role' => $produk['role'],
            'harga' => $produk['harga'],
            'deskripsi' => $produk['deskripsi'],
            'validateEmail' => $data
        ]);
        Log::create([
            'module'=>'Produk',
            'action'=>'update '.$produk['namaProduk'],
            'useraccess'=>$data
        ]);
        return response()->json([
            "data"=> [
                "msg"=>"produk berhasil diUpdate"
            ]
        ],200);
    }

    public function perjalanan($id)
    {
        $data = Pesanan::where('idPesanan',$id);
        $update = $data->update(['status'=>'perjalanan']);

        
        if ($update) {
            $data = Pesanan::where('idPesanan',$id)->get();
            foreach ($data as $key) {
                Log::create([
                    'module'=>'Pesanan',
                    'action'=>'status perjalanan '.$key->validateEmail,
                    'useraccess'=>$key->validateEmail
                ]);
                // kirim response ke pengguna
                return response()->json(["Berhasil"],200);
            }
        } else {
            return response()->json(['produk dengan id '.$id.' tidak ditemukan'], 200);
        }
    }
    
     public function aktifProduk($id)
    {
        $aktif = Produk::where('idProduk',$id);
        $update = $aktif->update(['role'=>'publish']);
        $aktif = Produk::where('idProduk',$id)->get();
        if ($update) {
            foreach ($aktif as $key) {
                Log::create([
                    'module'=>'Produk',
                    'action'=>'update '.$key->namaProduk.' status publish',
                    'useraccess'=>$key->validateEmail
                ]);
                return response()->json(["Berhasil"],200);
            }
        } else {
            return response()->json(['produk dengan id '.$id.' tidak ditemukan'], 200);
        }
            
    }

    public function nonAktifProduk($id)
    {
        $aktif = Produk::where('idProduk',$id);
        $update = $aktif->update(['role'=>'nonpublish']);
        $aktif = Produk::where('idProduk',$id)->get();
        if ($update) {
            foreach ($aktif as $key) {
                Log::create([
                    'module'=>'Produk',
                    'action'=>'update '.$key->namaProduk.' status nonpublish',
                    'useraccess'=>$key->validateEmail
                ]);
                return response()->json(["Berhasil"],200);
            }
        } else {
            return response()->json(['produk dengan id '.$id.' tidak ditemukan'], 200);
        }
    }

    public function buka(Request $request, $id)
    {
        $aktif = Seller::where('validateEmail','LIKE','%'.$id.'%');
        $update = $aktif->update(['status'=>'buka']);
        $aktif = Seller::where('idSeller',$id)->get();
        if ($update) {
                Log::create([
                    'module'=>'seller',
                    'action'=>'update status buka',
                    'useraccess'=>$id
                ]);
                return response()->json(["Berhasil"],200);
        } else {
            return response()->json(['seller dengan id '.$id.' tidak ditemukan'], 200);
        }
    }
    
    public function tutup(Request $request, $id)
    {
        $aktif = Seller::where('validateEmail','LIKE','%'.$id.'%');
        $update = $aktif->update(['status'=>'tutup']);
        if ($update) {
                Log::create([
                    'module'=>'seller',
                    'action'=>'update status tutup',
                    'useraccess'=>$id
                ]);
                return response()->json(["Berhasil"],200);
        } else {
            return response()->json(['seller dengan id '.$id.' tidak ditemukan'], 200);
        }
    }

    public function destroy($id)
    {
        $data = Produk::where('idProduk',$id);
        
        $data1 = Produk::where('idProduk',$id)->get();
            foreach ($data1 as $key) {
                Log::create([
                    'module'=>'Produk',
                    'action'=>'delete '.$key->namaProduk,
                    'useraccess'=>$key->validateEmail
                ]);
                $delete = $data->delete();
                return response()->json(["Berhasil"],200);
            }

    }
}
