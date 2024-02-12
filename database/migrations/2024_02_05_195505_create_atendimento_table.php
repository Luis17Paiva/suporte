<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('atendimento', function (Blueprint $table) {
            $table->id();
            $table->string('hash', 64);
            $table->string('numero', 25);
            $table->string('ura', 10);
            $table->string('status', 50);
            $table->time('hora_chamada');
            $table->time('hora_atendimento')->nullable();
            $table->time('hora_desliga')->nullable();
            $table->foreignId('id_colaborador')->constrained('colaborador');
            $table->string('id_asterisk', 50);
            $table->boolean('excluido')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('atendimento');
    }
};
