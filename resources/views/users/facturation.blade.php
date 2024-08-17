@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Facturation pour {{ $user->name }}</h1>

        <!-- Formulaire de filtrage -->
        <form method="GET" action="{{ route('users.facturation', $user->id) }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}" placeholder="Date de début">
                </div>
                <div class="col-md-4">
                    <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}" placeholder="Date de fin">
                </div>
                <div class="col-md-4">
                    <select name="type_prestation" class="form-control">
                        <option value="">-- Type de prestation --</option>
                        @foreach($types_prestations as $type)
                            <option value="{{ $type }}" {{ request('type_prestation') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Filtrer</button>
        </form>

        <a href="{{ route('users.generateInvoice', ['userId' => $user->id, 'date_debut' => request('date_debut'), 'date_fin' => request('date_fin'), 'type_prestation' => request('type_prestation')]) }}" class="btn btn-success mb-4">Générer la facture</a>

        <!-- Tableau des prestations -->
        @if($prestations->isEmpty())
            <p>Aucune prestation trouvée pour cet utilisateur.</p>
        @else
            @php
                $totalHT = 0;
                $totalTTC = 0;
            @endphp
            @foreach($prestations->groupBy('cheval_id') as $chevalId => $prestationGroupe)
                <h2>{{ $prestationGroupe->first()->cheval->nom }}</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom de la prestation</th>
                            <th>Date</th>
                            <th>Prix HT</th>
                            <th>Prix TTC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prestationGroupe as $prestation)
                            @php
                                $prixHT = $prestation->prestation->prix;
                                $tva = $prestation->prestation->tva / 100; // Convertir la TVA en pourcentage
                                $prixTTC = $prixHT * (1 + $tva);
                                $totalHT += $prixHT;
                                $totalTTC += $prixTTC;
                            @endphp
                            <tr>
                                <td>{{ $prestation->prestation->nom }}</td>
                                <td>{{ \Carbon\Carbon::parse($prestation->date_prestation)->format('d/m/Y') }}</td>
                                <td>{{ number_format($prixHT, 2) }} €</td>
                                <td>{{ number_format($prixTTC, 2) }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach

            <!-- Affichage des totaux -->
            <div class="mt-4">
                <h4>Total HT: {{ number_format($totalHT, 2) }} €</h4>
                <h4>Total TTC: {{ number_format($totalTTC, 2) }} €</h4>
            </div>
        @endif
    </div>
@endsection
