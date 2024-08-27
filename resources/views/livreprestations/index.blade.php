@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Liste des prestations (Livre des Prestations)</h1>

    <!-- Barre de filtre -->
    <form method="GET" action="{{ route('livreprestations.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <select name="cheval_id" class="form-control">
                    <option value="">-- Filtrer par cheval --</option>
                    @foreach($chevaux as $cheval)
                        <option value="{{ $cheval->id }}" {{ request('cheval_id') == $cheval->id ? 'selected' : '' }}>{{ $cheval->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <input type="text" name="nom_prestation" class="form-control" value="{{ request('nom_prestation') }}" placeholder="Filtrer par nom de prestation">
            </div>

            <div class="col-md-4">
                <input type="date" name="date_prestation" class="form-control" value="{{ request('date_prestation') }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Appliquer les filtres</button>
    </form>

    <!-- Tableau des prestations -->
    @if($prestations->isEmpty())
        <p>Aucune prestation trouvée.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Nom de la prestation</th>
                    <th>Cheval</th>
                    <th>Prix</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prestations as $prestation)
                    @php
                        // Comparer directement avec la date formatée 'Y-m-d'
                        $isToday = $prestation->date_prestation == $today;
                    @endphp
                    <tr class="{{ $isToday ? 'bg-warning' : '' }}">
                        <td>{{ \Carbon\Carbon::parse($prestation->date_prestation)->format('d/m/Y') }}</td>
                        <td>{{ $prestation->prestation->nom }}</td>
                        <td>{{ $prestation->cheval->nom }}</td>
                        <td>{{ number_format($prestation->prestation->prix, 2) }} €</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
