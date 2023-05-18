<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kurir extends Model
{
    use HasFactory;
    protected $table = 'kurir';
    protected $fillable = ['namaKurir','phoneKurir','namaToko','status'];
}
