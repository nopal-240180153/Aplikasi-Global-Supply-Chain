@extends('layouts.admin')

@section('title', 'Analisis Risiko')

@section('content')
<div class="container-fluid py-4">

    <!-- Header -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1 text-dark">Analisis Risiko</h2>
        <p class="text-muted">Menampilkan hasil analisis tingkat risiko rantai pasok setiap negara berdasarkan kondisi cuaca, ekonomi, nilai tukar, dan berita.</p>
    </div>

    <!-- Summary (4 Card) -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold">Total Negara Dianalisis</small>
                            <h3 class="fw-bold mb-0 mt-1 text-dark">{{ number_format($totalCountry) }}</h3>
                        </div>
                        <div class="text-primary fs-2">
                            <i class="fas fa-globe"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold">Risiko Tinggi</small>
                            <h3 class="fw-bold mb-0 mt-1 text-danger">{{ $highRisk }}</h3>
                        </div>
                        <div class="text-danger fs-2">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold">Risiko Sedang</small>
                            <h3 class="fw-bold mb-0 mt-1 text-warning">{{ $mediumRisk }}</h3>
                        </div>
                        <div class="text-warning fs-2">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold">Risiko Rendah</small>
                            <h3 class="fw-bold mb-0 mt-1 text-success">{{ $lowRisk }}</h3>
                        </div>
                        <div class="text-success fs-2">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Analisis Risiko -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white pt-3 pb-3 border-bottom">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h6 class="mb-0 fw-bold text-dark">Data Risiko Negara</h6>
                </div>
                <div class="col-md-4">
                    <input type="text" id="searchRisk" class="form-control form-control-sm" placeholder="Cari nama negara...">
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Negara</th>
                            <th>Cuaca</th>
                            <th>Inflasi</th>
                            <th>Nilai Tukar</th>
                            <th>Skor Cuaca</th>
                            <th>Skor Ekonomi</th>
                            <th>Skor Kurs</th>
                            <th>Skor Berita</th>
                            <th>Total Skor</th>
                            <th>Level Risiko</th>
                            <th class="pe-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($risks as $risk)
                        <tr class="risk-row">
                            <td class="ps-4">
                                <div class="fw-semibold text-dark country-name">{{ $risk->country->name }}</div>
                            </td>
                            <td>{{ $risk->country->weather_temp ?? '-' }}&deg;C</td>
                            <td>{{ $risk->country->inflation_rate ?? '-' }}%</td>
                            <td>{{ $risk->country->exchange_rate ?? '-' }}</td>
                            <td>{{ number_format($risk->weather_score, 1) }}</td>
                            <td>{{ number_format($risk->economy_score, 1) }}</td>
                            <td>{{ number_format($risk->exchange_score, 1) }}</td>
                            <td>{{ number_format($risk->news_score, 1) }}</td>
                            <td class="fw-bold">{{ number_format($risk->total_score, 1) }}</td>
                            <td>
                                @if($risk->risk_level == 'Rendah')
                                    <span class="badge bg-success">Rendah</span>
                                @elseif($risk->risk_level == 'Sedang')
                                    <span class="badge bg-warning text-dark">Sedang</span>
                                @else
                                    <span class="badge bg-danger">Tinggi</span>
                                @endif
                            </td>
                            <td class="pe-4 text-center">
                                <button class="btn btn-sm btn-outline-primary btn-detail" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#detailModal"
                                    data-country="{{ $risk->country->name }}"
                                    data-total="{{ number_format($risk->total_score, 1) }}"
                                    data-level="{{ $risk->risk_level }}"
                                    data-weather="{{ number_format($risk->weather_score, 1) }}"
                                    data-economy="{{ number_format($risk->economy_score, 1) }}"
                                    data-exchange="{{ number_format($risk->exchange_score, 1) }}"
                                    data-news="{{ number_format($risk->news_score, 1) }}">
                                    Detail
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-4 text-muted">Belum ada data analisis risiko.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 pt-3 pb-3">
            {{ $risks->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Grafik -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-2">
            <h6 class="fw-bold mb-0 text-dark">10 Negara dengan Risiko Tertinggi</h6>
        </div>
        <div class="card-body">
            <div style="height: 300px;">
                <canvas id="topRiskChart"></canvas>
            </div>
        </div>
    </div>

</div>

<!-- Panel Detail (Modal) -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-3 shadow">
      <div class="modal-header bg-light border-bottom-0">
        <h5 class="modal-title fw-bold text-dark" id="detailModalLabel">Detail Analisis Risiko</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1 text-dark" id="m-country">Nama Negara</h4>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small">Total Skor Risiko:</span>
                    <span class="fw-bold fs-5 text-dark" id="m-total">0</span>
                </div>
            </div>
            <div>
                <span class="badge px-3 py-2 fs-6" id="m-badge">Level</span>
            </div>
        </div>

        <h6 class="fw-bold text-dark mb-3">Penjelasan Singkat</h6>
        <div class="table-responsive mb-4">
            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Komponen</th>
                        <th class="text-center">Skor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-muted">Cuaca</td>
                        <td class="text-center fw-semibold" id="m-weather">0</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Ekonomi</td>
                        <td class="text-center fw-semibold" id="m-economy">0</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nilai Tukar</td>
                        <td class="text-center fw-semibold" id="m-exchange">0</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Berita</td>
                        <td class="text-center fw-semibold" id="m-news">0</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h6 class="fw-bold text-dark mb-2">Rekomendasi Tindakan</h6>
        <div class="alert alert-secondary border-0 text-dark small" id="m-recommendation">
            <span id="m-action"></span>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Fitur Pencarian Negara
    const searchRisk = document.getElementById('searchRisk');
    const riskRows = document.querySelectorAll('.risk-row');

    if (searchRisk) {
        searchRisk.addEventListener('keyup', function() {
            const query = this.value.toLowerCase();
            riskRows.forEach(row => {
                const countryName = row.querySelector('.country-name').textContent.toLowerCase();
                if (countryName.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Grafik Batang Top 10 Risiko
    @php
        $topRisks = collect($risks->items())->sortByDesc('total_score')->take(10);
        $topLabels = $topRisks->pluck('country.name')->toJson();
        $topData = $topRisks->pluck('total_score')->toJson();
    @endphp

    const ctx = document.getElementById('topRiskChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! $topLabels !!},
            datasets: [{
                label: 'Total Skor Risiko',
                data: {!! $topData !!},
                backgroundColor: '#3b82f6',
                borderRadius: 4,
                barPercentage: 0.5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, max: 100, grid: { borderDash: [4, 4] } },
                x: { grid: { display: false } }
            }
        }
    });

    // Logika Modal Detail
    const btns = document.querySelectorAll('.btn-detail');
    btns.forEach(btn => {
        btn.addEventListener('click', function() {
            const data = this.dataset;
            document.getElementById('m-country').textContent = data.country;
            document.getElementById('m-total').textContent = data.total;
            
            const badge = document.getElementById('m-badge');
            badge.textContent = data.level;
            if (data.level === 'Tinggi') {
                badge.className = 'badge bg-danger px-3 py-2 fs-6';
            } else if (data.level === 'Sedang') {
                badge.className = 'badge bg-warning text-dark px-3 py-2 fs-6';
            } else {
                badge.className = 'badge bg-success px-3 py-2 fs-6';
            }

            document.getElementById('m-weather').textContent = data.weather;
            document.getElementById('m-economy').textContent = data.economy;
            document.getElementById('m-exchange').textContent = data.exchange;
            document.getElementById('m-news').textContent = data.news;

            // Generate rekomendasi
            let action = "Lanjutkan pemantauan rutin terhadap indikator terkait. Kondisi relatif stabil.";
            if(data.level === 'Tinggi') {
                action = "Segera siapkan rencana mitigasi, cari rute logistik alternatif atau pemasok cadangan untuk meminimalisasi disrupsi.";
            } else if(data.level === 'Sedang') {
                action = "Tingkatkan buffer stock dan pantau secara ketat indikator yang memiliki skor cukup tinggi.";
            }
            
            // Tambahkan penjelasan spesifik jika ada skor yg menonjol (>30 misalnya)
            let exp = [];
            if(parseFloat(data.weather) > 30) exp.push("Kondisi cuaca");
            if(parseFloat(data.economy) > 30) exp.push("Ketidakstabilan ekonomi");
            if(parseFloat(data.exchange) > 30) exp.push("Fluktuasi nilai tukar");
            if(parseFloat(data.news) > 30) exp.push("Sentimen berita negatif");
            
            if(exp.length > 0) {
                action += " Perhatian ekstra diperlukan pada faktor: " + exp.join(", ") + ".";
            }
            
            document.getElementById('m-action').textContent = action;
        });
    });
});
</script>
@endpush