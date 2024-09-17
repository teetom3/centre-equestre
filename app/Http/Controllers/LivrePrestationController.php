<?php

namespace App\Http\Controllers;

use App\Models\LivrePrestation;
use Illuminate\Http\Request;
use App\Models\Cheval;
use App\Models\Prestation;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class LivrePrestationController extends Controller
{

   
    public function index(Request $request)
    {
        // Récupérer la date actuelle
        $today = Carbon::today()->format('Y-m-d');

        // Construire la requête pour récupérer les prestations futures et celles d'aujourd'hui
        $query = LivrePrestation::whereDate('date_prestation', '>=', $today)
            ->with('cheval', 'prestation')
            ->orderBy('date_prestation', 'asc');

        // Appliquer le filtre par cheval, si présent
        if ($request->input('cheval_id')) {
            $query->where('cheval_id', $request->input('cheval_id'));
        }

        // Appliquer le filtre par nom de prestation, si présent
        if ($request->input('nom_prestation')) {
            $query->whereHas('prestation', function ($query) use ($request) {
                $query->where('nom', 'like', '%' . $request->input('nom_prestation') . '%');
            });
        }

        // Appliquer le filtre par date, si présent
        if ($request->input('date_prestation')) {
            $query->whereDate('date_prestation', $request->input('date_prestation'));
        }

        // Exécuter la requête pour récupérer les prestations
        $prestations = $query->get();

        // Récupérer tous les chevaux pour le filtre
        $chevaux = Cheval::all();

        return view('livreprestations.index', compact('prestations', 'chevaux','today'));
    }
    
    public function store(Request $request, $chevalId)
    {
        // Validation des données
        $request->validate([
            'selected_date' => 'required|date',
            'prestations' => 'required|array',
            'prestations.*' => 'exists:prestations,id',  // Assurez-vous que chaque prestation existe dans la table 'prestations'
        ]);
    
        $date = $request->input('selected_date');
        $prestations = $request->input('prestations');
    
        // Insertion des prestations dans la base de données
        foreach ($prestations as $prestationId) {
            LivrePrestation::create([
                'cheval_id' => $chevalId,
                'prestation_id' => $prestationId,
                'date_prestation' => $date,
            ]);
        }
    
        return redirect()->back()->with('success', 'Prestations affectées avec succès.');
    }

    public function indexForGerant(Request $request)
{
    // Récupérer toutes les prestations avec les relations nécessaires
    $query = LivrePrestation::with('prestation', 'cheval', 'cheval.user')
    ->orderBy('date_prestation', 'desc');

    // Filtrer par type de prestation
    if ($request->filled('prestation_id')) {
        $query->where('prestation_id', $request->prestation_id);
    }

    // Filtrer par cheval
    if ($request->filled('cheval_id')) {
        $query->where('cheval_id', $request->cheval_id);
    }

    // Filtrer par propriétaire
    if ($request->filled('proprietaire_id')) {
        $query->whereHas('cheval.user', function($q) use ($request) {
            $q->where('id', $request->proprietaire_id);
        });
    }

    // Filtrer par date
    if ($request->filled('date_debut') && $request->filled('date_fin')) {
        $query->whereBetween('date_prestation', [$request->date_debut, $request->date_fin]);
    }

    // Filtrer par état
    if ($request->filled('etat')) {
        $query->where('etat', $request->etat);
    }

    // Récupérer les résultats
    $livrePrestations = $query->get();

    // Récupérer toutes les prestations, chevaux, et propriétaires pour les filtres
    $prestations = Prestation::all();
    $chevaux = Cheval::all();
    $proprietaires = User::whereHas('chevaux')->get(); // Récupérer les utilisateurs qui ont des chevaux

    return view('livreprestations.indexForGerant', compact('livrePrestations', 'prestations', 'chevaux', 'proprietaires'));
}

    public function changeState(Request $request, $id)
{
    $request->validate([
        'etat' => 'required|string|in:en attente,validé,facturé,paid',
    ]);

    $livrePrestation = LivrePrestation::findOrFail($id);

    $livrePrestation->etat = $request->etat;

    if ($request->etat === 'validé') {
        $livrePrestation->date_validation = now();
        $livrePrestation->validated_by = Auth::id();
    } elseif ($request->etat === 'facturé') {
        $livrePrestation->date_facturation = now();
        $livrePrestation->invoiced_by = Auth::id();
    } elseif ($request->etat === 'payée') {
        $livrePrestation->date_paiement = now();
        $livrePrestation->paid_by = Auth::id();
    }

    $livrePrestation->save();

    return redirect()->back()->with('success', 'État de la prestation mis à jour avec succès.');
}

public function massAddForm(Request $request)
{
    // Récupérer les chevaux avec leurs propriétaires
    $query = Cheval::with('user');

    // Filtrer par nom de cheval
    if ($request->filled('cheval_nom')) {
        $query->where('nom', 'like', '%' . $request->cheval_nom . '%');
    }

    // Filtrer par propriétaire
    if ($request->filled('proprietaire_id')) {
        $query->where('user_id', $request->proprietaire_id);
    }

    // Récupérer les résultats filtrés
    $chevaux = $query->get();

    // Récupérer toutes les prestations et propriétaires pour le formulaire
    $prestations = Prestation::all();
    $proprietaires = User::has('chevaux')->get();

    return view('livreprestations.massAdd', compact('chevaux', 'prestations', 'proprietaires'));
}

public function massAdd(Request $request)
{
    $request->validate([
        'chevaux' => 'required|array',
        'prestations' => 'required|array',
        'date_prestation' => 'required|date',
    ]);

    $chevaux = $request->chevaux;
    $prestations = $request->prestations;
    $datePrestation = $request->date_prestation;

    foreach ($chevaux as $chevalId) {
        foreach ($prestations as $prestationId) {
            LivrePrestation::create([
                'cheval_id' => $chevalId,
                'prestation_id' => $prestationId,
                'date_prestation' => $datePrestation,
                'etat' => 'en attente', // Par défaut, les prestations sont en attente
            ]);
        }
    }

    return redirect()->back()->with('success', 'Prestations ajoutées avec succès.');
}


    
    public function destroy($id)
    {
        $livrePrestation = LivrePrestation::findOrFail($id);
        $livrePrestation->delete();

        return back()->with('success', 'Prestation annulée avec succès.');
    }
}
