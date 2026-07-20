@extends('layouts.admin')

@section('title', 'Country Dashboard - ' . $country->name)

@section('content')

<!-- Header Profile Negara -->
<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white;">
    <div class="card-body p-4 p-md-5">
        <div class="row align-items-center">
            <div class="col-md-2 text-center text-md-start mb-3 mb-md-0">
                @if($country->flag)
                    <img src="{{ $country->flag }}" alt="Flag of {{ $country->name }}" class="img-fluid rounded shadow-sm border border-light" style="max-width: 120px; border-width: 3px !important;">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 120px; height: 80px; font-size: 2rem;">
                        <i class="bi bi-flag"></i>
                    </div>
                @endif
            </div>
            <div class="col-md-7 text-center text-md-start">
                <h1 class="display-5 fw-bold mb-1">{{ $country->name }}</h1>
                <p class="fs-5 text-white-50 mb-0">
                    <i class="bi bi-geo-alt-fill text-danger"></i> {{ $country->capital ?? 'Unknown Capital' }}, {{ $country->continent ?? 'Unknown Continent' }}
                </p>
                <div class="mt-3 d-flex flex-wrap justify-content-center justify-content-md-start gap-2">
                    <span class="badge bg-white text-primary px-3 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-people-fill"></i> Populasi: {{ $country->population ? number_format($country->population) : 'N/A' }}
                    </span>
                    <span class="badge bg-white text-success px-3 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-currency-exchange"></i> Mata Uang: {{ $country->currency_code ?? 'N/A' }}
                    </span>
                </div>
            </div>
            <div class="col-md-3 text-center text-md-end mt-4 mt-md-0">
                @if(Auth::check())
                <form action="{{ route('favorites.toggle') }}" method="POST">
                    @csrf
                    <input type="hidden" name="country_id" value="{{ $country->id }}">
                    <button type="submit" class="btn {{ $isFavorited ? 'btn-warning text-dark' : 'btn-outline-light' }} rounded-pill px-4 py-2 fw-bold shadow-sm transition-all" style="transition: all 0.3s ease;">
                        @if($isFavorited)
                            <i class="bi bi-star-fill"></i> Favorit Tersimpan
                        @else
                            <i class="bi bi-star"></i> Tambahkan ke Favorit
                        @endif
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Decorative Circle -->
    <div class="position-absolute rounded-circle" style="width: 300px; height: 300px; background: rgba(255,255,255,0.05); top: -100px; right: -50px; z-index: 0; pointer-events: none;"></div>
</div>

<!-- Main Row -->
<div class="row g-4 mb-4">
    
    <!-- Left Column: Peta & Berita -->
    <div class="col-lg-8">
        
        <!-- Peta Interaktif (Leaflet) -->
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 pb-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-map-fill text-primary"></i> Peta Lokasi & Infrastruktur
                </h5>
                <span class="badge bg-primary bg-opacity-10 text-primary">
                    {{ $ports->count() }} Pelabuhan
                </span>
            </div>
            <div class="card-body p-0 position-relative">
                <div id="countryMap" style="height: 500px; border-radius: 0 0 1rem 1rem; z-index: 1;"></div>
                
                @if(!$country->latitude || !$country->longitude)
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-light bg-opacity-75" style="z-index: 2; border-radius: 0 0 1rem 1rem;">
                    <div class="text-center">
                        <i class="bi bi-geo-alt-fill text-muted" style="font-size: 3rem;"></i>
                        <p class="mt-2 fw-semibold text-muted">Koordinat negara tidak tersedia.</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
    </div>
    
    <!-- Right Column: Statistik & Risk -->
    <div class="col-lg-4">
        
        <!-- Risk Score Card -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden position-relative">
            <!-- Dynamic Background based on risk -->
            @php
                $riskColor = 'secondary';
                $riskBg = '#f8f9fa';
                $riskIcon = 'bi-shield-check';
                
                if($risk) {
                    if($risk->risk_level == 'Rendah') { $riskColor = 'success'; $riskBg = '#ecfdf5'; $riskIcon = 'bi-shield-check'; }
                    elseif($risk->risk_level == 'Sedang') { $riskColor = 'warning'; $riskBg = '#fffbeb'; $riskIcon = 'bi-shield-exclamation'; }
                    elseif($risk->risk_level == 'Tinggi') { $riskColor = 'danger'; $riskBg = '#fef2f2'; $riskIcon = 'bi-shield-x'; }
                }
            @endphp
            
            <div class="card-body p-4" style="background-color: {{ $riskBg }};">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted fw-bold mb-1 text-uppercase" style="letter-spacing: 1px; font-size: 0.8rem;">Skor Analisis Risiko</h6>
                        <h2 class="fw-bold mb-0 text-{{ $riskColor }}">
                            {{ $risk ? $risk->total_score : 'N/A' }} <span class="fs-6 text-muted fw-normal">/ 100</span>
                        </h2>
                    </div>
                    <div class="bg-{{ $riskColor }} bg-opacity-10 p-3 rounded-circle text-{{ $riskColor }}">
                        <i class="bi {{ $riskIcon }} fs-2"></i>
                    </div>
                </div>
                
                @if($risk)
                <div class="progress mb-2" style="height: 8px;">
                    <div class="progress-bar bg-{{ $riskColor }}" role="progressbar" style="width: {{ $risk->total_score }}%"></div>
                </div>
                <div class="d-flex justify-content-between">
                    <small class="text-muted fw-semibold">Status: <span class="text-{{ $riskColor }}">{{ $risk->risk_level }}</span></small>
                    <small class="text-muted">{{ optional($risk->calculated_at)->diffForHumans() }}</small>
                </div>
                @else
                <p class="text-muted small mb-0"><i class="bi bi-info-circle"></i> Analisis risiko belum pernah dijalankan.</p>
                @endif
            </div>
        </div>
        
        <!-- Live Weather Card -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h6 class="text-muted fw-bold mb-3 text-uppercase" style="letter-spacing: 1px; font-size: 0.8rem;">Kondisi Cuaca Saat Ini</h6>
                
                @if($weather)
                <div class="d-flex align-items-center mb-4">
                    <div class="me-4 text-info">
                        @php
                            $icon = 'bi-cloud';
                            if(str_contains(strtolower($weather->weather_condition ?? ''), 'clear')) $icon = 'bi-sun text-warning';
                            if(str_contains(strtolower($weather->weather_condition ?? ''), 'rain')) $icon = 'bi-cloud-rain text-primary';
                            if(str_contains(strtolower($weather->weather_condition ?? ''), 'thunderstorm')) $icon = 'bi-cloud-lightning-rain text-dark';
                        @endphp
                        <i class="bi {{ $icon }}" style="font-size: 3.5rem;"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0">{{ number_format($weather->temperature, 1) }}°C</h2>
                        <p class="text-muted fw-semibold mb-0 fs-5">{{ $weather->weather_condition ?? 'Unknown' }}</p>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="bg-light p-2 rounded text-center">
                            <small class="text-muted d-block"><i class="bi bi-wind"></i> Kecepatan Angin</small>
                            <span class="fw-bold">{{ number_format($weather->wind_speed, 1) }} km/h</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light p-2 rounded text-center">
                            <small class="text-muted d-block"><i class="bi bi-cloud-drizzle"></i> Curah Hujan</small>
                            <span class="fw-bold">{{ number_format($weather->rainfall ?? 0, 1) }} mm</span>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center text-muted py-3">
                    <i class="bi bi-cloud-slash fs-2 d-block mb-2"></i>
                    Data cuaca tidak tersedia.
                </div>
                @endif
            </div>
        </div>

        <!-- Economy Card -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h6 class="text-muted fw-bold mb-3 text-uppercase" style="letter-spacing: 1px; font-size: 0.8rem;">Indikator Ekonomi ({{ $economy->year ?? '-' }})</h6>
                
                @if($economy)
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted"><i class="bi bi-cash-stack text-success"></i> GDP</span>
                        <span class="fw-bold fs-5">${{ number_format($economy->gdp / 1000000000, 2) }} Milyar</span>
                    </div>
                </div>
                <hr class="text-muted opacity-25">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted"><i class="bi bi-graph-up-arrow text-danger"></i> Inflasi</span>
                        <span class="fw-bold fs-5">{{ number_format($economy->inflation, 2) }}%</span>
                    </div>
                </div>
                @else
                <div class="text-center text-muted py-3">
                    <i class="bi bi-bar-chart-line fs-2 d-block mb-2"></i>
                    Data ekonomi tidak tersedia.
                </div>
                @endif
            </div>
        </div>
        
        <!-- Exchange Rate Card -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h6 class="text-muted fw-bold mb-3 text-uppercase" style="letter-spacing: 1px; font-size: 0.8rem;">Nilai Tukar</h6>
                
                @if($exchange && $country->currency_code)
                <div class="d-flex align-items-center justify-content-between">
                    <div class="text-center px-3 py-2 bg-light rounded-3 flex-grow-1">
                        <span class="fw-bold fs-4 text-primary">1 USD</span>
                    </div>
                    <div class="px-3 text-muted">
                        <i class="bi bi-arrow-left-right fs-4"></i>
                    </div>
                    <div class="text-center px-3 py-2 bg-light rounded-3 flex-grow-1">
                        <span class="fw-bold fs-4 text-success">{{ number_format($exchange->exchange_rate, 2) }}</span>
                        <small class="text-muted d-block fw-bold">{{ $country->currency_code }}</small>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <small class="text-muted"><i class="bi bi-clock"></i> Update: {{ optional($exchange->recorded_at)->diffForHumans() }}</small>
                </div>
                @else
                <div class="text-center text-muted py-3">
                    <i class="bi bi-currency-exchange fs-2 d-block mb-2"></i>
                    Data kurs tidak tersedia.
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

<!-- News Section -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-4 pb-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-newspaper text-primary"></i> Berita Rantai Pasok Terkini
                </h5>
            </div>
            <div class="card-body p-0">
                @if($news && $news->count() > 0)
                <div class="list-group list-group-flush rounded-bottom-4">
                    @foreach($news as $article)
                    <a href="{{ $article->url }}" target="_blank" class="list-group-item list-group-item-action p-4 transition-all" style="transition: all 0.2s ease;">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="fw-bold mb-0 text-primary">{{ $article->title }}</h6>
                            <small class="text-muted whitespace-nowrap ms-3">{{ optional($article->published_at)->format('d M Y') }}</small>
                        </div>
                        <p class="text-muted mb-2 small">{{ \Illuminate\Support\Str::limit($article->description, 150) }}</p>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">{{ $article->source }}</span>
                            
                            @php
                                $sentBadge = 'secondary';
                                if($article->sentiment == 'Positive') $sentBadge = 'success';
                                if($article->sentiment == 'Negative') $sentBadge = 'danger';
                            @endphp
                            <span class="badge bg-{{ $sentBadge }} rounded-pill px-3 shadow-sm">
                                Sentimen: {{ $article->sentiment }} ({{ number_format($article->sentiment_score, 1) }})
                            </span>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-journal-x fs-1 d-block mb-3 opacity-50"></i>
                    <h5>Belum ada berita terkait negara ini.</h5>
                    <p>Berita baru akan muncul setelah sistem melakukan sinkronisasi dengan News API.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if($country->latitude && $country->longitude)
            // Initialize Leaflet Map
            const map = L.map('countryMap').setView([{{ $country->latitude }}, {{ $country->longitude }}], 5);

            // Add standard OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap',
                maxZoom: 18,
            }).addTo(map);

            // Custom Icon for Country Marker
            const countryIcon = L.divIcon({
                className: 'custom-div-icon',
                html: "<div style='background-color:#0d6efd; color:white; border-radius:50%; width:30px; height:30px; display:flex; align-items:center; justify-content:center; border:2px solid white; box-shadow:0 0 10px rgba(0,0,0,0.5);'><i class='bi bi-geo-alt-fill fs-5'></i></div>",
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });

            // Marker for the Country Center/Capital
            L.marker([{{ $country->latitude }}, {{ $country->longitude }}], {icon: countryIcon})
                .addTo(map)
                .bindPopup('<div class="text-center"><b>{{ $country->name }}</b><br>Pusat Peta</div>')
                .openPopup();

            // Add Ports Markers
            const ports = @json($ports);
            
            if(ports.length > 0) {
                const portIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: "<div style='background-color:#198754; color:white; border-radius:50%; width:24px; height:24px; display:flex; align-items:center; justify-content:center; border:2px solid white; box-shadow:0 0 5px rgba(0,0,0,0.3);'><i class='bi bi-anchor fs-6'></i></div>",
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                });

                ports.forEach(port => {
                    if(port.latitude && port.longitude) {
                        L.marker([port.latitude, port.longitude], {icon: portIcon})
                            .addTo(map)
                            .bindPopup('<b>Pelabuhan:</b> ' + port.name + '<br><b>Tipe:</b> ' + (port.type || 'N/A'));
                    }
                });
            }
        @endif
    });
</script>

<style>
    .list-group-item-action:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }
</style>
@endpush
