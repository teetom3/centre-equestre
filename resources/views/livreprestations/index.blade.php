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

    <div class="container">
    <h2>Historique des prestations passées</h2>
    @if ($historique->isEmpty())
        <p>Aucune prestation passée.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Nom de la prestation</th>
                    <th>Type</th>
                    <th>Prix</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($historique as $entry)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($entry->date_prestation)->format('d/m/Y') }}</td>
                        <td>{{ $entry->prestation->nom }}</td>
                        <td>{{ $entry->prestation->type }}</td>
                        <td>{{ $entry->prestation->prix }} €</td>
                        <td>
                            @if(Auth::user()->type_client === 'Gérant')
                                <form action="{{ route('livreprestation.destroy', $entry->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Annuler</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
</div>
@endsection
