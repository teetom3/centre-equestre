@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Liste des chevaux</h1>

        <!-- Tableau des chevaux -->
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Date de naissance</th>
                    <th>Poids (kg)</th>
                    <th>Propriétaire</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($chevaux as $cheval)
                    <tr>
                        <td>{{ $cheval->nom }}</td>
                        <td>{{ \Carbon\Carbon::parse($cheval->date_de_naissance)->format('d/m/Y') }}</td>
                        <td>{{ $cheval->poids }}</td>
                        <td>{{ $cheval->user ? $cheval->user->name . ' ' . $cheval->user->prenom : 'Aucun' }}</td>
                        <td>
                            <a href="{{ route('chevaux.show', $cheval->id) }}" class="btn btn-info btn-sm">Voir</a>
                            <a href="{{ route('chevaux.edit', $cheval->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                            <form action="{{ route('chevaux.destroy', $cheval->id) }}" method="POST" style="display:inline;">
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
            {{ $chevaux->links() }}
        </div>
    </div>
@endsection
