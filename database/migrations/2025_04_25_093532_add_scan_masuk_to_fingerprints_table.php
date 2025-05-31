<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScanMasukToFingerprintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fingerprints', function (Blueprint $table) {
            $table->time('scan_masuk')
                ->nullable()
                ->after('nip')
                ->comment('Waktu scan masuk karyawan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fingerprints', function (Blueprint $table) {
            $table->dropColumn('scan_masuk');
        });
    }
}
