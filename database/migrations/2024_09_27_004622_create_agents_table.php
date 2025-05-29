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
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string("matricule")->unique();
            $table->string("photo")->nullable();
            $table->string("fullname");
            $table->string("password");
            $table->string("role")->default("guard");
            $table->unsignedBigInteger("agency_id");
            $table->unsignedBigInteger("site_id")->nullable();
            $table->unsignedBigInteger("groupe_id")->nullable();
            $table->string("status")->default("actif");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agents');
    }
};
