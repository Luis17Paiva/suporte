<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtendimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atendimento', function (Blueprint $table) {
            $table->id();
            $table->string('hash', 64)->nullable()->unique();
            $table->string('numero', 20);
            $table->string('ura', 50)->nullable();
            $table->time('hora_chamada')->nullable();
            $table->time('hora_atendimento')->nullable();
            $table->time('hora_desliga')->nullable();
            $table->unsignedBigInteger('id_ramal')->nullable();
            $table->string('status', 50);
            $table->date('data_inclusao');
            $table->string('id_asterisk', 50)->nullable();
            $table->boolean('excluido')->default(false);
            $table->timestamps();

            // Chave estrangeira para a tabela de ramais (opcional)
            // $table->foreign('id_ramal')->references('id')->on('ramais')->onDelete('set null');

            // Se desejar, você pode adicionar um índice para a coluna 'status'
            // $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('atendimento');
    }
}
