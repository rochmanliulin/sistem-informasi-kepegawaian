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
        Schema::create('overtime_salaries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nip');
            $table->integer('total_uang_lembur');
            $table->integer('doa');
            $table->integer('premi');
            $table->integer('gaji');
            $table->integer('total_uang_kopi');
            $table->integer('total_uang_lembur_minggu');
            $table->integer('total_uang_makan');
            $table->integer('total');
            $table->string('keterangan');
            $table->integer('hari_aktif');
            $table->float('total_jam_lembur');
            $table->date('tgl_terbit');
            $table->integer('hari_terlambat');
            $table->integer('total_terlambat');
            $table->integer('tidak_istirahat');
            $table->integer('tidak_istirahat_masuk');
            $table->integer('tidak_istirahat_kembali');
            $table->integer('lebih_istirahat');
            $table->timestamps();

            $table->foreign('nip')->references('nip')->on('allowances')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtime_salaries');
    }
};
