<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->unsigned()->nullable();
            $table->string('operation'); // O text() si puede ser muy largo
            $table->string('ip', 50);
            $table->string('method', 10)->nullable(); // GET, POST, etc.
            $table->text('url')->nullable(); // URL completa puede ser larga
            $table->json('type')->nullable(); // Para guardar objetos JSON
            $table->text('details')->nullable(); // Texto largo para detalles
            $table->timestamps();
        });

        Schema::table('logs', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('logs');
    }
}
