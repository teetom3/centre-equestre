@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Détails du cheval : {{ $cheval->nom }}</h1>

        <!-- Détails du cheval -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        @if($cheval->photo)
                            <img src="{{ asset('images/' . $cheval->photo) }}" class="img-fluid" alt="{{ $cheval->nom }}">
                        @else
                            <img src="{{ asset('images/default_cheval.jpg') }}" class="img-fluid" alt="{{ $cheval->nom }}">
                        @endif
                    </div>
                    <div class="col-md-8">
                        <p><strong>Nom :</strong> {{ $cheval->nom }}</p>
                        <p><strong>Date de naissance :</strong> {{ \Carbon\Carbon::parse($cheval->date_de_naissance)->format('d/m/Y') }}</p>
                        <p><strong>Poids :</strong> {{ $cheval->poids }} kg</p>
                        <p><strong>Propriétaire :</strong> {{ $cheval->user ? $cheval->user->name . ' ' . $cheval->user->prenom : 'Aucun' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation entre les semaines -->
        <div class="mb-4">
            <h2>Calendrier de la semaine</h2>
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" onclick="navigateWeek(-1)">Semaine précédente</button>
                <span id="week-label"></span>
                <button type="button" class="btn btn-secondary" onclick="navigateWeek(1)">Semaine suivante</button>
            </div>
        </div>

        <!-- Calendrier de la semaine -->
        <div class="mb-4">
            <form action="{{ route('livreprestation.store', $cheval->id) }}" method="POST">
                @csrf
                <div class="row" id="calendar-week">
                    <!-- Les jours de la semaine seront générés par JavaScript -->
                </div>
                <button type="submit" class="btn btn-primary mt-3">Affecter les prestations</button>
            </form>
        </div>
         <!-- Bouton Carnet de santé -->
         <button class="btn btn-info mb-4" onclick="toggleSoinTable()">Carnet de santé</button>

<!-- Tableau du Carnet de santé (initialement caché) -->
<div id="soinTableContainer" class="card mb-4" style="display: none;">
    <div class="card-body">
        <h2>Carnet de santé</h2>

        <!-- Formulaire de filtrage -->
        <form id="filtreSoins" method="GET" action="{{ route('chevaux.show', $cheval->id) }}">
            <div class="row">
                <div class="col-md-4">
                    <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}" placeholder="Date de début">
                </div>
                <div class="col-md-4">
                    <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}" placeholder="Date de fin">
                </div>
                <div class="col-md-4">
                    <input type="text" name="nom_prestation" class="form-control" value="{{ request('nom_prestation') }}" placeholder="Nom de la prestation">
                </div>
                <div class="col-md-12 mt-2">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                </div>
            </div>
        </form>

        <!-- Tableau des soins -->
        @if ($soins->isEmpty())
            <p>Aucun soin enregistré.</p>
        @else
            <table class="table mt-4">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Nom de la prestation</th>
                        <th>Type</th>
                        <th>Prix</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($soins as $soin)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($soin->date_prestation)->format('d/m/Y') }}</td>
                            <td>{{ $soin->prestation->nom }}</td>
                            <td>{{ $soin->prestation->type }}</td>
                            <td>{{ $soin->prestation->prix }} €</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

      <!-- Livre des prestations -->
      <div>
            <h2>Livre des prestations</h2>
            @if ($livreDesPrestations->isEmpty())
                <p>Aucune prestation enregistrée.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Nom de la prestation</th>
                            <th>Type</th>
                            <th>Prix</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($livreDesPrestations as $entry)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($entry->date_prestation)->format('d/m/Y') }}</td>
                                <td>{{ $entry->prestation->nom }}</td>
                                <td>{{ $entry->prestation->type }}</td>
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
            @endif
        </div>
    </div>

    <script>
        let currentWeekOffset = 0;
        const prestations = @json($prestations);




        function renderWeek() {
            const startOfWeek = moment().startOf('isoWeek').add(currentWeekOffset, 'weeks');
            const endOfWeek = startOfWeek.clone().endOf('isoWeek');
            const calendarWeek = document.getElementById('calendar-week');
            const weekLabel = document.getElementById('week-label');
            calendarWeek.innerHTML = '';
            weekLabel.textContent = `Semaine du ${startOfWeek.format('DD/MM/YYYY')} au ${endOfWeek.format('DD/MM/YYYY')}`;

            for (let i = 0; i < 7; i++) {
                const jour = startOfWeek.clone().add(i, 'days');
                const dayDiv = document.createElement('div');
                dayDiv.className = 'col-md-2';
                dayDiv.innerHTML = `
                    <h4>${jour.format('dddd DD/MM')}</h4>
                    <div id="prestations_${jour.format('YYYY-MM-DD')}">
                        <div class="form-group">
                            <select name="prestations[${jour.format('YYYY-MM-DD')}][]" class="form-control mb-2">
                                <option value="">-- Choisir une prestation --</option>
                                ${prestations.map(prestation => `<option value="${prestation.id}">${prestation.nom}</option>`).join('')}
                            </select>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success btn-sm mb-3" onclick="ajouterPrestation('${jour.format('YYYY-MM-DD')}')">+ Ajouter une prestation</button>
                `;
                calendarWeek.appendChild(dayDiv);
            }
        }

        function navigateWeek(direction) {
            currentWeekOffset += direction;
            renderWeek();
        }

        function ajouterPrestation(date) {
            const prestationDiv = document.getElementById('prestations_' + date);
            const newPrestationSelect = document.createElement('div');
            newPrestationSelect.classList.add('form-group');
            newPrestationSelect.innerHTML = `
                <select name="prestations[${date}][]" class="form-control mb-2">
                    <option value="">-- Choisir une prestation --</option>
                    ${prestations.map(prestation => `<option value="${prestation.id}">${prestation.nom}</option>`).join('')}
                </select>
            `;
            prestationDiv.appendChild(newPrestationSelect);
        }

        document.addEventListener('DOMContentLoaded', function() {
            moment.locale('fr'); // Mettre le calendrier en français
            renderWeek(); // Afficher la semaine actuelle au chargement de la page
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/fr.min.js"></script>

@endsection


 

