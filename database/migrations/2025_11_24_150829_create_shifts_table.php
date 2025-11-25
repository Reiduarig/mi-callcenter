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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');             // día del turno
            $table->time('start_time');       // inicio
            $table->time('end_time');         // fin
            $table->foreignId('shift_template_id')->nullable()->after('user_id')->constrained('shift_templates')->nullOnDelete();
            $table->boolean('is_custom')->default(false)->after('shift_template_id'); // true si horarios fueron personalizados

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
