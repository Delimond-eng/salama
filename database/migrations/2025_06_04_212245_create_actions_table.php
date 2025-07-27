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
        // migration create_actions_table
        if (!Schema::hasTable('actions')) {
    Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Voir, Créer, Modifier, Supprimer, Export, import
            $table->string('slug')->unique(); // view, create, edit, delete, export, import
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
        Schema::dropIfExists('actions');
    }
};
