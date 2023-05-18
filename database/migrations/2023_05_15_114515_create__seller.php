<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeller extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller', function (Blueprint $table) {
            $table->id('idSeller');
            $table->string('namaToko')->unique();
            $table->string('gambarSeller')->nullable();
            $table->string('phoneSeller');
            $table->string('alamatToko');
            $table->string('validateEmail');
            $table->foreign('validateEmail')->references('email')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('status', ['buka', 'tutup'])->default('tutup');
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
        Schema::dropIfExists('seller');
    }
}
