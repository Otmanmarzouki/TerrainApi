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
        Schema::create('terrains', function (Blueprint $table) {
            $table->id();
            $table->string('Nom_Terrain');
            $table->string('activité');
            $table->integer('Capacité');
            $table->string('type_Terrain');   
            $table->decimal('prix', 8, 2);     
            $table->string('dimension1');      
            $table->string('dimension2');      
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
        Schema::dropIfExists('terrains');
    }
};