@extends('layouts.app')

@push('styles')
    <style>
        .chart-container {
            width: 100%;
            height: 400px;
            margin-bottom: 40px;
        }
        .statistics-container {
            display: flex;
            justify-content: space-around;
            margin-bottom: 40px;
        }
        .stat-box {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            width: 20%;
            text-align: center;
        }
        .stat-box h3 {
            margin: 0;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <h1 class="text-center">Dashboard Financier</h1>

        <!-- Statistiques globales -->
        <div class="statistics-container">
            <div class="stat-box">
                <h3>{{ number_format($totalCA, 2) }} €</h3>
                <p>Chiffre d'affaires Total</p>
            </div>
            <div class="stat-box">
                <h3>{{ number_format($totalCAValide, 2) }} €</h3>
                <p>Chiffre d'affaires Validé</p>
            </div>
            <div class="stat-box">
                <h3>{{ number_format($totalCAAEnAttente, 2) }} €</h3>
                <p>Chiffre d'affaires En Attente</p>
            </div>
            <div class="stat-box">
                <h3>{{ number_format($totalCAFacture, 2) }} €</h3>
                <p>Chiffre d'affaires Facturé</p>
            </div>
            <div class="stat-box">
                <h3>{{ number_format($totalCAPaye, 2) }} €</h3>
                <p>Chiffre d'affaires Payé</p>
            </div>
        </div>

        <!-- Formulaire de filtre -->
        <form method="GET" action="{{ route('dashboard.finance') }}" class="mb-4">
            <div class="row">

            <label>Date de début de période</label>
                <div class="col-md-4">
                    <input type="date" name="date_debut" class="form-control" placeholder="Date de début" value="{{ request('date_debut') }}">
                </div>
                <div class="col-md-4">
                    <label>Date de fin de période </label>
                    <input type="date" name="date_fin" class="form-control" placeholder="Date de fin" value="{{ request('date_fin') }}">
                </div>
                <div class="col-md-4">
                    <select name="etat" class="form-control">
                        <option value="">-- Filtrer par état --</option>
                        <option value="en attente" {{ request('etat') == 'en attente' ? 'selected' : '' }}>En Attente</option>
                        <option value="validé" {{ request('etat') == 'validé' ? 'selected' : '' }}>Validé</option>
                        <option value="facturé" {{ request('etat') == 'facturé' ? 'selected' : '' }}>Facturé</option>
                        <option value="payé" {{ request('etat') == 'payé' ? 'selected' : '' }}>Payé</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Appliquer les filtres</button>
        </form>

        <!-- Chiffre d'affaires par prestation et état -->
        <div class="chart-container">
            <canvas id="chiffreAffaireChart"></canvas>
        </div>

        <!-- CA en fonction de l'année et du mois -->
        <div class="chart-container">
            <canvas id="chiffreAffaireParMoisChart"></canvas>
        </div>

        <!-- Répartition des prestations par utilisateurs -->
        <div class="chart-container">
            <canvas id="prestationsParUserChart"></canvas>
        </div>
    </div>

    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Chiffre d'affaires par prestation et état
        const chiffreAffaireData = @json($chiffreAffaire);
        const labels1 = chiffreAffaireData.map(ca => ca.prestation.nom + ' (' + ca.etat + ')');
        const data1 = chiffreAffaireData.map(ca => ca.total);

        const chiffreAffaireChart = new Chart(document.getElementById('chiffreAffaireChart'), {
            type: 'bar',
            data: {
                labels: labels1,
                datasets: [{
                    label: 'Chiffre d\'affaire par prestation et état',
                    data: data1,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // CA en fonction de l'année et du mois
        const chiffreAffaireParMoisData = @json($chiffreAffaireParMois);
        const labels2 = chiffreAffaireParMoisData.map(ca => ca.year + '-' + ca.month);
        const data2 = chiffreAffaireParMoisData.map(ca => ca.total);

        const chiffreAffaireParMoisChart = new Chart(document.getElementById('chiffreAffaireParMoisChart'), {
            type: 'line',
            data: {
                labels: labels2,
                datasets: [{
                    label: 'Chiffre d\'affaire par mois',
                    data: data2,
                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1,
                    fill: false
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Prestations validées par utilisateurs
        const prestationsParUserData = @json($prestationsParUser);
        const labels3 = prestationsParUserData.map(pu => pu.validator.name);
        const data3 = prestationsParUserData.map(pu => pu.total);

        const prestationsParUserChart = new Chart(document.getElementById('prestationsParUserChart'), {
            type: 'doughnut',
            data: {
                labels: labels3,
                datasets: [{
                    label: 'Prestations validées par utilisateurs',
                    data: data3,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    </script>
@endsection
