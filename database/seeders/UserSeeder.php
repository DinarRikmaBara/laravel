<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Seller;
use App\Models\kurir;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username'=>'admin',
            'email'=>'admin@admin.com',
            'password'=>'admin',
            'role'=>'admin'
        ]);
        User::create([
            'username'=>'seller',
            'email'=>'seller@seller.com',
            'password'=>'seller',
            'role'=>'seller'
        ]);
        User::create([
            'username'=>'User',
            'email'=>'user@user.com',
            'password'=>'user',
            'role'=>'user'
        ]);
        User::create([
            'username'=>'kurir',
            'email'=>'kurir@kurir.com',
            'password'=>'kurir',
            'role'=>'kurir'
        ]);
        Seller::create([
            'namaToko'=>'bayuStore',
            'alamatToko'=>'jln.kertabumi kab.karawang',
            'phoneSeller'=>'08222278688',
            'validateEmail'=>'seller@seller.com',
            'status'=>'tutup'
        ]);
        kurir::create([
            'namaKurir'=>'bayu',
            'phoneKurir'=>'089278688',
            'emailKurir'=>'kurir@kurir.com',
        ]);
    }
}
