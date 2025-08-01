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
        if (!Schema::hasTable('announces')) {
    Schema::create('announces', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->text("content");
            $table->string("status")->default("actif");
            $table->unsignedBigInteger("site_id")->nullable();
            $table->unsignedBigInteger("agency_id");
            $table->timestamps();
        });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('announces');
    }
};
