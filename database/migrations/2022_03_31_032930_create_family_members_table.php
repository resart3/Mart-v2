<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('family_members', function (Blueprint $table) {
            $table->id();
            $table->string('family_card_id');
            $table->string('nama');
            $table->string('nik');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki - Laki', 'Perempuan']);
            $table->enum('agama', ['ISLAM', 'PROTESTAN', 'KATOLIK', 'HINDU', 'BUDDHA', 'KHONGHUCU']);
            $table->string('pendidikan');
            $table->string('pekerjaan');
            $table->enum('golongan_darah', ['A', 'AB', 'B', 'O', 'TIDAK TAHU']);
            $table->boolean('isFamilyHead')->default(0);
            $table->foreign('family_card_id')->references('nomor')->on('family_cards');
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
        Schema::dropIfExists('family_members');
    }
};
