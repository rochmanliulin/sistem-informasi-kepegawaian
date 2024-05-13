<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nip');
            $table->string('fullname');
            $table->string('role')->default('user');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            // $table->foreign('nip')->references('nip')->on('employees')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
