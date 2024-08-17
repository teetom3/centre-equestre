@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Modifier l'événement : {{ $evenement->nom }}</h1>
        <form action="{{ route('evenements.update', $evenement->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="nom">Nom de l'événement</label>
                <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom', $evenement->nom) }}" required>
            </div>
            <div class="form-group">
                <label for="type">Type d'événement</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="Cours" {{ $evenement->type == 'Cours' ? 'selected' : '' }}>Cours</option>
                    <option value="Compétition" {{ $evenement->type == 'Compétition' ? 'selected' : '' }}>Compétition</option>
                    <option value="Repas" {{ $evenement->type == 'Repas' ? 'selected' : '' }}>Repas</option>
                </select>
            </div>
            <div class="form-group">
                <label for="nombre_de_place">Nombre de places</label>
                <input type="number" name="nombre_de_place" id="nombre_de_place" class="form-control" value="{{ old('nombre_de_place', $evenement->nombre_de_place) }}" required>
            </div>
            <div class="form-group">
                <label for="image_de_presentation">Image de présentation</label>
                <input type="file" name="image_de_presentation" id="image_de_presentation" class="form-control-file">
                @if($evenement->image_de_presentation)
                    <p>Image actuelle : <img src="{{ asset('images/' . $evenement->image_de_presentation) }}" alt="{{ $evenement->nom }}" width="100"></p>
                @endif
            </div>
            <button type="submit" class="btn btn-primary mt-3">Mettre à jour</button>
        </form>
    </div>
@endsection
