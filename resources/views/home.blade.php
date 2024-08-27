@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/home/home.css') }}">
@endpush

@section('content')
<div class="container">
    <div class="card">
        <img src="{{ asset('images/chevaux.webp') }}" alt="Mes Chevaux">
        <div class="card-body">
            <h5 class="card-title">Mes Chevaux</h5>
            <p class="card-text">Voir et gérer vos chevaux.</p>
            <a href="{{ route('chevaux.index') }}" class="btn btn-primary">Accéder</a>
        </div>
    </div>
    <div class="card">
        <img src="{{ asset('images/evenements.webp') }}" alt="Mes Événements">
        <div class="card-body">
            <h5 class="card-title">Mes Événements</h5>
            <p class="card-text">Voir et participer aux événements.</p>
            <a href="{{ route('evenements.index') }}" class="btn btn-primary">Accéder</a>
        </div>
    </div>
    <div class="card">
        <img src="{{ asset('images/facturation.webp') }}" alt="Ma Facturation">
        <div class="card-body">
            <h5 class="card-title">Ma Facturation</h5>
            <p class="card-text">Voir les factures de vos chevaux.</p>
            <a href="{{ route('users.facturation', auth()->user()->id) }}" class="btn btn-primary">Accéder</a>
        </div>
    </div>
</div>
@endsection
