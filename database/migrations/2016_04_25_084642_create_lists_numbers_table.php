<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListsNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('lists_numbers', function (Blueprint $table) {
            $table->bigIncrements('id'); // Cambiado a bigIncrements
            $table->unsignedBigInteger('phone_list_id')->unsigned();
            $table->string('phone',12);
            $table->string('operator',20);
            $table->text('attributes')->nullable();
            $table->timestamps();

            $table->foreign('phone_list_id')
                ->references('id')->on('phone_lists');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('lists_numbers');
    }
}
