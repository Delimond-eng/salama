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
        // migration create_menus_table
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ex: Produits, Utilisateurs
            $table->string('slug')->unique(); // ex: produits, utilisateurs
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
        Schema::dropIfExists('menus');
    }
};
