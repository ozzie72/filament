<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('temp_numbers', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->string('token',100);
            $table->string('operator',20);
            $table->string('phone',20);
            $table->text('attributes')->nullable();
            $table->timestamps();

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
        Schema::drop('temp_numbers');
    }
}
