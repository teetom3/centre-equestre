<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
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
}
