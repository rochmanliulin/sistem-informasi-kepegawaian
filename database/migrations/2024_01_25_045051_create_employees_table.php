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
        Schema::create('employees', function (Blueprint $table) {
            $table->bigInteger('nip')->primary();
            $table->string('nama');
            $table->char('credited_account', 10)->nullable();
            $table->string('jabatan')->nullable();
            $table->string('departemen')->nullable();
            $table->string('status')->nullable();
            $table->date('tgl_masuk_kerja')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
