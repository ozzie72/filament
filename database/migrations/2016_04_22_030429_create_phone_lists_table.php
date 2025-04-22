<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhoneListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phone_lists', function (Blueprint $table) {
            $table->bigIncrements('id'); // Cambiado a bigIncrements
            $table->unsignedBigInteger('client_id')->unsigned();
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->string('name',100);
            $table->string('description',255);
            $table->string('token',100);
            $table->text('attributes');
            $table->integer('params');
            $table->timestamps();

        });

        Schema::table('phone_lists', function (Blueprint $table) {
            $table->foreign('client_id')
                ->references('id')->on('clients');
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
        Schema::drop('phone_lists');
    }
}
