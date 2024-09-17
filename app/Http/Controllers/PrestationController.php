<?php

namespace App\Http\Controllers;

use App\Models\Prestation;
use Illuminate\Http\Request;

class PrestationController extends Controller
{
    // Afficher la liste des prestations
    public function index()
    {
        $prestations = Prestation::all();
        return view('prestations.index', compact('prestations'));
    }

    // Afficher le formulaire pour créer une nouvelle prestation
    public function create()
    {
        return view('prestations.create');
    }

    // Stocker une nouvelle prestation dans la base de données
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string|in:Service,Produit,Soin,Vétérinaire,Marechal',
            'prix' => 'required|numeric',
            'tva' => 'required|numeric',
        ]);
    
        Prestation::create([
            'nom' => $request->nom,
            'type' => $request->type,
            'prix' => $request->prix,
            'tva' => $request->tva,
        ]);
    
        return redirect()->route('prestations.index')->with('success', 'Prestation créée avec succès');
    }

    // Afficher les détails d'une prestation
    public function show(Prestation $prestation)
    {
        return view('prestations.show', compact('prestation'));
    }

    // Afficher le formulaire pour modifier une prestation
    public function edit(Prestation $prestation)
    {
        return view('prestations.edit', compact('prestation'));
    }

    // Mettre à jour une prestation dans la base de données
    public function update(Request $request, Prestation $prestation)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string|in:Service,Produit,Soin,Vétérinaire,Marechal',
            'prix' => 'required|numeric',
            'tva' => 'required|numeric',
        ]);
    
        $prestation->update([
            'nom' => $request->nom,
            'type' => $request->type,
            'prix' => $request->prix,
            'tva' => $request->tva,
        ]);
    
        return redirect()->route('prestations.index')->with('success', 'Prestation mise à jour avec succès');
    }
    

    // Supprimer une prestation de la base de données
    public function destroy(Prestation $prestation)
    {
        $prestation->delete();
        return redirect()->route('prestations.index')->with('success', 'Prestation supprimée avec succès');
    }
}
