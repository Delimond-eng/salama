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
        if (!Schema::hasTable('ronde011s')){
            Schema::create('ronde011s', function (Blueprint $table) {
                $table->id();
                $table->text("comment")->nullable();
                $table->string("latlng")->nullable();
                $table->string("photo")->nullable();
                $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
                $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
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
        Schema::dropIfExists('ronde011s');
    }
};
