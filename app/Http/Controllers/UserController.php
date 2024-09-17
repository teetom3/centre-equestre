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

use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\Jc;

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
    

    

  
    
   // Générer la facture
public function generateInvoice(Request $request, $userId)
{
    // Récupérer l'utilisateur
    $user = User::findOrFail($userId);

    // Récupérer les chevaux appartenant à cet utilisateur
    $chevaux = Cheval::where('user_id', $userId)->get();
    $chevalIds = $chevaux->pluck('id');

    // Construire la requête pour récupérer les prestations liées à ces chevaux
    $query = LivrePrestation::whereIn('cheval_id', $chevalIds)
        ->with('prestation', 'cheval');

    // Appliquer les filtres de date
    if ($request->input('date_debut')) {
        $query->where('date_prestation', '>=', $request->input('date_debut'));
    }
    if ($request->input('date_fin')) {
        $query->where('date_prestation', '<=', $request->input('date_fin'));
    }
    if ($request->input('type_prestation')) {
        $query->whereHas('prestation', function ($query) use ($request) {
            $query->where('type', $request->input('type_prestation'));
        });
    }

    // Exécuter la requête pour récupérer les prestations
    $prestations = $query->get();

    // Générer un numéro de facture unique
    $numeroFacture = 'FAC-' . time(); // Numéro de facture basé sur le timestamp

    // Créer un nouvel objet PhpWord
    $phpWord = new PhpWord();

    // Ajouter une nouvelle section
    $section = $phpWord->addSection([
        'marginTop' => Converter::cmToTwip(1.5),
        'marginBottom' => Converter::cmToTwip(1.5),
        'marginLeft' => Converter::cmToTwip(2),
        'marginRight' => Converter::cmToTwip(2),
    ]);

    // Ajouter l'en-tête de la facture avec le logo et le numéro de facture
    $header = $section->addHeader();
    $header->addImage(public_path('images/logo.png'), [
        'width' => 100,
        'height' => 100,
        'align' => Jc::CENTER
    ]);
    $header->addText("FACTURE N° $numeroFacture", ['bold' => true, 'size' => 16], ['align' => 'right']);
    $header->addText(date('d/m/Y'), ['size' => 12], ['align' => 'right']);

    // Ajouter les informations du client
    $section->addText("Client : " . $user->name);
    $section->addText("Adresse : " . $user->adresse);
    $section->addTextBreak(1); // Ajouter un saut de ligne

    // Créer un tableau pour les prestations
    $table = $section->addTable([
        'borderSize' => 6,
        'borderColor' => '999999',
        'cellMargin' => 80
    ]);

    // Ajouter les en-têtes du tableau
    $table->addRow();
    $table->addCell(3000)->addText('Désignation', ['bold' => true]);
    $table->addCell(1000)->addText('Qte', ['bold' => true]);
    $table->addCell(2000)->addText('PU HT', ['bold' => true]);
    $table->addCell(2000)->addText('PU TTC', ['bold' => true]);
    $table->addCell(1000)->addText('TVA', ['bold' => true]);
    $table->addCell(2000)->addText('Total HT', ['bold' => true]);
    $table->addCell(2000)->addText('Total TTC', ['bold' => true]);

    // Variables pour les totaux
    $totalHT = 0;
    $totalTTC = 0;
    $totalTVA = 0;

    // Ajouter les lignes de prestations
    foreach ($prestations as $prestation) {
        $prixHT = $prestation->prestation->prix;
        $tva = $prestation->prestation->tva / 100;
        $prixTTC = $prixHT * (1 + $tva);
        $qte = 1; // Quantité par défaut, à modifier selon ton besoin

        // Ajouter la ligne de prestation dans le tableau
        $table->addRow();
        $table->addCell(3000)->addText($prestation->prestation->nom);
        $table->addCell(1000)->addText($qte);
        $table->addCell(2000)->addText(number_format($prixHT, 2) . " €");
        $table->addCell(2000)->addText(number_format($prixTTC, 2) . " €");
        $table->addCell(1000)->addText($prestation->prestation->tva . " %");
        $table->addCell(2000)->addText(number_format($prixHT * $qte, 2) . " €");
        $table->addCell(2000)->addText(number_format($prixTTC * $qte, 2) . " €");

        // Ajouter aux totaux
        $totalHT += $prixHT * $qte;
        $totalTTC += $prixTTC * $qte;
        $totalTVA += ($prixTTC - $prixHT) * $qte;
    }

    // Ajouter les totaux sous le tableau
    $section->addTextBreak(1); // Ajouter un saut de ligne
    $section->addText("Total HT : " . number_format($totalHT, 2) . " €");
    $section->addText("Total TVA : " . number_format($totalTVA, 2) . " €");
    $section->addText("Total TTC : " . number_format($totalTTC, 2) . " €");

    // Générer le fichier Word
    $fileName = 'facture_' . $user->name . '_' . date('Y_m_d') . '.docx';
    $tempFile = storage_path($fileName);

    $phpWord->save($tempFile, 'Word2007', true);

    // Télécharger le fichier Word
    return response()->download($tempFile)->deleteFileAfterSend(true);
}
    

    
}
