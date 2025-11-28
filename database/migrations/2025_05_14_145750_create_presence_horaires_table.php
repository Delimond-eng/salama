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
        if (!Schema::hasTable('presence_horaires')) {
            Schema::create('presence_horaires', function (Blueprint $table) {
                $table->id();
                $table->string("libelle");
                $table->time("started_at");
                $table->time("ended_at");
                $table->time("tolerence")->nullable();
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
        Schema::dropIfExists('presence_horaires');
    }
};
