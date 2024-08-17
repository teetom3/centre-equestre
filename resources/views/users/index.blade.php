@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Gérer les utilisateurs</h1>

        <!-- Formulaire de filtrage -->
        <form action="{{ route('users.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Nom" value="{{ request('name') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="prenom" class="form-control" placeholder="Prénom" value="{{ request('prenom') }}">
                </div>
                <div class="col-md-2">
                    <input type="number" name="age" class="form-control" placeholder="Âge" value="{{ request('age') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="telephone" class="form-control" placeholder="Téléphone" value="{{ request('telephone') }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                </div>
            </div>
        </form>

        <!-- Tableau des utilisateurs -->
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Âge</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->prenom }}</td>
                        <td>{{ \Carbon\Carbon::parse($user->date_de_naissance)->age }} ans</td>
                        <td>{{ $user->telephone }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                            <a href="{{ route('users.facturation', $user->id) }}" class="btn btn-info btn-sm">Facturation</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    </div>
@endsection
