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
        if (!Schema::hasTable('schedule_supervisors')) {
    Schema::create('schedule_supervisors', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->date("date")->nullable();
            $table->string("status")->default("actif");
            $table->string("comment")->nullable();
            $table->foreignId("agent_id")->nullable()->constrained("agents", "id")->nullOnDelete();
            $table->foreignId("user_id")->nullable()->constrained("users", "id")->nullOnDelete();
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
        Schema::dropIfExists('schedule_supervisors');
    }
};
