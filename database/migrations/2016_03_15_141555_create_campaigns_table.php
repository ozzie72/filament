<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->bigIncrements('id'); // Cambiado a bigIncrements
            $table->unsignedBigInteger('client_id')->unsigned();
            $table->string('name');
            $table->boolean('extended_schedule');
            $table->date('start_date');
            $table->char('start_hour',2);
            $table->char('start_minute',2);
            $table->text('message');
            $table->integer('total_sent');
            $table->integer('total_error');
            $table->string('remote_ip', 50);
            $table->unsignedBigInteger('created_user')->unsigned();
            $table->unsignedBigInteger('updated_user')->unsigned();
            $table->char('status_send',1)->default('P');
            $table->timestamps();
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->foreign('client_id')
                ->references('id')->on('clients');

            $table->foreign('created_user')
                ->references('id')->on('users');

            $table->foreign('updated_user')
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
        Schema::drop('campaigns');
    }
}
