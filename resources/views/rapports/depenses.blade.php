@extends('layouts.app')

@section('title', 'Dépenses par véhicule')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="fa-solid fa-money-bill-wave"></i> Dépenses par véhicule</h3>
        <a href="{{ route('rapports.depenses.export') }}" class="btn btn-outline-success">
            <i class="fa-solid fa-file-csv"></i> Exporter en CSV
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Dépenses totales du parc</div>
                    <div class="fs-4 fw-bold text-success">{{ number_format($totalGeneral, 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Véhicules suivis</div>
                    <div class="fs-4 fw-bold">{{ $vehicules->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Véhicule le plus coûteux</div>
                    <div class="fs-6 fw-bold">{{ $vehicules->first()?->immatriculation ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <canvas id="chart-depenses" height="90"></canvas>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="table-depenses" class="table table-striped align-middle w-100">
                <thead>
                    <tr>
                        <th>Véhicule</th>
                        <th>Entretiens</th>
                        <th>Carburant</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicules as $vehicule)
                        <tr>
                            <td><a href="{{ route('vehicules.show', $vehicule) }}">{{ $vehicule->immatriculation }} — {{ $vehicule->marque }} {{ $vehicule->modele }}</a></td>
                            <td>{{ number_format($vehicule->cout_entretiens, 0, ',', ' ') }} FCFA</td>
                            <td>{{ number_format($vehicule->cout_carburant, 0, ',', ' ') }} FCFA</td>
                            <td class="fw-semibold">{{ number_format($vehicule->cout_total, 0, ',', ' ') }} FCFA</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.initDataTable('#table-depenses', { order: [[3, 'desc']] });

            const labels = @json($vehicules->take(10)->pluck('immatriculation'));
            const entretiens = @json($vehicules->take(10)->pluck('cout_entretiens'));
            const carburant = @json($vehicules->take(10)->pluck('cout_carburant'));

            new Chart(document.getElementById('chart-depenses'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        { label: 'Entretiens', data: entretiens, backgroundColor: '#fd7e14' },
                        { label: 'Carburant', data: carburant, backgroundColor: '#22c55e' },
                    ],
                },
                options: {
                    responsive: true,
                    scales: { x: { stacked: true }, y: { stacked: true } },
                    plugins: { title: { display: true, text: 'Top 10 véhicules — dépenses (FCFA)' } },
                },
            });
        });
    </script>
@endpush
