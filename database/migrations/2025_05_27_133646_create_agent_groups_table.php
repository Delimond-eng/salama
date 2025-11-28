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
        if (!Schema::hasTable('agent_groups')) {
            Schema::create('agent_groups', function (Blueprint $table) {
                $table->id();
                $table->string("libelle");
                $table->unsignedBigInteger("horaire_id")->nullable();
                $table->string("status")->default("actif");
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
        Schema::dropIfExists('agent_groups');
    }
};
