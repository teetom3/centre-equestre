<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;

class EvenementController extends Controller
{
    // Afficher la liste des événements
    public function index()
    {
        $evenements = Evenement::all();
        return view('evenements.index', compact('evenements'));
    }

    // Afficher le formulaire pour créer un nouvel événement
    public function create()
    {
        return view('evenements.create');
    }

    // Stocker un nouvel événement dans la base de données
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string|in:Cours,Compétition,Repas',
            'nombre_de_place' => 'required|integer',
            'image_de_presentation' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $evenement = new Evenement;
        $evenement->nom = $request->nom;
        $evenement->type = $request->type;
        $evenement->nombre_de_place = $request->nombre_de_place;

        if ($request->hasFile('image_de_presentation')) {
            $imageName = time().'.'.$request->image_de_presentation->extension();
            $request->image_de_presentation->move(public_path('images'), $imageName);
            $evenement->image_de_presentation = $imageName;
        }

        $evenement->save();

        return redirect()->route('evenements.index')->with('success', 'Événement créé avec succès');
    }

    // Afficher les détails d'un événement
    public function show(Evenement $evenement)
    {
        return view('evenements.show', compact('evenement'));
    }

    // Afficher le formulaire pour modifier un événement
    public function edit(Evenement $evenement)
    {
        return view('evenements.edit', compact('evenement'));
    }

    // Mettre à jour un événement dans la base de données
    public function update(Request $request, Evenement $evenement)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string|in:Cours,Compétition,Repas',
            'nombre_de_place' => 'required|integer',
            'image_de_presentation' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $evenement->nom = $request->nom;
        $evenement->type = $request->type;
        $evenement->nombre_de_place = $request->nombre_de_place;

        if ($request->hasFile('image_de_presentation')) {
            $imageName = time().'.'.$request->image_de_presentation->extension();
            $request->image_de_presentation->move(public_path('images'), $imageName);
            $evenement->image_de_presentation = $imageName;
        }

        $evenement->save();

        return redirect()->route('evenements.index')->with('success', 'Événement mis à jour avec succès');
    }

    // Supprimer un événement de la base de données
    public function destroy(Evenement $evenement)
    {
        if ($evenement->image_de_presentation && file_exists(public_path('images/'.$evenement->image_de_presentation))) {
            unlink(public_path('images/'.$evenement->image_de_presentation));
        }

        $evenement->delete();
        return redirect()->route('evenements.index')->with('success', 'Événement supprimé avec succès');
    }
}
