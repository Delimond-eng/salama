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
        if (!Schema::hasTable('presence_supervisor_sites')) {
            Schema::create('presence_supervisor_sites', function (Blueprint $table) {
                $table->id();
                $table->foreignId("site_id")->constrained("sites", "id")->cascadeOnDelete();
                $table->foreignId("agent_id")->constrained("agents", "id")->cascadeOnDelete();
                $table->foreignId("schedule_id")->constrained("schedule_supervisor_sites", "id")->cascadeOnDelete();
                $table->text("comment")->nullable();
                $table->string("distance")->nullable();
                $table->time("started_at")->nullable();
                $table->time("ended_at")->nullable();
                $table->string("duree")->nullable();
                $table->string("latlng")->nullable();
                $table->string("start_photo")->nullable();
                $table->string("end_photo")->nullable();
                $table->date("date");
                $table->string("status")->nullable();
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
        Schema::dropIfExists('presence_supervisor_sites');
    }
};
