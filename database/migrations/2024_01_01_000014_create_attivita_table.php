<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attivita', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained('clienti')->nullOnDelete();
            $table->foreignId('opportunita_id')->nullable()->constrained('opportunita')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('tipo')->default('chiamata');
            $table->dateTime('data');
            $table->text('note')->nullable();
            $table->string('stato')->default('da_fare');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attivita');
    }
};
