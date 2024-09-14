@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="page-title">Tableau de Bord - Gérant</h1>

    <!-- Widgets pour les prestations, groupées par type -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        @foreach ($prestationsParType as $type => $prestations)
        <div class="bg-white rounded-lg shadow-md p-4">
            <h2 class="text-xl font-semibold mb-2">{{ $type }}</h2>
            <div class="overflow-y-auto max-h-48">
                @foreach ($prestations as $prestation)
                <div class="p-3 mb-2 rounded {{ $prestation->status == 'En attente' ? 'bg-yellow-200' : 'bg-green-200' }}">
                    <p><strong>Nom :</strong> {{ $prestation->prestation->nom }}</p>
                    <p><strong>Cheval :</strong> {{ $prestation->cheval->nom }}</p>
                    <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($prestation->date_prestation)->format('d/m/Y') }}</p>
                    <p><strong>Statut :</strong> {{ $prestation->etat }}</p>

                    <!-- Si la prestation est en attente, on peut la valider -->
                    @if($prestation->etat == 'en attente')
                        <form action="{{ route('livreprestation.changeState', $prestation->id) }}" method="POST">
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
</div>
@endsection
