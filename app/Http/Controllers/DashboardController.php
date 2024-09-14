<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Affiche la vue du tableau de bord.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retourne la vue du tableau de bord située dans resources/views/users/dashboard.blade.php
        return view('users.dashboard'); 
    }


    public function gerantDashboard()
{
    $today = \Carbon\Carbon::today();
    
    // Récupérer toutes les prestations du jour et les grouper par type
    $prestationsParType = \App\Models\LivrePrestation::whereDate('date_prestation', $today)
                    ->with('prestation', 'cheval')
                    ->orderBy('date_prestation', 'asc')
                    ->get()
                    ->groupBy(function($item) {
                        return $item->prestation->type;
                    });

    return view('dashboards.gerant', compact('prestationsParType', 'today'));
}

}
