@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/prestations/edit.css') }}">
@endpush


@section('content')
    <div class="container">
        <h1>Modifier la prestation</h1>
        <form action="{{ route('prestations.update', $prestation->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="nom">Nom de la prestation</label>
                <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom', $prestation->nom) }}" required>
            </div>

            <div class="form-group">
                <label for="type">Type de prestation</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="Service" {{ $prestation->type == 'Service' ? 'selected' : '' }}>Service</option>
                    <option value="Produit" {{ $prestation->type == 'Produit' ? 'selected' : '' }}>Produit</option>
                    <option value="Soin" {{ $prestation->type == 'Soin' ? 'selected' : '' }}>Soin</option>
                </select>
            </div>

            <div class="form-group">
                <label for="prix">Prix</label>
                <input type="number" name="prix" id="prix" class="form-control" value="{{ old('prix', $prestation->prix) }}" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="tva">TVA (en %)</label>
                <input type="number" name="tva" id="tva" class="form-control" value="{{ old('tva', $prestation->tva) }}" step="0.01" required>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Mettre à jour la prestation</button>
        </form>
    </div>
@endsection

