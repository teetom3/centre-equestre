@extends('layouts.app')


@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/chevaux/show.css') }}">
@endpush



@section('content')
<div class="container">
    <h1 class="page-title">{{ $cheval->nom }}</h1>

    <div class="widget-grid">
        <!-- Détails du cheval -->
        <div class="card cheval-detail-card">
            <div class="card-body">
                <div class="cheval-info-grid">
                    <div class="cheval-image-container">
                        @if($cheval->photo)
                            <img src="{{ asset('images/' . $cheval->photo) }}" class="img-fluid cheval-image" alt="{{ $cheval->nom }}">
                        @else
                            <img src="{{ asset('images/default_cheval.jpg') }}" class="img-fluid cheval-image" alt="{{ $cheval->nom }}">
                        @endif
                    </div>
                    <div class="cheval-details">
                        <p><strong>Nom :</strong> {{ $cheval->nom }}</p>
                        <p><strong>Date de naissance :</strong> {{ \Carbon\Carbon::parse($cheval->date_de_naissance)->format('d/m/Y') }}</p>
                        <p><strong>Poids :</strong> {{ $cheval->poids }} kg</p>
                        <p><strong>Propriétaire :</strong> {{ $cheval->user ? $cheval->user->name . ' ' . $cheval->user->prenom : 'Aucun' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendrier -->
        <div class="card">
            <div id="calendar" class="mb-4"></div>
        </div>

        <!-- Formulaire latéral d'affectation des prestations -->
        <div id="prestations-section" class="side-form">
            <button id="close-prestations-section" class="close-btn">&times;</button>
            <h3>Affecter des prestations pour le <span id="selected-date"></span></h3>
            <form action="{{ route('livreprestation.store', $cheval->id) }}" method="POST">
                @csrf
                <input type="hidden" id="selected-date-input" name="selected_date" value="">
                <div id="prestations-list">
                    <div class="form-group">
                        <select name="prestations[]" class="form-control mb-2">
                            <option value="">-- Choisir une prestation --</option>
                            @foreach($prestations as $prestation)
                                <option value="{{ $prestation->id }}">{{ $prestation->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="button" class="btn btn-success btn-sm" onclick="ajouterPrestation()">+ Ajouter une prestation</button>
                <button type="submit" class="btn btn-primary mt-3">Affecter les prestations</button>
            </form>
        </div>

        <!-- Historique des prestations passées -->
        <div class="card">
            <div class="historique-prestations">
                <h2>Historique des prestations passées</h2>
                @if ($historique->isEmpty())
                    <p class="no-prestations">Aucune prestation </p>
                @else
                    <div class="table-container">
                        <table class="table historique-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Nom de la prestation</th>
                                    <th class="mobile_disable">Type</th>
                                    <th>Prix</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($historique as $entry)
                                    <tr>
                                        <td>{{ ($entry->date_prestation)}}</td>
                                        <td>{{ $entry->prestation->nom }}</td>
                                        <td class="mobile_disable">{{ $entry->prestation->type }}</td>
                                        <td>{{ $entry->prestation->prix }} €</td>
                                        <td>
                                            @if(Auth::user()->type_client === 'Gérant')
                                                <form action="{{ route('livreprestation.destroy', $entry->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Annuler</button>
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
    </div>
</div>

<!-- Inclure FullCalendar CSS et JS depuis le CDN -->
<link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>

<!-- Script pour gérer l'ouverture et la fermeture du formulaire latéral -->
<script>
    $(document).ready(function() {
        $('#calendar').fullCalendar({
            locale: 'fr',
            selectable: true,
            selectHelper: true,
            dayClick: function(date, jsEvent, view) {
                selectDate(date.format());
            }
        });

        $('#add-prestation-btn, #affect-prestation-btn').on('click', function() {
            $('#prestations-section').addClass('show');
        });

        $('#close-prestations-section').on('click', function() {
            $('#prestations-section').removeClass('show');
        }); 
    });

    function selectDate(dateStr) {
        $('#selected-date').text(new Date(dateStr).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' }));
        $('#selected-date-input').val(dateStr);
        $('#prestations-section').addClass('show');
    }

    function ajouterPrestation() {
        const prestationsList = $('#prestations-list');
        prestationsList.append(`
            <div class="form-group">
                <select name="prestations[]" class="form-control mb-2">
                    <option value="">-- Choisir une prestation --</option>
                    @foreach($prestations as $prestation)
                        <option value="{{ $prestation->id }}">{{ $prestation->nom }}</option>
                    @endforeach
                </select>
            </div>
        `);
    }
</script>
@endsection
