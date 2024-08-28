@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/prestations/create.css') }}">
@endpush

@section('content')
    <div class="container">
        <h1>Créer une nouvelle prestation</h1>
        <form action="{{ route('prestations.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nom">Nom de la prestation</label>
                <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom') }}" required>
            </div>

            <div class="form-group">
                <label for="type">Type de prestation</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="Service">Service</option>
                    <option value="Produit">Produit</option>
                    <option value="Soin">Soin</option>
                </select>
            </div>

            <div class="form-group">
                <label for="prix">Prix</label>
                <input type="number" name="prix" id="prix" class="form-control" value="{{ old('prix') }}" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="tva">TVA (en %)</label>
                <input type="number" name="tva" id="tva" class="form-control" value="{{ old('tva') }}" step="0.01" required>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Créer la prestation</button>
        </form>
    </div>
@endsection
