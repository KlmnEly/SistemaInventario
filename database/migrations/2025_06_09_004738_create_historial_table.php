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
        Schema::create('historial', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')
                ->constrained('productos')
                ->onDelete('cascade');
            $table->foreignId('tipo_evento_id')
                ->constrained('tipos_evento')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->datetime('fecha_evento');
            $table->text('descripcion')->nullable();
            $table->foreignId('ubicacion_antigua_id')
                ->nullable()
                ->constrained('ubicaciones')
                ->onDelete('set null');
            $table->foreignId('ubicacion_nueva_id')
                ->nullable()
                ->constrained('ubicaciones')
                ->onDelete('set null');
            $table->foreignId('condicion_antigua_id')
                ->nullable()
                ->constrained('condiciones')
                ->onDelete('set null');
            $table->foreignId('condicion_nueva_id')
                ->nullable()
                ->constrained('condiciones')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial');
    }
};
