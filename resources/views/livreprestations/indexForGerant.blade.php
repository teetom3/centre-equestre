@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/livreprestations/indexForGerant.css') }}">
@endpush

@section('content')
    <div class="container">
        <h1 class="page-title text-center">Livre des Prestations du Centre Équestre</h1>

        <!-- Formulaire de filtrage -->
        <form method="GET" action="{{ route('livre-prestations.index') }}" class="mb-4 filter-form">
            <div class="row">
                <div class="col-md-3">
                    <select name="prestation_id" class="form-control">
                        <option value="">-- Filtrer par prestation --</option>
                        @foreach($prestations as $prestation)
                            <option value="{{ $prestation->id }}" {{ request('prestation_id') == $prestation->id ? 'selected' : '' }}>{{ $prestation->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="cheval_id" class="form-control">
                        <option value="">-- Filtrer par cheval --</option>
                        @foreach($chevaux as $cheval)
                            <option value="{{ $cheval->id }}" {{ request('cheval_id') == $cheval->id ? 'selected' : '' }}>{{ $cheval->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="proprietaire_id" class="form-control">
                        <option value="">-- Filtrer par propriétaire --</option>
                        @foreach($proprietaires as $proprietaire)
                            <option value="{{ $proprietaire->id }}" {{ request('proprietaire_id') == $proprietaire->id ? 'selected' : '' }}>{{ $proprietaire->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="etat" class="form-control">
                        <option value="">-- Filtrer par état --</option>
                        <option value="en attente" {{ request('etat') == 'en attente' ? 'selected' : '' }}>En attente</option>
                        <option value="validé" {{ request('etat') == 'validé' ? 'selected' : '' }}>Validé</option>
                        <option value="facturé" {{ request('etat') == 'facturé' ? 'selected' : '' }}>Facturé</option>
                        <option value="payé" {{ request('etat') == 'payé' ? 'selected' : '' }}>Payé</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                </div>
            </div>
        </form>

        <!-- Tableau des prestations -->
        <div class="table-container">
            <table class="table prestations-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Nom de la prestation</th>
                        <th>Cheval</th>
                        <th>Propriétaire</th>
                        <th>État</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($livrePrestations as $livrePrestation)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($livrePrestation->date_prestation)->format('d/m/Y') }}</td>
                            <td>{{ $livrePrestation->prestation->nom }}</td>
                            <td>{{ $livrePrestation->cheval->nom }}</td>
                            <td>{{ $livrePrestation->cheval->user->name }}</td>
                            <td>{{ $livrePrestation->etat }}</td>
                            <td>
                            <form action="{{ route('livreprestation.changeState',  $livrePrestation->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <select name="etat" onchange="this.form.submit()">
                    <option value="en attente" {{ old('etat', $livrePrestation->etat) == 'en attente' ? 'selected' : '' }}>En attente</option>
        <option value="validé" {{ old('etat', $livrePrestation->etat) == 'validé' ? 'selected' : '' }}>Validé</option>
        <option value="facturé" {{ old('etat', $livrePrestation->etat) == 'facturé' ? 'selected' : '' }}>Facturé</option>
        <option value="paid" {{ old('etat', $livrePrestation->etat) == 'paid' ? 'selected' : '' }}>Payé</option>
    </select>
                </form>
                            </td>
                            <td>
                                <form action="{{ route('livreprestation.destroy', $livrePrestation->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Annuler</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
