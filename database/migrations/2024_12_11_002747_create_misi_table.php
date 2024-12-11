<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('misi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('tipe', ['pertama', 'harian', 'mingguan']);
            $table->integer('jumlah')->default(0);
            $table->boolean('selesai')->default(false);
            $table->boolean('poin_diklaim')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('misi');
    }
};