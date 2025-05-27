<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('monthly_salaries', function (Blueprint $table) {
            $table->id(); // Ini saja yang auto increment
            $table->bigInteger('nip');
            $table->integer('gaji')->unsigned()->default(0); // Default value 0
            $table->integer('kos')->unsigned()->default(0); // Default value 0
            $table->integer('masuk_pagi')->unsigned()->default(0); // Default value 0
            $table->integer('prestasi')->unsigned()->default(0); // Default value 0
            $table->integer('komunikasi')->unsigned()->default(0); // Default value 0
            $table->string('jabatan')->default(''); // Default empty string
            $table->integer('lain_lain')->unsigned()->default(0); // Default value 0
            $table->integer('uang_makan')->unsigned()->default(0); // Default value 0
            $table->integer('kasbon')->unsigned()->default(0); // Default value 0
            $table->integer('premi_hadir')->unsigned()->default(0); // Default value 0
            $table->integer('doa')->unsigned()->default(0); // Default value 0
            $table->integer('total_gaji')->unsigned()->default(0); // Default value 0
            $table->string('keterangan')->unsigned()->default(0); // Default value 0
            $table->integer('jumlah_hari_kerja')->unsigned()->default(0); // Default value 0
            $table->integer('jumlah_hari_kerja_aktif')->unsigned()->default(0); // Default value 0
            $table->date('tanggal_terbit');
            $table->integer('month');
            $table->integer('year');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('monthly_salaries');
    }
};
