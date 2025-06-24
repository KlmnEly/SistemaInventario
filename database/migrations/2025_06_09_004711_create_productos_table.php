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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marca_id')
                ->nullable()
                ->constrained('marcas')
                ->onDelete('set null');
            $table->foreignId('categoria_id')
                ->nullable()
                ->constrained('categorias')
                ->onDelete('set null');
            $table->foreignId('ubicacion_id')
                ->nullable()
                ->constrained('ubicaciones')
                ->onDelete('set null');
            $table->foreignId('condicion_id')
                ->nullable()
                ->constrained('condiciones')
                ->onDelete('set null');
            $table->string('codigo', 50)->unique();
            $table->string('nombre', 255);
            $table->string('serial', 255)->nullable();
            $table->tinyInteger('estado')->default(1); // 1: activo, 0: inactivo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
