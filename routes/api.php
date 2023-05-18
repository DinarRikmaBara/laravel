<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\KurirController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login',[AuthController::class,'login']); 
Route::post('register',[AuthController::class,'register']);
Route::post('seller',[AuthController::class,'rSeller']);
Route::post('kurir',[AuthController::class,'rKurir']);


Route::middleware(['admin.api'])->prefix('admin')->group(function(){

    // Get
    Route::get('user',[AdminController::class,'showUser']);                 //menampilkan semua data user 
    Route::get('log',[AdminController::class,'showLog']);                   //menampilkan semua data user 
    
    //Delete
    Route::delete('user/{id}',[AdminController::class,'userdelete']);       //hapus user per id
});


Route::middleware(['kurir.api'])->prefix('kurir')->group(function(){
    Route::post('ambil/{id}',[KurirController::class,'ambilPesanan']);            
    Route::get('pesanan',[KurirController::class,'pesanan']);   
    
    //menampilkan pesanan yg di ambil
});

Route::middleware(['user.api'])->prefix('user')->group(function(){

    //get
    Route::get('toko',[UserController::class,'toko']);                //menampilkan produk makanan ( yang aktif)
    Route::get('total/{id}',[UserController::class,'total']);               //hitung total harga semua produk yg ada di keranjang user
    Route::get('pesanan/{id}',[UserController::class,'pesanan']);               //hitung total harga semua produk yg ada di keranjang user
    Route::get('keranjang/{id}',[UserController::class,'showkeranjang']);     

    //post
    Route::post('keranjang/{id}',[UserController::class,'keranjang']);      //menambahkan produk ke keranjang
    Route::post('chekout',[UserController::class,'checkout']);              //checkout semua produk yg ada di keranjang
    Route::post('selesai/{id}',[UserController::class,'selesai']);          //produk sampai ke user

    //delete
    Route::delete('keranjang/{id}',[UserController::class,'destroy']);      //hapus produk di keranjang
});

Route::middleware(['seller.api'])->prefix('seller')->group(function(){

    //get
    Route::get('produk/{id}',[SellerController::class,'index']);

    //post
    Route::post('produk',[SellerController::class,'create']);

    //update
    Route::post('produk/{id}',[SellerController::class,'update']);
    Route::post('aktif/{id}',[SellerController::class,'aktifProduk']);
    Route::post('nonAktif/{id}',[SellerController::class,'nonAktifProduk']);
    Route::post('buka/{id}',[SellerController::class,'buka']);
    Route::post('tutup/{id}',[SellerController::class,'tutup']);

    //delete
    Route::delete('produk/{id}',[SellerController::class,'destroy']);
});
