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
            $table->string('family_card_id');
            $table->foreign('family_card_id')->references('nomor')->on('family_cards');

            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');            

            $table->integer("amount");
            $table->string("house_number");
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
