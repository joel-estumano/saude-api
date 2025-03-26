<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEntidadeEspecialidades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entidade_especialidades', function (Blueprint $table) {
            $table->uuid('entidade_uuid');
            $table->uuid('especialidade_uuid');
            $table->timestamps();

            // Definindo as chaves estrangeiras
            $table->foreign('entidade_uuid')->references('uuid')->on('entidades')->onDelete('cascade');
            $table->foreign('especialidade_uuid')->references('uuid')->on('especialidades')->onDelete('cascade');

            // Garantir que uma combinação seja única
            $table->unique(['entidade_uuid', 'especialidade_uuid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entidade_especialidades');
    }
}