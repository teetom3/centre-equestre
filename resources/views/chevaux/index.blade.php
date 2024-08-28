



@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/chevaux/index.css') }}">
@endpush

@section('content')
<div class="container">
<a href="{{ route('chevaux.create') }}" class="btn btn-primary mb-3">Créer un nouveau cheval</a>
    <h1 class="page-title">Liste des chevaux</h1>

    <!-- Liste des chevaux sous forme de cartes -->
    <div class="grid-container">
        @foreach ($chevaux as $cheval)
            <div class="card cheval-card">
                <div class="card-img-container">
                    @if($cheval->photo)
                        <img src="{{ asset('images/' . $cheval->photo) }}" class="card-img-top" alt="{{ $cheval->nom }}">
                    @else
                        <img src="{{ asset('images/default_cheval.jpg') }}" class="card-img-top" alt="{{ $cheval->nom }}">
                    @endif
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $cheval->nom }}</h5>
                    <p class="card-text">Date de naissance : {{ \Carbon\Carbon::parse($cheval->date_de_naissance)->format('d/m/Y') }}</p>
                    <p class="card-text">Poids : {{ $cheval->poids }} kg</p>
                    <p class="card-text">Propriétaire : {{ $cheval->user ? $cheval->user->name . ' ' . $cheval->user->prenom : 'Aucun' }}</p>
                    <a href="{{ route('chevaux.show', $cheval->id) }}" class="btn btn-info btn-sm">Voir</a>
                    <a href="{{ route('chevaux.edit', $cheval->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                    <form action="{{ route('chevaux.destroy', $cheval->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $chevaux->links() }}
    </div>
</div>
@endsection



