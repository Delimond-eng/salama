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
        if (!Schema::hasTable('patrol_scans')) {
            Schema::create('patrol_scans', function (Blueprint $table) {
                $table->id();
                $table->timestamp("time")->useCurrent();
                $table->string("latlng");
                $table->string("distance");
                $table->string("comment")->nullable();
                $table->string("matricule")->nullable();
                $table->string("photo")->nullable();
                $table->string("status")->default("actif");
                $table->unsignedBigInteger("agent_id");
                $table->unsignedBigInteger("area_id");
                $table->unsignedBigInteger("patrol_id");
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
        Schema::dropIfExists('patrol_scans');
    }
};