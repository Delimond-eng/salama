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
        Schema::create('schedule_supervisor_sites', function (Blueprint $table) {
            $table->id();
            $table->foreignId("schedule_id")->nullable()->constrained("schedule_supervisors", "id")->nullOnDelete();
            $table->foreignId("site_id")->nullable()->constrained("sites", "id")->nullOnDelete();
            $table->integer("order")->default(1);
            $table->string("status")->default("pending");
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
        Schema::dropIfExists('schedule_supervisor_sites');
    }
};
