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
        Schema::create('fingerprints', function (Blueprint $table) {
            $table->id();
            $table->date('tgl');
            $table->string('jadwal');
            $table->string('jam_kerja');
            $table->bigInteger('nip');
            $table->integer('terlambat')->nullable();
            $table->time('scan_istirahat_1')->nullable();
            $table->time('scan_istirahat_2')->nullable();
            $table->integer('istirahat')->nullable();
            $table->integer('durasi')->nullable();
            $table->integer('lembur_akhir');
            $table->timestamps();

            $table->foreign('nip')->references('nip')->on('employees')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fingerprints');
    }
};
