@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Tableau de bord Maréchal</h1>

        <!-- Tableau des prestations de type Maréchal -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Cheval</th>
                    <th>Prestation</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prestations as $prestation)
                    <tr class="{{ $prestation->etat == 'en attente' ? 'bg-warning' : 'bg-success' }}">
                        <td>{{ $prestation->cheval->nom }}</td>
                        <td>{{ $prestation->prestation->nom }}</td>
                        <td>{{ \Carbon\Carbon::parse($prestation->date_prestation)->format('d/m/Y') }}</td>
                        <td>{{ ucfirst($prestation->etat) }}</td>
                        <td>
                            @if($prestation->etat == 'en attente')
                                <form action="{{ route('livreprestation.changeState', $prestation->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="etat" value="validé">
                                    <button type="submit" class="btn btn-sm btn-success">Valider</button>
                                </form>
                            @else
                                <span>Validé</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
