<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Ajouter des colonnes à la table users
            
            
            $table->string('prenom');
            $table->date('date_de_naissance');
            $table->string('telephone')->nullable();
            $table->string('adresse')->nullable();
            $table->string('email_de_facturation')->nullable();
            $table->enum('type_client', ['Gérant', 'Client', 'Moniteur', 'Vétérinaire', 'Maréchal']);
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées en cas de rollback
            $table->dropColumn([
                
                'prenom',
                'date_de_naissance',
                'telephone',
                'adresse',
                'email_de_facturation',
                'type_client',
                
            ]);
        });
    }
}
