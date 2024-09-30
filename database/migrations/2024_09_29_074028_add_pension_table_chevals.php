<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('chevals', function (Blueprint $table) {
            $table->text('commentaire')->nullable(); // Ajout d'un champ texte pour les commentaires, nullable pour autoriser des champs vides
        });
    }
    
    public function down()
    {
        Schema::table('chevals', function (Blueprint $table) {
            $table->dropColumn('commentaire');
        });
    }
    
};
