@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/chevaux/create.css') }}">
@endpush

@section('content')
    <div class="container">
        <h1 class="page-title">Créer un nouveau cheval</h1>
        <form action="{{ route('chevaux.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="nom">Nom du cheval</label>
                <input type="text" name="nom" id="nom" class="form-input" value="{{ old('nom') }}" required>
            </div>

            <div class="form-group">
                <label for="date_de_naissance">Date de naissance</label>
                <input type="date" name="date_de_naissance" id="date_de_naissance" class="form-input" value="{{ old('date_de_naissance') }}" required>
            </div>

            <div class="form-group">
                <label for="poids">Poids (en kg)</label>
                <input type="number" name="poids" id="poids" class="form-input" value="{{ old('poids') }}" required>
            </div>

            <div class="form-group">
                <label for="photo">Photo du cheval (optionnel)</label>
                <input type="file" name="photo" id="photo" class="form-input">
            </div>
            <div class="form-group">
    <label for="commentaire">Commentaire</label>
    <textarea name="commentaire" id="commentaire" class="form-control" rows="5">{{ old('commentaire', $cheval->commentaire) }}</textarea>
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
            <div class="form-group">
                <label for="user_id">Propriétaire (optionnel)</label>
                <select name="user_id" id="user_id" class="form-input">
                    <option value="">Aucun</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} {{ $user->prenom }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-submit">Créer le cheval</button>
        </form>
    </div>
@endsection
