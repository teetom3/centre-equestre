@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/dashboards/gerant.css') }}">
@endpush

@section('content')
<div class="container">
    <h1 class="page-title text-center">Tableau de Bord - Gérant</h1>

    <!-- Widgets pour les prestations, groupées par type -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-5">

        @foreach ($prestationsParType as $type => $prestations)
        <div class="widget bg-white rounded-lg shadow-lg p-4">
            <h2 class="widget-title text-xl font-semibold mb-3">{{ $type }}</h2>
            <div class="overflow-y-auto max-h-48 prestations-list">
                @foreach ($prestations as $prestation)
                <div class="prestation-card p-3 mb-2 rounded {{ $prestation->etat == 'en attente' ? 'bg-warning' : 'bg-success' }}">
                    <p><strong>Nom :</strong> {{ $prestation->prestation->nom }}</p>
                    <p><strong>Cheval :</strong> {{ $prestation->cheval->nom }}</p>
                    <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($prestation->date_prestation)->format('d/m/Y') }}</p>
                    <p><strong>Statut :</strong> {{ $prestation->etat }}</p>

                    @if($prestation->etat == 'en attente')
                    <form action="{{ route('livreprestation.changeState', $prestation->id) }}" method="POST" class="mt-2">
                        @csrf
                        <button name="etat" type="submit" class="btn btn-success btn-sm" value="validé">Valider</button>
                    </form>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

    </div>

    <!-- Section des événements -->
    <div class="mt-10">
        <h2 class="page-title text-center">Événements à venir</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-5">
            @foreach ($evenements as $evenement)
            <div class="widget bg-white rounded-lg shadow-lg p-4">
                <h3 class="widget-title text-xl font-semibold">{{ $evenement->nom }}</h3>
                <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($evenement->date)->format('d/m/Y') }}</p>
                <p><strong>Type :</strong> {{ $evenement->type }}</p>

                <h4 class="text-lg mt-3">Inscrits :</h4>
                <ul class="list-inscrits">
                    @foreach ($evenement->inscriptions as $inscription)
                        <li>{{ $inscription->user->name }} - {{ $inscription->cheval ? $inscription->cheval->nom : 'Sans cheval' }}</li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
