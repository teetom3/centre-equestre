@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="page-title">Créer un nouvel événement</h1>
        <form action="{{ route('evenements.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="nom">Nom de l'événement</label>
                <input type="text" name="nom" id="nom" class="form-input" value="{{ old('nom') }}" required>
            </div>
            <div class="form-group">
                <label for="type">Type d'événement</label>
                <select name="type" id="type" class="form-input" required>
                    <option value="Cours">Cours</option>
                    <option value="Compétition">Compétition</option>
                    <option value="Repas">Repas</option>
                </select>
            </div>
            <div class="form-group">
                <label for="nombre_de_place">Nombre de places</label>
                <input type="number" name="nombre_de_place" id="nombre_de_place" class="form-input" value="{{ old('nombre_de_place') }}" required>
            </div>
            <div class="form-group">
                <label for="image_de_presentation">Image de présentation</label>
                <input type="file" name="image_de_presentation" id="image_de_presentation" class="form-input-file">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Créer</button>
        </form>
    </div>
@endsection
