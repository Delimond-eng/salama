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
        if (!Schema::hasTable('agent_requests')) {
    Schema::create('agent_requests', function (Blueprint $table) {
            $table->id();
            $table->string("object");
            $table->string("description");
            $table->unsignedBigInteger("agent_id");
            $table->unsignedBigInteger("agency_id");
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
        Schema::dropIfExists('agent_requests');
    }
};
