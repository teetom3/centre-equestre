@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Mes Chevaux</h5>
                    <p class="card-text">Voir et gérer vos chevaux.</p>
                    <a href="{{ route('chevaux.index') }}" class="btn btn-primary">Accéder</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Mes Événements</h5>
                    <p class="card-text">Voir et participer aux événements.</p>
                    <a href="{{ route('evenements.index') }}" class="btn btn-primary">Accéder</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Ma Facturation</h5>
                    <p class="card-text">Voir les factures de vos chevaux.</p>
                    <a href="{{ route('users.facturation', auth()->user()->id) }}" class="btn btn-primary">Accéder</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
