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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('terrain_id');
            $table->unsignedBigInteger('client_id'); 
            $table->date('DateDebut');
            $table->date('DateFin');
            $table->boolean('drafts')->default(true);
            $table->timestamps();
            $table->foreign('terrain_id')
            ->references('id')->on('terrains')
            ->onDelete('cascade');
            $table->foreign('client_id')
            ->references('id')->on('Clients')
            ->onDelete('cascade');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};