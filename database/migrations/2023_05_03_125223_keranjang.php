<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Keranjang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Keranjang', function (Blueprint $table) {
            $table->id('idKeranjang');
            $table->unsignedBigInteger('idProduk');
            $table->foreign('idProduk')->references('idProduk')->on('Produk')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('jumlahBarang');
            $table->integer('totalHarga');
            $table->string('createBy');
            $table->foreign('createBy')->references('email')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('status', ['keranjang', 'proses'])->default('keranjang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Keranjang');
    }
}
