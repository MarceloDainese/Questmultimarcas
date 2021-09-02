<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id', false, 10);
            $table->string('nome_veiculo',191);
            $table->string('link',191);
            $table->string('ano',5);
	        $table->string('combustivel',191);
            $table->integer('portas', false, 11);
            $table->integer('quilometragem', false, 11);
            $table->string('cambio',191);
            $table->string('cor',191);
            $table->double('preco');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
    }
}
