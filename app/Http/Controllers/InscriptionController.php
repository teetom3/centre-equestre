<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use App\Models\Evenement;
use App\Models\User;
use App\Models\Cheval;
use Illuminate\Http\Request;
use App\Models\LivrePrestation;



class InscriptionController extends Controller
{
    // Afficher la liste des inscriptions
    public function index()
    {
        $inscriptions = Inscription::with(['evenement', 'user', 'cheval'])->get();
        return view('inscriptions.index', compact('inscriptions'));
    }

    // Afficher le formulaire pour créer une nouvelle inscription
    public function create()
    {
        $evenements = Evenement::all();
        $users = User::all();
        $chevaux = Cheval::all();
        return view('inscriptions.create', compact('evenements', 'users', 'chevaux'));
    }

     // Inscrire un utilisateur à un événement depuis la vue de l'événement
     public function inscrire(Request $request, Evenement $evenement)
     {
         $request->validate([
             'user_id' => 'required|exists:users,id',
             'cheval_id' => 'nullable|exists:chevals,id',
             'prestation_id' => 'nullable|exists:prestations,id',
         ]);
 
         // Créer une nouvelle inscription
         $inscription = new Inscription([
             'evenement_id' => $evenement->id,
             'user_id' => $request->user_id,
             'cheval_id' => $request->cheval_id,
             'date_inscription' => now(),
         ]);
         $inscription->save();
 
         // Si une prestation est sélectionnée, l'ajouter au livre des prestations
         if ($request->prestation_id) {
             LivrePrestation::create([
                 'cheval_id' => $request->cheval_id,
                 'prestation_id' => $request->prestation_id,
                 'date_prestation' => now(),
             ]);
         }
 
         return redirect()->route('evenements.show', $evenement->id)->with('success', 'Utilisateur inscrit avec succès');
     }

    // Stocker une nouvelle inscription dans la base de données
    public function store(Request $request)
    {
        $request->validate([
            'evenement_id' => 'required|exists:evenements,id',
            'user_id' => 'nullable|exists:users,id',
            'cheval_id' => 'nullable|exists:chevals,id',
        ]);

        Inscription::create([
            'evenement_id' => $request->evenement_id,
            'user_id' => $request->user_id,
            'cheval_id' => $request->cheval_id,
            'date_inscription' => now(),
        ]);

        return redirect()->route('inscriptions.index')->with('success', 'Inscription créée avec succès');
    }

    // Afficher les détails d'une inscription
    public function show(Inscription $inscription)
    {
        return view('inscriptions.show', compact('inscription'));
    }

    // Afficher le formulaire pour modifier une inscription
    public function edit(Inscription $inscription)
    {
        $evenements = Evenement::all();
        $users = User::all();
        $chevaux = Cheval::all();
        return view('inscriptions.edit', compact('inscription', 'evenements', 'users', 'chevaux'));
    }

    // Mettre à jour une inscription dans la base de données
    public function update(Request $request, Inscription $inscription)
    {
        $request->validate([
            'evenement_id' => 'required|exists:evenements,id',
            'user_id' => 'nullable|exists:users,id',
            'cheval_id' => 'nullable|exists:chevals,id',
        ]);

        $inscription->update([
            'evenement_id' => $request->evenement_id,
            'user_id' => $request->user_id,
            'cheval_id' => $request->cheval_id,
        ]);

        return redirect()->route('inscriptions.index')->with('success', 'Inscription mise à jour avec succès');
    }

    // Supprimer une inscription de la base de données
    public function destroy(Inscription $inscription)
    {
        $evenementId = $inscription->evenement_id;
        $inscription->delete();
        
        return redirect()->route('evenements.show', $evenementId)->with('success', 'Inscription supprimée avec succès');
    }
}
