<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pesanan extends Model
{
    use HasFactory;
    protected $table = 'pesanan';
    protected $fillable = ['createBy','idKeranjang','namaKurir','namaUser','alamatUser','phoneKurir','phoneUser','PesananStatus'];
}
