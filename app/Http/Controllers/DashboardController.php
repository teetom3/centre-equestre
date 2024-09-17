<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LivrePrestation;
use App\Models\Evenement;
use Illuminate\Support\Carbon;

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
    $today = Carbon::today();
    
    // Récupérer toutes les prestations du jour et les grouper par type
    $prestationsParType = LivrePrestation::whereDate('date_prestation', $today)
                    ->with('prestation', 'cheval')
                    ->orderBy('date_prestation', 'asc')
                    ->get()
                    ->groupBy(function($item) {
                        return $item->prestation->type;
                    });
                      // Récupérer les événements avec les inscrits
    $evenements = Evenement::with('inscriptions.user', 'inscriptions.cheval')->orderBy('date', 'desc')->get();

    return view('dashboards.gerant', compact('prestationsParType', 'evenements', 'today'));

    
}
public function marechalDashboard(Request $request)
{
    // Vérifier que l'utilisateur connecté est bien un maréchal
    

    // Récupérer les prestations de type "Maréchal"
    $prestations = \App\Models\LivrePrestation::whereHas('prestation', function($query) {
        $query->where('type', 'Maréchal');
    })
    ->with('prestation', 'cheval')
    ->orderBy('date_prestation', 'asc')
    ->get();

    return view('dashboard.marechal', compact('prestations'));
}

public function veterinaireDashboard()
{
    // Récupérer les prestations de type "Vétérinaire" pour le vétérinaire
    $prestations = LivrePrestation::with('prestation', 'cheval')
        ->whereHas('prestation', function($query) {
            $query->where('type', 'Vétérinaire');
        })
        ->get();

    return view('dashboards.veterinaire', compact('prestations'));
}

}
