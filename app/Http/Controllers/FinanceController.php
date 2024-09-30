<?php

namespace App\Http\Controllers;

use App\Models\LivrePrestation;
use Illuminate\Http\Request;
use DB;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        // Filtrage par date et état
        $query = LivrePrestation::select(
                DB::raw("SUM(prestations.prix * (1 + prestations.tva / 100)) as total"),
                'livre_des_prestations.etat',
                'livre_des_prestations.prestation_id'
            )
            ->join('prestations', 'livre_des_prestations.prestation_id', '=', 'prestations.id')
            ->groupBy('livre_des_prestations.etat', 'livre_des_prestations.prestation_id');

        if ($request->has('date_debut')) {
            $query->where('livre_des_prestations.date_prestation', '>=', $request->input('date_debut'));
        }

        if ($request->has('date_fin')) {
            $query->where('livre_des_prestations.date_prestation', '<=', $request->input('date_fin'));
        }

        if ($request->has('etat') && $request->input('etat') !== '') {
            $query->where('livre_des_prestations.etat', $request->input('etat'));
        }

        $chiffreAffaire = $query->with('prestation')->get();

        // Chiffre d'affaires par mois et année
        $chiffreAffaireParMois = LivrePrestation::select(
                DB::raw("YEAR(livre_des_prestations.date_prestation) as year, MONTH(livre_des_prestations.date_prestation) as month, SUM(prestations.prix * (1 + prestations.tva / 100)) as total")
            )
            ->join('prestations', 'livre_des_prestations.prestation_id', '=', 'prestations.id')
            ->groupBy(DB::raw('YEAR(livre_des_prestations.date_prestation)'), DB::raw('MONTH(livre_des_prestations.date_prestation)'))
            ->get();

        // Utilisateurs ayant validé le plus de prestations
        $prestationsParUser = LivrePrestation::select(
                DB::raw("COUNT(*) as total"),
                'livre_des_prestations.validated_by'
            )
            ->whereNotNull('livre_des_prestations.validated_by')
            ->groupBy('livre_des_prestations.validated_by')
            ->with('validator')
            ->get();

        // Statistiques générales (Total CA, validé, en attente, facturé, payé)
        $totalCA = LivrePrestation::join('prestations', 'livre_des_prestations.prestation_id', '=', 'prestations.id')
            ->sum(DB::raw('prestations.prix * (1 + prestations.tva / 100)'));

        $totalCAValide = LivrePrestation::join('prestations', 'livre_des_prestations.prestation_id', '=', 'prestations.id')
            ->where('livre_des_prestations.etat', 'validé')
            ->sum(DB::raw('prestations.prix * (1 + prestations.tva / 100)'));

        $totalCAAEnAttente = LivrePrestation::join('prestations', 'livre_des_prestations.prestation_id', '=', 'prestations.id')
            ->where('livre_des_prestations.etat', 'en attente')
            ->sum(DB::raw('prestations.prix * (1 + prestations.tva / 100)'));

        $totalCAFacture = LivrePrestation::join('prestations', 'livre_des_prestations.prestation_id', '=', 'prestations.id')
            ->where('livre_des_prestations.etat', 'facturé')
            ->sum(DB::raw('prestations.prix * (1 + prestations.tva / 100)'));

        $totalCAPaye = LivrePrestation::join('prestations', 'livre_des_prestations.prestation_id', '=', 'prestations.id')
            ->where('livre_des_prestations.etat', 'paid')
            ->sum(DB::raw('prestations.prix * (1 + prestations.tva / 100)'));

        return view('dashboards.finance', compact('chiffreAffaire', 'chiffreAffaireParMois', 'prestationsParUser', 'totalCA', 'totalCAValide', 'totalCAAEnAttente', 'totalCAFacture', 'totalCAPaye'));
    }
}
