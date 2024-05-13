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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->date('tgl');
            $table->bigInteger('nip');
            $table->integer('uang_lembur')->nullable();
            $table->integer('uang_makan')->nullable();
            $table->integer('uang_kopi')->nullable();
            $table->integer('uang_lembur_minggu')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
