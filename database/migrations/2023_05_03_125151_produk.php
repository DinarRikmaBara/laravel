<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Produk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Produk', function (Blueprint $table) {
            $table->id('idProduk');
            $table->string('namaProduk');
            $table->string('gambar');
            $table->enum('jenis', ['makanan', 'minuman'])->default('makanan');
            $table->enum('role', ['publish', 'nonpublish'])->default('nonpublish');
            $table->integer('harga');
            $table->string('deskripsi');
            $table->string('validateEmail');
            $table->foreign('validateEmail')->references('email')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('Produk');
    }
}
