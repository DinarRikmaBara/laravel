<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesanan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('idPesanan');
            $table->string('createBy');
            $table->foreign('createBy')->references('email')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('idKeranjang');
            $table->foreign('idKeranjang')->references('idKeranjang')->on('Keranjang')->onDelete('cascade')->onUpdate('cascade');
            $table->string('emailKurir')->nullable();
            $table->string('namaKurir')->nullable();
            $table->string('phoneKurir')->nullable();
            $table->string('namaUser');
            $table->string('phoneUser');
            $table->string('alamatUser');
            $table->enum('pesananStatus', ['proses', 'perjalanan', 'selesai'])->default('proses');
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
        Schema::dropIfExists('pesanan');
    }
}
