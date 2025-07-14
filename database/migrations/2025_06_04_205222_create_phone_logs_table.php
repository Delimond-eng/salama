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
        Schema::create('phone_logs', function (Blueprint $table) {
            $table->id();
            $table->string("reason")->nullable();
            $table->string("battery_level")->nullable();
            $table->dateTime("date_and_time")->nullable();
            $table->unsignedInteger("agent_id");
            $table->unsignedInteger("site_id")->nullable();
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
        Schema::dropIfExists('phone_logs');
    }
};
