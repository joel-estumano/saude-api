<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEntidades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entidades', function (Blueprint $table) {
            $table->uuid('uuid')->primary(); 
            $table->string('razao_social'); 
            $table->string('nome_fantasia'); 
            $table->string('cnpj')->unique(); 
            $table->uuid('regional_id'); 
            $table->date('data_inauguracao'); 
            $table->boolean('ativa')->default(true); 
            $table->timestamps(); 

           
            $table->foreign('regional_id')->references('uuid')->on('regionais')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entidades');
    }
}