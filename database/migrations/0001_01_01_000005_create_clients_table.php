<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id'); // Cambiado a bigIncrements
            $table->string('company', 50)->unique();
            $table->string('name', 100);
            $table->string('last_name', 100);
            $table->string('ip', 15);
            $table->string('port', 6);
            $table->string('server_user', 50)->nullable();
            $table->string('server_pass', 50)->nullable();
            $table->string('image');
            $table->tinyInteger('status')->nullable()->default(1);
            $table->foreignId('divition_id')->constrained();
            $table->foreignId('department_id')->constrained();

            $table->foreignId('country_id')->constrained();
            $table->foreignId('state_id')->constrained();
            $table->foreignId('city_id')->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
