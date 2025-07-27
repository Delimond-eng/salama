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
        if (!Schema::hasTable('supervision_control_elements')) {
    Schema::create('supervision_control_elements', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('description')->nullable();
            $table->boolean('active')->default(true); 
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
        Schema::dropIfExists('supervision_control_elements');
    }
};
