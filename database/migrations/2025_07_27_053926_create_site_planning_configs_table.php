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
        if (!Schema::hasTable('site_planning_configs')) {
    Schema::create('site_planning_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id')->unique(); // un seul config par site
            $table->time('start_hour')->default('21:00');
            $table->unsignedTinyInteger('interval')->default(1); // en heures
            $table->unsignedTinyInteger('pause')->default(1);    // en heures
            $table->unsignedTinyInteger('number_of_plannings')->default(5);
            $table->timestamps();
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
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
        Schema::dropIfExists('site_planning_configs');
    }
};
