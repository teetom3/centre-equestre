@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center mb-5">Événements à venir</h1>
        <div class="row">
            @foreach ($evenements as $evenement)
                <div class="col-md-6 mb-4">
                    <div class="card evenement-card" style="width: 100%;">
                        @if($evenement->image_de_presentation)
                            <img src="{{ asset('images/' . $evenement->image_de_presentation) }}" class="card-img-top img-fluid" alt="{{ $evenement->nom }}" style="height: 200px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/default_event.jpg') }}" class="card-img-top img-fluid" alt="{{ $evenement->nom }}" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $evenement->nom }}</h5>
                            <p class="card-text">{{ $evenement->type }}</p>
                            <a href="{{ route('inscriptions.create', $evenement->id) }}" class="btn btn-primary">En savoir plus</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .evenement-card {
            transition: transform 0.3s ease;
        }

        .evenement-card:hover {
            transform: translateY(-10px);
        }
    </style>
@endsection
