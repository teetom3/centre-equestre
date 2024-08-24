<?php

namespace App\Http\Controllers;

use App\Models\LivrePrestation;
use Illuminate\Http\Request;
use App\Models\Cheval;
use App\Models\Prestation;
use Carbon\Carbon;
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



    
    public function destroy($id)
    {
        $livrePrestation = LivrePrestation::findOrFail($id);
        $livrePrestation->delete();

        return back()->with('success', 'Prestation annulée avec succès.');
    }
}
