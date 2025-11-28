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
        if (!Schema::hasTable('signalements')) {
    Schema::create('signalements', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("description");
            $table->string("media")->nullable();
            $table->unsignedBigInteger("agent_id");
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
        Schema::dropIfExists('signalements');
    }
};
