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
            $table->string('pension')->nullable(); // Ajoute la colonne pension, qui peut être vide
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('chevals', function (Blueprint $table) {
            $table->dropColumn('pension');
        });
    }
};
