@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/chevaux/edit.css') }}">
@endpush



@section('content')
<div class="container">
    <h1 class="page-title">Modifier le cheval : {{ $cheval->nom }}</h1>

    <!-- Formulaire de modification du cheval -->
    <div class="card cheval-detail-card mb-4">
        <div class="card-body">
            <form action="{{ route('chevaux.update', $cheval->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label for="nom">Nom du cheval</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom', $cheval->nom) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="date_de_naissance">Date de naissance</label>
                    <input type="date" name="date_de_naissance" id="date_de_naissance" class="form-control" value="{{ old('date_de_naissance', $cheval->date_de_naissance) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="poids">Poids (kg)</label>
                    <input type="number" name="poids" id="poids" class="form-control" value="{{ old('poids', $cheval->poids) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="user_id">Propriétaire</label>
                    <select name="user_id" id="user_id" class="form-control">
                        <option value="">-- Aucun propriétaire --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $cheval->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} {{ $user->prenom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
    <label for="commentaire">Commentaire</label>
    <textarea name="commentaire" id="commentaire" class="form-control" rows="5">{{ old('commentaire', $cheval->commentaire) }}</textarea>
</div>

                <div class="form-group">
    <label for="pension">Pension</label>
    <select name="pension" id="pension" class="form-control">
        <option value="">-- Choisir une pension --</option>
        <option value="pension 1" {{ old('pension', $cheval->pension ?? '') == 'pension 1' ? 'selected' : '' }}>Pension 1</option>
        <option value="pension 2" {{ old('pension', $cheval->pension ?? '') == 'pension 2' ? 'selected' : '' }}>Pension 2</option>
        <option value="pension 3" {{ old('pension', $cheval->pension ?? '') == 'pension 3' ? 'selected' : '' }}>Pension 3</option>
        <option value="pension 4" {{ old('pension', $cheval->pension ?? '') == 'pension 4' ? 'selected' : '' }}>Pension 4</option>
    </select>
</div>

                <div class="form-group mb-3">
                    <label for="photo">Photo du cheval (optionnel)</label>
                    <input type="file" name="photo" id="photo" class="form-control">
                    @if($cheval->photo)
                        <img src="{{ asset('images/' . $cheval->photo) }}" class="img-fluid mt-2" alt="{{ $cheval->nom }}" width="150">
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Mettre à jour le cheval</button>
                <a href="{{ route('chevaux.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>
@endsection
