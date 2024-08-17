<?php

namespace App\Http\Controllers;

use App\Models\Cheval;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Prestation;
use App\Models\LivrePrestation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ChevalController extends Controller
{
    // Afficher la liste des chevaux
    public function index()
    {
        // Pour les gérants, on affiche tous les chevaux
        $chevaux = Cheval::with('user')->paginate(10);

        return view('chevaux.index', compact('chevaux'));
    }

    // Afficher le formulaire pour créer un nouveau cheval
    public function create()
    {
        $users = User::all();
    return view('chevaux.create', compact('users'));
    }

    // Stocker un nouveau cheval dans la base de données
    public function store(Request $request)
{
    $request->validate([
        'nom' => 'required|string|max:255',
        'date_de_naissance' => 'required|date',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'poids' => 'required|numeric',
        'user_id' => 'nullable|exists:users,id',
    ]);

    $cheval = new Cheval;
    $cheval->nom = $request->nom;
    $cheval->date_de_naissance = $request->date_de_naissance;
    $cheval->poids = $request->poids;
    $cheval->user_id = $request->user_id; // Lier au user si un user_id est fourni

    if ($request->hasFile('photo')) {
        $imageName = time().'.'.$request->photo->extension();
        $request->photo->move(public_path('images'), $imageName);
        $cheval->photo = $imageName;
    }

    $cheval->save();

    return redirect()->route('chevaux.index')->with('success', 'Cheval créé avec succès');
}

    // Afficher les détails d'un cheval
    
    public function show($id, Request $request)
    {
        $cheval = Cheval::with('user')->findOrFail($id);

        // Récupérer toutes les prestations
        $prestations = Prestation::all();

        // Générer le calendrier de la semaine
        $jours = [];
        $startOfWeek = Carbon::now()->startOfWeek();
        for ($i = 0; $i < 7; $i++) {
            $jours[] = [
                'nom' => $startOfWeek->copy()->addDays($i)->format('l'),
                'date' => $startOfWeek->copy()->addDays($i)->format('Y-m-d'),
            ];
        }

        // Récupérer toutes les prestations liées au cheval
        $livreDesPrestations = LivrePrestation::where('cheval_id', $cheval->id)
            ->with('prestation')
            ->orderBy('date_prestation', 'desc')
            ->get();

        // Récupérer l'historique des prestations passées
        $historique = LivrePrestation::where('cheval_id', $cheval->id)
            ->where('date_prestation', '<', Carbon::now())
            ->with('prestation')
            ->orderBy('date_prestation', 'desc')
            ->get();

        // Filtrage des prestations de type "soins"
        $soins = LivrePrestation::where('cheval_id', $cheval->id)
    ->whereHas('prestation', function ($query) {
        $query->where('type', 'Soin'); // ou 'Soins', selon ce qui correspond
    })
    ->when(request('date_debut'), function ($query) {
        $query->where('date_prestation', '>=', request('date_debut'));
    })
    ->when(request('date_fin'), function ($query) {
        $query->where('date_prestation', '<=', request('date_fin'));
    })
    ->with('prestation')
    ->orderBy('date_prestation', 'desc')
    ->get();
       
            
        return view('chevaux.show', compact('cheval', 'prestations', 'jours', 'historique', 'livreDesPrestations', 'soins'));
    }


    // Afficher le formulaire pour modifier un cheval
    public function edit(Cheval $cheval)
    {
        $users = User::all();
    return view('chevaux.edit', compact('cheval', 'users'));
    }

    // Mettre à jour un cheval dans la base de données
    public function update(Request $request, Cheval $cheval)
{
    $request->validate([
        'nom' => 'required|string|max:255',
        'date_de_naissance' => 'required|date',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'poids' => 'required|numeric',
        'user_id' => 'nullable|exists:users,id',
    ]);

    $cheval->nom = $request->nom;
    $cheval->date_de_naissance = $request->date_de_naissance;
    $cheval->poids = $request->poids;
    $cheval->user_id = $request->user_id; // Mettre à jour l'association avec un utilisateur

    if ($request->hasFile('photo')) {
        $imageName = time().'.'.$request->photo->extension();
        $request->photo->move(public_path('images'), $imageName);
        $cheval->photo = $imageName;
    }

    $cheval->save();

    return redirect()->route('chevaux.index')->with('success', 'Cheval mis à jour avec succès');
}


    // Supprimer un cheval de la base de données
    public function destroy(Cheval $cheval)
    {
        if ($cheval->photo && file_exists(public_path('images/'.$cheval->photo))) {
            unlink(public_path('images/'.$cheval->photo));
        }

        $cheval->delete();
        return redirect()->route('chevaux.index')->with('success', 'Cheval supprimé avec succès');
    }
}
