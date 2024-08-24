@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="page-title">Modifier l'utilisateur : {{ $user->name }}</h1>

    <!-- Formulaire de modification de l'utilisateur -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label for="name">Nom</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" class="form-control" value="{{ old('prenom', $user->prenom) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="password">Mot de passe (laisser vide pour conserver le mot de passe actuel)</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="password_confirmation">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="date_de_naissance">Date de naissance</label>
                    <input type="date" name="date_de_naissance" id="date_de_naissance" class="form-control" value="{{ old('date_de_naissance', $user->date_de_naissance) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="telephone">Téléphone (optionnel)</label>
                    <input type="text" name="telephone" id="telephone" class="form-control" value="{{ old('telephone', $user->telephone) }}">
                </div>

                <div class="form-group mb-3">
                    <label for="adresse">Adresse (optionnel)</label>
                    <input type="text" name="adresse" id="adresse" class="form-control" value="{{ old('adresse', $user->adresse) }}">
                </div>

                <div class="form-group mb-3">
                    <label for="email_de_facturation">Email de facturation (optionnel)</label>
                    <input type="email" name="email_de_facturation" id="email_de_facturation" class="form-control" value="{{ old('email_de_facturation', $user->email_de_facturation) }}">
                </div>

                <div class="form-group mb-3">
                    <label for="type_client">Type de client</label>
                    <select name="type_client" id="type_client" class="form-control" required>
                        <option value="Gérant" {{ $user->type_client == 'Gérant' ? 'selected' : '' }}>Gérant</option>
                        <option value="Client" {{ $user->type_client == 'Client' ? 'selected' : '' }}>Client</option>
                        <option value="Moniteur" {{ $user->type_client == 'Moniteur' ? 'selected' : '' }}>Moniteur</option>
                        <option value="Vétérinaire" {{ $user->type_client == 'Vétérinaire' ? 'selected' : '' }}>Vétérinaire</option>
                        <option value="Maréchal" {{ $user->type_client == 'Maréchal' ? 'selected' : '' }}>Maréchal</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Mettre à jour l'utilisateur</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>
@endsection
