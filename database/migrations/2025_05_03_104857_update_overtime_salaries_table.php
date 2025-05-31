<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOvertimeSalariesTable extends Migration
{
    public function up()
    {
        // Langkah 1: Rename kolom premi menjadi premi_hadir
        Schema::table('overtime_salaries', function (Blueprint $table) {
            $table->renameColumn('premi', 'premi_hadir');
        });

        // Langkah 2: Menambahkan kolom premi_lembur dan hari_kerja
        Schema::table('overtime_salaries', function (Blueprint $table) {
            $table->integer('premi_lembur')->default(0)->after('premi_hadir');
            $table->integer('hari_kerja')->default(0)->after('hari_aktif');
        });
    }

    public function down()
    {
        Schema::table('overtime_salaries', function (Blueprint $table) {
            $table->renameColumn('premi_hadir', 'premi');
        });

        Schema::table('overtime_salaries', function (Blueprint $table) {
            $table->dropColumn('premi_lembur');
            $table->dropColumn('hari_kerja');
        });
    }
}
