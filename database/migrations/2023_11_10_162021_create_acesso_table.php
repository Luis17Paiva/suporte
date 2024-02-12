<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcessoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acesso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cliente')->constrained('cliente');
            $table->boolean('excluido')->default(false);
            $table->string('acesso_tipo', 30)->nullable();
            $table->string('acesso_id', 50)->nullable();
            $table->string('acesso_pass', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('acesso');
    }
};
