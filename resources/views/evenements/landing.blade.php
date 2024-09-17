@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/evenements/landing.css') }}">
@endpush

@section('content')
    <div class="container">
        <!-- Hero Text -->
        <div class="hero-text text-center">
            
        </div>

        <!-- Section des événements à venir -->
        <div class="event-section">
        <h1 class="display-4">Bienvenue à nos Événements</h1>
        <p class="lead">Découvrez tous les événements passionnants à venir et inscrivez-vous pour ne rien manquer.</p>
            <div class="row">
                @foreach ($evenements as $evenement)
                    <div class="col-md-6 mb-4">
                        <div class="card evenement-card shadow-sm">
                            <div class="card-img-top-container">
                                @if($evenement->image_de_presentation)
                                    <img src="{{ asset('images/' . $evenement->image_de_presentation) }}" class="card-img-top img-fluid rounded-top" alt="{{ $evenement->nom }}">
                                @else
                                    <img src="{{ asset('images/default_event.jpg') }}" class="card-img-top img-fluid rounded-top" alt="{{ $evenement->nom }}">
                                @endif
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-center">{{ $evenement->nom }}</h5>
                                <p class="card-text text-center">{{ $evenement->type }}</p>
                                <div class="text-center">
                                    <a href="{{ route('evenements.show', $evenement->id) }}" class="btn btn-primary">En savoir plus</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection


