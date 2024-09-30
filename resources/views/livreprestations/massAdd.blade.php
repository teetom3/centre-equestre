@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/livreprestations/massAdd.css') }}">
@endpush

@section('content')
    <div class="container">
        <h1 class="page-title text-center">Ajout de Prestation multiple</h1>

        <!-- Formulaire de filtrage -->
        <form method="GET" action="{{ route('livre-prestations.mass-add') }}" class="mb-4 filter-form">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="cheval_nom" class="form-control" placeholder="Nom du cheval" value="{{ request('cheval_nom') }}">
                </div>
                <div class="col-md-4">
                    <select name="proprietaire_id" class="form-control">
                        <option value="">-- Filtrer par propriétaire --</option>
                        @foreach($proprietaires as $proprietaire)
                            <option value="{{ $proprietaire->id }}" {{ request('proprietaire_id') == $proprietaire->id ? 'selected' : '' }}>{{ $proprietaire->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
            <select name="pension" class="form-control">
                <option value="">-- Filtrer par type de pension --</option>
                <option value="pension 1" {{ request('pension') == 'pension 1' ? 'selected' : '' }}>Pension 1</option>
                <option value="pension 2" {{ request('pension') == 'pension 2' ? 'selected' : '' }}>Pension 2</option>
                <option value="pension 3" {{ request('pension') == 'pension 3' ? 'selected' : '' }}>Pension 3</option>
                <option value="pension 4" {{ request('pension') == 'pension 4' ? 'selected' : '' }}>Pension 4</option>
            </select>
        </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                </div>
            </div>
        </form>

        <!-- Formulaire d'ajout en masse -->
        <form action="{{ route('livre-prestations.mass-add.store') }}" method="POST">
            @csrf

            <!-- Tableau des chevaux -->
            <div class="table-container">
                <table class="table cheval-table">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="select-all"> <!-- Case pour sélectionner tous les chevaux filtrés -->
                            </th>
                            <th>Nom du Cheval</th>
                            <th>Propriétaire</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chevaux as $cheval)
                            <tr>
                                <td>
                                    <input type="checkbox" name="chevaux[]" value="{{ $cheval->id }}" class="cheval-checkbox">
                                </td>
                                <td>{{ $cheval->nom }}</td>
                                <td>{{ $cheval->user->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Sélection des prestations et date -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <label for="prestations">Prestations</label>
                    <select name="prestations[]" class="form-control" multiple required>
                        @foreach($prestations as $prestation)
                            <option value="{{ $prestation->id }}">{{ $prestation->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="date_prestation">Date de la Prestation</label>
                    <input type="date" name="date_prestation" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-success mt-4">Affecter les Prestations</button>
        </form>
    </div>

    <!-- Script pour sélectionner/désélectionner tous les chevaux -->
    <script>
        document.getElementById('select-all').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.cheval-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    </script>
@endsection
