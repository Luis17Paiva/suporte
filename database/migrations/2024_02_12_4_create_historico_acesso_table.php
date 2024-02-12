<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('acesso_hist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_acesso')->constrained('acesso');
            $table->boolean('excluido')->default(false);
            $table->foreignId('id_colaborador')->constrained('colaborador');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('acesso_hist');
    }
};
