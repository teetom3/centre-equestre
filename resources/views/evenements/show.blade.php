@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/evenements/show.css') }}">
@endpush

@section('content')
    <div class="container">
        <h1 class="page-title">{{ $evenement->nom }}</h1>

        <div class="card mb-4">
            <div class="card-body">
                <p><strong>Type :</strong> {{ $evenement->type }}</p>
                <p><strong>Nombre de places :</strong> {{ $evenement->nombre_de_place }}</p>
                @if($evenement->image_de_presentation)
                    <img src="{{ asset('images/' . $evenement->image_de_presentation) }}" alt="{{ $evenement->nom }}" class="img-fluid event-image rounded">
                @else
                    <p>Pas d'image disponible.</p>
                @endif
            </div>
        </div>

        @if(Auth::user()->type_client === 'Gérant' || Auth::user()->isAdmin())
            <div class="action-buttons">
                <a href="{{ route('evenements.edit', $evenement->id) }}" class="btn btn-warning">Modifier</a>
                <form action="{{ route('evenements.destroy', $evenement->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
                <a href="{{ route('evenements.index') }}" class="btn btn-secondary">Retour à la liste des événements</a>
            </div>
        @endif

        <!-- Formulaire pour inscrire un utilisateur -->
        <div class="mt-4">
            <h2>Inscrire un utilisateur</h2>
            <form action="{{ route('evenements.inscrire', $evenement->id) }}" method="POST">
                @csrf

                @if(Auth::user()->type_client === 'Gérant' || Auth::user()->isAdmin())
                    <div class="form-group">
                        <label for="user_id">Utilisateur</label>
                        <select name="user_id" id="user_id" class="form-control" required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="form-group">
                        <label for="user_id">Utilisateur</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    </div>
                @endif

                <!-- Sélection de cheval -->
                <div class="form-group">
                    <label for="cheval_id">Cheval (facultatif)</label>
                    <select name="cheval_id" id="cheval_id" class="form-control">
                        <option value="">-- Choisir un cheval --</option>
                        @if(Auth::user()->type_client === 'Gérant' || Auth::user()->isAdmin())
                            @foreach($chevaux as $cheval)
                                <option value="{{ $cheval->id }}">{{ $cheval->nom }} ({{ $cheval->user->name }})</option>
                            @endforeach
                        @else
                            @foreach($chevaux as $cheval)
                                @if($cheval->user_id === Auth::user()->id)
                                    <option value="{{ $cheval->id }}">{{ $cheval->nom }}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>

                @if(Auth::user()->type_client === 'Gérant' || Auth::user()->isAdmin())
                    <div class="form-group">
                        <label for="prestation_id">Attribuer une prestation (facultatif)</label>
                        <select name="prestation_id" id="prestation_id" class="form-control">
                            <option value="">-- Choisir une prestation --</option>
                            @foreach($prestations as $prestation)
                                <option value="{{ $prestation->id }}">{{ $prestation->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <button type="submit" class="btn btn-primary mt-3">Inscrire</button>
            </form>
        </div>

        <!-- Liste des inscrits -->
        <div class="mt-5">
            <h2>Liste des inscrits</h2>
            @if ($inscriptions->isEmpty())
                <p>Aucune inscription enregistrée.</p>
            @else
                <div class="table-container">
                    <table class="table inscriptions-table">
                        <thead>
                            <tr>
                                <th>Nom de l'utilisateur</th>
                                <th>Nom du cheval</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inscriptions as $inscription)
                                <tr>
                                    <td>{{ $inscription->user->name }}</td>
                                    <td>{{ $inscription->cheval ? $inscription->cheval->nom : 'Aucun cheval' }}</td>
                                    <td>
                                        @if(Auth::user()->type_client === 'Gérant' || Auth::user()->isAdmin())
                                            <form action="{{ route('inscriptions.destroy', $inscription->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
