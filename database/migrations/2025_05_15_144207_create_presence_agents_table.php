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
        Schema::create('presence_agents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("agent_id");
            $table->unsignedBigInteger("site_id");
            $table->unsignedBigInteger("horaire_id");
            $table->string("started_at")->nullable();
            $table->string("ended_at")->nullable();
            $table->string("duree")->nullable();
            $table->string("retard")->nullable();
            $table->string("photos_debut")->nullable();
            $table->string("photos_fin")->nullable();
            $table->string("status_photo_debut")->nullable();
            $table->string("status_photo_fin")->nullable();
            $table->string("commentaires")->nullable();
            $table->string("status")->default("arrive");
            $table->date('date_reference')->nullable();
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
        Schema::dropIfExists('presence_agents');
    }
};
