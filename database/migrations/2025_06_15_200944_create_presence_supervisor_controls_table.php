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
        Schema::create('presence_supervisor_controls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presence_id')->constrained('presence_supervisor_sites')->cascadeOnDelete();
            $table->foreignId('element_id')->constrained('supervision_control_elements')->cascadeOnDelete();
            $table->foreignId('agent_id')->constrained('supervision_control_elements')->cascadeOnDelete();
            $table->enum('note', ['B', 'P', 'M'])->nullable(); // Bien, Passable, Mauvais
            $table->string('observation')->nullable();
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
        Schema::dropIfExists('presence_supervisor_controls');
    }
};
