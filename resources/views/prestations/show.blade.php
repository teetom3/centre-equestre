@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Détails de la prestation</h1>
        <div class="card">
            <div class="card-header">
                <h2>{{ $prestation->nom }}</h2>
            </div>
            <div class="card-body">
                <p><strong>Type : </strong>{{ $prestation->type }}</p>
                <p><strong>Prix : </strong>{{ $prestation->prix }} €</p>
                <p><strong>TVA : </strong>{{ $prestation->tva }} %</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('prestations.edit', $prestation->id) }}" class="btn btn-warning">Modifier</a>
                <form action="{{ route('prestations.destroy', $prestation->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
                <a href="{{ route('prestations.index') }}" class="btn btn-secondary">Retour à la liste</a>
            </div>
        </div>
    </div>
@endsection
