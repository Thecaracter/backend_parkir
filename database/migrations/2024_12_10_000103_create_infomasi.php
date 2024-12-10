<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('informasi', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_kendaraan', ['motor', 'mobil'])->nullable(false);
            $table->string('area');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('kapasitas');
            $table->string('foto');
            $table->integer('poin')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informasi');
    }
};