<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Cheval;
use App\Models\LivrePrestation;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Afficher la liste des utilisateurs
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('prenom')) {
            $query->where('prenom', 'like', '%' . $request->prenom . '%');
        }

        if ($request->filled('age')) {
            $query->whereRaw('TIMESTAMPDIFF(YEAR, date_de_naissance, CURDATE()) = ?', [$request->age]);
        }

        if ($request->filled('telephone')) {
            $query->where('telephone', 'like', '%' . $request->telephone . '%');
        }

        $users = $query->paginate(10);

        return view('users.index', compact('users'));
    }
    

    // Afficher le formulaire pour créer un nouvel utilisateur
    public function create()
    {
        return view('users.create');
    }

    // Stocker un nouvel utilisateur dans la base de données
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'date_de_naissance' => 'required|date',
            'telephone' => 'nullable|string|max:15',
            'adresse' => 'nullable|string|max:255',
            'email_de_facturation' => 'nullable|string|email|max:255',
            'type_client' => ['required', Rule::in(['Gérant', 'Client', 'Moniteur', 'Vétérinaire', 'Maréchal'])],
        ]);

        User::create([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'date_de_naissance' => $request->date_de_naissance,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'email_de_facturation' => $request->email_de_facturation,
            'type_client' => $request->type_client,
        ]);

        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès');
    }

    // Afficher les détails d'un utilisateur
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // Afficher le formulaire pour modifier un utilisateur
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Mettre à jour un utilisateur dans la base de données
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'date_de_naissance' => 'required|date',
            'telephone' => 'nullable|string|max:15',
            'adresse' => 'nullable|string|max:255',
            'email_de_facturation' => 'nullable|string|email|max:255',
            'type_client' => ['required', Rule::in(['Gérant', 'Client', 'Moniteur', 'Vétérinaire', 'Maréchal'])],
        ]);

        $user->update([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'date_de_naissance' => $request->date_de_naissance,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'email_de_facturation' => $request->email_de_facturation,
            'type_client' => $request->type_client,
        ]);

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès');
    }

    // Supprimer un utilisateur de la base de données
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès');
    }


    public function facturation(Request $request, $userId)
    {
        // Récupérer l'utilisateur
        $user = User::findOrFail($userId);
    
        // Récupérer les chevaux appartenant à cet utilisateur
        $chevaux = Cheval::where('user_id', $userId)->get();
    
        $chevalIds = $chevaux->pluck('id');
    
        // Construire la requête pour récupérer les prestations liées à ces chevaux
        $query = LivrePrestation::whereIn('cheval_id', $chevalIds)
            ->with('prestation', 'cheval'); // Charger les relations pour obtenir les détails
    
        // Appliquer le filtre par date de début, si présent
        if ($request->input('date_debut')) {
            $query->where('date_prestation', '>=', $request->input('date_debut'));
        }
    
        // Appliquer le filtre par date de fin, si présent
        if ($request->input('date_fin')) {
            $query->where('date_prestation', '<=', $request->input('date_fin'));
        }
    
        // Appliquer le filtre par type de prestation, si présent
        if ($request->input('type_prestation')) {
            $query->whereHas('prestation', function ($query) use ($request) {
                $query->where('type', $request->input('type_prestation'));
            });
        }
    
        // Exécuter la requête pour récupérer les prestations
        $prestations = $query->get();
    
        // Récupérer tous les types de prestations disponibles pour le filtre
        $types_prestations = \App\Models\Prestation::distinct()->pluck('type');
    
        // Passer les données à la vue
        return view('users.facturation', compact('user', 'prestations', 'types_prestations'));
    }
    

    

  
    
    public function generateInvoice(Request $request, $userId)
    {
        // Récupérer l'utilisateur
        $user = User::findOrFail($userId);
    
        // Récupérer les chevaux appartenant à cet utilisateur
        $chevaux = Cheval::where('user_id', $userId)->get();
    
        $chevalIds = $chevaux->pluck('id');
    
        // Construire la requête pour récupérer les prestations liées à ces chevaux
        $query = LivrePrestation::whereIn('cheval_id', $chevalIds)
            ->with('prestation', 'cheval'); // Charger les relations pour obtenir les détails
    
        // Appliquer le filtre par date de début, si présent
        if ($request->input('date_debut')) {
            $query->where('date_prestation', '>=', $request->input('date_debut'));
        }
    
        // Appliquer le filtre par date de fin, si présent
        if ($request->input('date_fin')) {
            $query->where('date_prestation', '<=', $request->input('date_fin'));
        }
    
        // Appliquer le filtre par type de prestation, si présent
        if ($request->input('type_prestation')) {
            $query->whereHas('prestation', function ($query) use ($request) {
                $query->where('type', $request->input('type_prestation'));
            });
        }
    
        // Exécuter la requête pour récupérer les prestations
        $prestations = $query->get();
    
        // Créer un nouvel objet PhpWord
        $phpWord = new PhpWord();
    
        // Ajouter une nouvelle section
        $section = $phpWord->addSection();
    
        // Ajouter un titre à la facture
        $section->addText("Facture", ['bold' => true, 'size' => 16], ['align' => 'center']);
    
        // Ajouter les informations du client
        $section->addText("Client : " . $user->name);
        $section->addText("Société : Nom de la société");
        $section->addTextBreak(1); // Ajouter un saut de ligne
    
        // Ajouter un titre pour la liste des prestations
        $section->addText("Détails des prestations :", ['bold' => true, 'size' => 14]);
        $section->addTextBreak(1); // Ajouter un saut de ligne
    
        // Ajouter les lignes de prestations
        $totalHT = 0;
        $totalTTC = 0;
    
        foreach ($prestations as $prestation) {
            $prixHT = $prestation->prestation->prix;
            $tva = $prestation->prestation->tva / 100;
            $prixTTC = $prixHT * (1 + $tva);
    
            $totalHT += $prixHT;
            $totalTTC += $prixTTC;
    
            $datePrestation = \Carbon\Carbon::parse($prestation->date_prestation)->format('d/m/Y');
    
            $section->addText("Prestation : {$prestation->prestation->nom} | Date : {$datePrestation} | Prix HT : " . number_format($prixHT, 2) . " € | Prix TTC : " . number_format($prixTTC, 2) . " €");
        }
    
        // Ajouter les totaux
        $section->addTextBreak(1); // Ajouter un saut de ligne
        $section->addText("Total HT : " . number_format($totalHT, 2) . " €");
        $section->addText("Total TTC : " . number_format($totalTTC, 2) . " €");
    
        // Générer le fichier Word
        $fileName = 'facture_' . $user->name . '_' . date('Y_m_d') . '.docx';
        $tempFile = storage_path($fileName);
    
        $phpWord->save($tempFile, 'Word2007', true);
    
        // Télécharger le fichier Word
        return response()->download($tempFile)->deleteFileAfterSend(true);
    }
    

    
}
