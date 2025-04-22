<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Importa la clase DB

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('divition_id')->constrained();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });
        // Insertar el registro por defecto con divition_id = 1
        DB::table('departments')->insert([
            'divition_id' => 1,
            'name' => 'Departamento Principal',
            'description' => 'Departamento por defecto creado durante la migración.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
