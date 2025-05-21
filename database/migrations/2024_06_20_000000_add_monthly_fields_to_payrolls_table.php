<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->integer('bulan')->nullable()->after('remark');
            $table->integer('tahun')->nullable()->after('bulan');
            $table->date('tanggal_terbit')->nullable()->after('tahun');
            $table->text('catatan')->nullable()->after('tanggal_terbit');
        });
    }

    public function down()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['bulan', 'tahun', 'tanggal_terbit', 'catatan']);
        });
    }
};
