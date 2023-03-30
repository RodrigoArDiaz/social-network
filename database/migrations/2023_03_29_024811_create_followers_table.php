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
        Schema::create('followers', function (Blueprint $table) {
            $table->id();
            //
            $table->foreignId('user_id_send')->references('id')->on('users'); //Usuario que envia solicitud
            $table->foreignId('user_id_receive')->references('id')->on('users'); //Usuario que recibe la solicitud
            $table->string('state',1); //Indica el estado de la solicitud: P=pendiente A=aceptado R=Rechazado
            //
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
        Schema::dropIfExists('followers');
    }
};