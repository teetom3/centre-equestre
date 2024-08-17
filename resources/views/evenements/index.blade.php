@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Liste des événements</h1>
        <a href="{{ route('evenements.create') }}" class="btn btn-primary mb-3">Créer un nouvel événement</a>
        @if ($evenements->isEmpty())
            <p>Aucun événement disponible.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Type</th>
                        <th>Nombre de places</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($evenements as $evenement)
                        <tr>
                            <td>{{ $evenement->nom }}</td>
                            <td>{{ $evenement->type }}</td>
                            <td>{{ $evenement->nombre_de_place }}</td>
                            <td>
                                @if($evenement->image_de_presentation)
                                    <img src="{{ asset('images/' . $evenement->image_de_presentation) }}" alt="{{ $evenement->nom }}" width="100">
                                @else
                                    Pas d'image
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('evenements.show', $evenement->id) }}" class="btn btn-info btn-sm">Voir</a>
                                <a href="{{ route('evenements.edit', $evenement->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                                <form action="{{ route('evenements.destroy', $evenement->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
