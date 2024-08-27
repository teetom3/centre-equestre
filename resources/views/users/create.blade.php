@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/users/create.css') }}">
@endpush


@section('content')
    <div class="container">
        <h1>Créer un nouvel utilisateur</h1>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" name="prenom" id="prenom" class="form-control" value="{{ old('prenom') }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" class="form-control" required>
                <input type="checkbox" id="show_password" onclick="togglePassword()"> Afficher le mot de passe
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="date_de_naissance">Date de naissance</label>
                <input type="date" name="date_de_naissance" id="date_de_naissance" class="form-control" value="{{ old('date_de_naissance') }}" required>
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone (optionnel)</label>
                <input type="text" name="telephone" id="telephone" class="form-control" value="{{ old('telephone') }}">
            </div>

            <div class="form-group">
                <label for="adresse">Adresse (optionnel)</label>
                <input type="text" name="adresse" id="adresse" class="form-control" value="{{ old('adresse') }}">
            </div>

            <div class="form-group">
                <label for="email_de_facturation">Email de facturation (optionnel)</label>
                <input type="email" name="email_de_facturation" id="email_de_facturation" class="form-control" value="{{ old('email_de_facturation') }}">
            </div>

            <div class="form-group full-width">
                <label for="type_client">Type de client</label>
                <select name="type_client" id="type_client" class="form-control" required>
                    <option value="Gérant">Gérant</option>
                    <option value="Client">Client</option>
                    <option value="Moniteur">Moniteur</option>
                    <option value="Vétérinaire">Vétérinaire</option>
                    <option value="Maréchal">Maréchal</option>
                </select>
            </div>

            <div class="form-group full-width">
                <button type="submit" class="btn btn-primary mt-3">Créer l'utilisateur</button>
            </div>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const passwordConfirmField = document.getElementById('password_confirmation');
            const showPassword = document.getElementById('show_password');
            if (showPassword.checked) {
                passwordField.type = 'text';
                passwordConfirmField.type = 'text';
            } else {
                passwordField.type = 'password';
                passwordConfirmField.type = 'password';
            }
        }
    </script>
@endsection

