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
        if (!Schema::hasTable('sites')) {
            Schema::create('sites', function (Blueprint $table) {
                $table->id();
                $table->string("name");
                $table->string("code")->unique();
                $table->string("adresse");
                $table->string("latlng")->nullable();
                $table->string("phone")->nullable();
                $table->string("client_email")->nullable();
                $table->integer("presence")->nullable();
                $table->string("client_fcm_token")->nullable();
                $table->string("otp")->nullable();
                $table->text("emails")->nullable();
                $table->unsignedBigInteger("agency_id");
                $table->unsignedBigInteger("secteur_id")->nullable();
                $table->string("status")->default("actif");
                $table->string("fcm_token")->nullable();
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
        Schema::dropIfExists('sites');
    }
};
