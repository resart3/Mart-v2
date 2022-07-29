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
        Schema::create('lands', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('family_card_id');
            $table->foreign('family_card_id')->references('nomor')->on('family_cards');

            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');            

            $table->integer("area");
            $table->string("house_number");
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
        Schema::dropIfExists('lands');
    }
};
