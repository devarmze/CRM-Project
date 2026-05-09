<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clienti', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('tipo')->default('azienda');
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->string('citta')->nullable();
            $table->string('settore')->nullable();
            $table->string('stato')->default('prospect');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clienti');
    }
};
