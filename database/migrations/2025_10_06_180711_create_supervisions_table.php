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
        Schema::create('supervisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supervisor_id'); // utilisateur superviseur
            $table->unsignedBigInteger('site_id');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->string('general_comment')->nullable();
            $table->string('photo_debut')->nullable();
            $table->string('photo_fin')->nullable();
            $table->string('latlng')->nullable();
            $table->string('distance')->nullable();
            $table->string('status')->default('actif');
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
        Schema::dropIfExists('supervisions');
    }
};
