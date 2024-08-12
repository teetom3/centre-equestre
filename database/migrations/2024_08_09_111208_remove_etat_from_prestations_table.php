<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveEtatFromPrestationsTable extends Migration
{
    public function up()
    {
        Schema::table('prestations', function (Blueprint $table) {
            $table->dropColumn('etat');
        });
    }

    public function down()
    {
        Schema::table('prestations', function (Blueprint $table) {
            $table->string('etat')->default('en_cours');
        });
    }
}

