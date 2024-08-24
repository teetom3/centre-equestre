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
    Schema::table('livre_des_prestations', function (Blueprint $table) {
        $table->string('etat')->default('en attente'); // État de la prestation
        $table->dateTime('date_validation')->nullable(); // Date de validation
        $table->dateTime('date_facturation')->nullable(); // Date de facturation
        $table->dateTime('date_paiement')->nullable(); // Date de paiement
        $table->foreignId('validated_by')->nullable()->constrained('users'); // Utilisateur qui a validé
        $table->foreignId('invoiced_by')->nullable()->constrained('users'); // Utilisateur qui a facturé
        $table->foreignId('paid_by')->nullable()->constrained('users'); // Utilisateur qui a marqué comme payé
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livre_des_prestations', function (Blueprint $table) {
            //
        });
    }
};
