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
        Schema::create('allowances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nip');
            $table->integer('gaji')->nullable();
            $table->integer('kos')->nullable();
            $table->integer('masuk_pagi')->nullable();
            $table->integer('prestasi')->nullable();
            $table->integer('komunikasi')->nullable();
            $table->integer('jabatan')->nullable();
            $table->integer('lain_lain')->nullable();
            $table->integer('uang_makan')->nullable();
            $table->integer('kasbon')->nullable();
            $table->integer('premi_hadir')->nullable();
            $table->integer('premi_lembur')->nullable();
            $table->integer('doa')->nullable();
            $table->timestamps();

            $table->foreign('nip')->references('nip')->on('employees')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allowances');
    }
};
