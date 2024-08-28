@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/prestations/index.css') }}">
@endpush
@section('content')
    <div class="container">
        <h1>Liste des prestations</h1>
        <a href="{{ route('prestations.create') }}" class="btn btn-primary mb-3">Créer une nouvelle prestation</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Type</th>
                    <th>Prix</th>
                    <th>TVA</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($prestations as $prestation)
                    <tr>
                        <td>{{ $prestation->nom }}</td>
                        <td>{{ $prestation->type }}</td>
                        <td>{{ $prestation->prix }} €</td>
                        <td>{{ $prestation->tva }} %</td>
                        <td>
                            <a href="{{ route('prestations.show', $prestation->id) }}" class="btn btn-info">Voir</a>
                            <a href="{{ route('prestations.edit', $prestation->id) }}" class="btn btn-warning">Modifier</a>
                            <form action="{{ route('prestations.destroy', $prestation->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
