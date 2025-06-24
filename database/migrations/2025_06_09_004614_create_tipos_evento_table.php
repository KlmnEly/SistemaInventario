<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tipos_evento', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->text('descripcion')->nullable();
            $table->tinyInteger('estado')->default(1); // 1: activo, 0: inactivo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_evento');
    }
};
