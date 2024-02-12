<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cliente_contato', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cliente')->constrained('cliente');
            $table->string('numero', 25)->nullable();
            $table->string('email', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cliente_contato');
    }
};