@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/evenements/index.css') }}">
@endpush

@section('content')
    <div class="container">
        <h1 class="page-title text-center">Gestion des Événements</h1>
        <div class="text-center mb-4">
            <a href="{{ route('evenements.create') }}" class="btn btn-primary btn-create-event">Créer un nouvel événement</a>
        </div>
        
        @if ($evenements->isEmpty())
            <p class="no-events text-center">Aucun événement disponible pour le moment.</p>
        @else
            <div class="table-container">
                <table class="table events-table table-hover">
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
                                        <img src="{{ asset('images/' . $evenement->image_de_presentation) }}" alt="{{ $evenement->nom }}" class="event-image rounded">
                                    @else
                                        <span class="no-image text-muted">Pas d'image</span>
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
            </div>
        @endif
    </div>
@endsection
