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
        Schema::create('supervision_agent_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supervision_agent_id');
            $table->unsignedBigInteger('control_element_id');
            $table->enum('note', ['B', 'P', 'M'])->nullable(); // Bien, Passable, Mauvais
            $table->text('comment')->nullable(); // observation spécifique
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
        Schema::dropIfExists('supervision_agent_notes');
    }
};
