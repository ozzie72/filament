<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('numbers', function (Blueprint $table) {
            //
            $table->bigIncrements('id'); // Cambiado a bigIncrements
            $table->unsignedBigInteger('campaign_id')->unsigned();
            $table->string('operator',20);
            $table->string('phone',20);
            $table->text('message');
            $table->char('send_status',1)->default('P');
            $table->dateTime('send_date',1)->nullable();
            $table->string('send_response',100)->nullable();
            $table->timestamps();

        });

        Schema::table('numbers', function (Blueprint $table) {
            $table->foreign('campaign_id')
                ->references('id')->on('campaigns');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('numbers');
    }
}
