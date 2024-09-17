@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/dashboards/veterinaire.css') }}">
@endpush

@section('content')
<div class="container">
    <h1 class="page-title text-center">Tableau de Bord - Vétérinaire</h1>

    <!-- Widget pour les prestations de type Vétérinaire -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-5">

        @if($prestations->isEmpty())
            <p class="text-center">Aucune prestation de type Vétérinaire trouvée.</p>
        @else
            @foreach ($prestations as $prestation)
            <div class="widget bg-white rounded-lg shadow-lg p-4">
                <h2 class="widget-title text-xl font-semibold mb-3">{{ $prestation->prestation->nom }}</h2>
                <div class="prestation-card p-3 mb-2 rounded {{ $prestation->etat == 'en attente' ? 'bg-warning' : 'bg-success' }}">
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
            </div>
            @endforeach
        @endif

    </div>
</div>
@endsection
