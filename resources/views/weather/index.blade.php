@extends('layouts.admin')

@section('title','Monitoring Cuaca')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                🌦️ Global Weather Monitoring
            </h2>
            <p class="text-muted mb-0">
                Peta dunia menunjukkan cuaca real-time: Hujan, Badai, Angin Kencang berdasarkan negara
            </p>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold">Total Monitoring</small>
                            <h2 class="fw-bold mt-2 mb-0">{{ $weatherLogs->total() }}</h2>
                        </div>
                        <div class="text-primary fs-2">
                            <i class="bi bi-cloud"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold">Rata-rata Suhu</small>
                            <h2 class="fw-bold mt-2 mb-0 text-danger">{{ number_format($averageTemperature,1) }} °C</h2>
                        </div>
                        <div class="text-danger fs-2">
                            <i class="bi bi-thermometer-half"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold">Risiko Tinggi</small>
                            <h2 class="fw-bold mt-2 mb-0 text-warning">{{ $highRiskCountries }}</h2>
                        </div>
                        <div class="text-warning fs-2">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold">Pembaruan Terakhir</small>
                            <h6 class="mt-2 mb-1">{{ optional($lastUpdate)->format('d M Y') ?? 'T/A' }}</h6>
                            <h5 class="fw-bold text-success mb-0">{{ optional($lastUpdate)->format('H:i') ?? '-' }}</h5>
                        </div>
                        <div class="text-success fs-2">
                            <i class="bi bi-clock-history"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Weather Map -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 pt-4 pb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-map"></i> Peta Cuaca Global Interaktif
                </h5>
                <div class="d-flex gap-2">
                    <select id="mapConditionFilter" class="form-select form-select-sm" style="width: 200px;">
                        <option value="">Semua Kondisi</option>
                        @foreach($conditions as $cond)
                            <option value="{{ $cond }}">{{ $cond }}</option>
                        @endforeach
                    </select>
                    <select id="mapContinentFilter" class="form-select form-select-sm" style="width: 200px;">
                        <option value="">Semua Benua</option>
                        @foreach($continents as $cont)
                            <option value="{{ $cont }}">{{ $cont }}</option>
                        @endforeach
                    </select>
                    <button id="refreshMap" class="btn btn-sm btn-primary">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div id="weatherMap" style="height: 600px; width: 100%;"></div>
        </div>
        <div class="card-footer bg-white border-0">
            <div class="d-flex justify-content-center gap-4 flex-wrap">
                <div class="d-flex align-items-center">
                    <div style="width: 20px; height: 20px; background: #4CAF50; border-radius: 50%; margin-right: 8px;"></div>
                    <small><i class="bi bi-sun-fill"></i> Clear</small>
                </div>
                <div class="d-flex align-items-center">
                    <div style="width: 20px; height: 20px; background: #9E9E9E; border-radius: 50%; margin-right: 8px;"></div>
                    <small><i class="bi bi-cloud-fill"></i> Cloudy</small>
                </div>
                <div class="d-flex align-items-center">
                    <div style="width: 20px; height: 20px; background: #2196F3; border-radius: 50%; margin-right: 8px;"></div>
                    <small><i class="bi bi-cloud-rain-heavy-fill"></i> Rain</small>
                </div>
                <div class="d-flex align-items-center">
                    <div style="width: 20px; height: 20px; background: #607D8B; border-radius: 50%; margin-right: 8px;"></div>
                    <small><i class="bi bi-cloud-fog-fill"></i> Fog</small>
                </div>
                <div class="d-flex align-items-center">
                    <div style="width: 20px; height: 20px; background: #E3F2FD; border: 2px solid #2196F3; border-radius: 50%; margin-right: 8px;"></div>
                    <small><i class="bi bi-snow"></i> Snow</small>
                </div>
                <div class="d-flex align-items-center">
                    <div style="width: 20px; height: 20px; background: #F44336; border-radius: 50%; margin-right: 8px;"></div>
                    <small><i class="bi bi-cloud-lightning-rain-fill"></i> Thunderstorm</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('weather.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Cari Negara</label>
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Ketik nama negara..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Benua</label>
                        <select name="continent" class="form-select">
                            <option value="">Semua Benua</option>
                            @foreach($continents as $cont)
                                <option value="{{ $cont }}" {{ request('continent') == $cont ? 'selected' : '' }}>
                                    {{ $cont }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Kondisi Cuaca</label>
                        <select name="condition" class="form-select">
                            <option value="">Semua Kondisi</option>
                            @foreach($conditions as $cond)
                                <option value="{{ $cond }}" {{ request('condition') == $cond ? 'selected' : '' }}>
                                    {{ $cond }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                </div>
                @if(request()->hasAny(['search', 'continent', 'condition']))
                    <div class="mt-3">
                        <a href="{{ route('weather.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Atur Ulang Filter
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 pt-4 pb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Data Monitoring Cuaca</h5>
                @if($weatherLogs->total() > 0)
                    <span class="badge bg-primary">{{ $weatherLogs->total() }} Data</span>
                @endif
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Negara</th>
                        <th>Suhu</th>
                        <th>Angin</th>
                        <th>Curah Hujan</th>
                        <th>Kondisi</th>
                        <th>Risiko Badai</th>
                        <th>Update</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($weatherLogs as $weather)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($weather->country->flag)
                                        <img src="{{ $weather->country->flag }}"
                                             width="35"
                                             class="me-2 rounded">
                                    @endif
                                    <strong>{{ $weather->country->name }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-danger">{{ $weather->temperature }} °C</span>
                            </td>
                            <td>
                                <i class="bi bi-wind"></i> {{ $weather->wind_speed }} km/h
                            </td>
                            <td>
                                <i class="bi bi-droplet-fill text-primary"></i> {{ $weather->rainfall }} mm
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($weather->weather_condition) {
                                        'Clear' => 'bg-success',
                                        'Cloudy' => 'bg-secondary',
                                        'Rain' => 'bg-primary',
                                        'Fog' => 'bg-info',
                                        'Snow' => 'bg-light text-dark',
                                        'Thunderstorm' => 'bg-danger',
                                        default => 'bg-warning text-dark'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $weather->weather_condition }}</span>
                            </td>
                            <td>
                                @if($weather->storm_risk < 30)
                                    <span class="badge bg-success">
                                        Rendah ({{ $weather->storm_risk }}%)
                                    </span>
                                @elseif($weather->storm_risk < 70)
                                    <span class="badge bg-warning text-dark">
                                        Sedang ({{ $weather->storm_risk }}%)
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        Tinggi ({{ $weather->storm_risk }}%)
                                    </span>
                                @endif
                            </td>
                            <td>
                                {{ $weather->recorded_at->format('d M Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center p-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Tidak ada data cuaca yang sesuai dengan filter.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($weatherLogs->hasPages())
            <div class="card-footer bg-white border-0">
                {{ $weatherLogs->links() }}
            </div>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
let map;
let markersLayer;

document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing weather map...');
    
    // Initialize Leaflet map
    map = L.map('weatherMap').setView([20, 0], 2);
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
    }).addTo(map);
    
    // Create markers layer group
    markersLayer = L.layerGroup().addTo(map);
    
    // Load weather data
    loadWeatherData();
    
    // Event listeners for filters
    document.getElementById('mapConditionFilter').addEventListener('change', loadWeatherData);
    document.getElementById('mapContinentFilter').addEventListener('change', loadWeatherData);
    document.getElementById('refreshMap').addEventListener('click', function() {
        console.log('Manual refresh triggered');
        loadWeatherData();
    });
});

function loadWeatherData() {
    console.log('Loading weather data...');
    
    const condition = document.getElementById('mapConditionFilter').value;
    const continent = document.getElementById('mapContinentFilter').value;
    
    console.log('Filters:', { condition, continent });
    
    // Build query string
    const params = new URLSearchParams();
    if (condition) params.append('condition', condition);
    if (continent) params.append('continent', continent);
    
    const url = `{{ route('api.weather.map-data') }}?${params.toString()}`;
    console.log('Fetching from:', url);
    
    // Show loading indicator
    document.getElementById('refreshMap').innerHTML = '<i class="bi bi-hourglass-split"></i> Loading...';
    document.getElementById('refreshMap').disabled = true;
    
    // Fetch data
    fetch(url)
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data.length, 'locations');
            console.log('Sample data:', data[0]);
            
            // Clear existing markers
            markersLayer.clearLayers();
            
            if (data.length === 0) {
                alert('⚠️ Tidak ada data cuaca. Silakan lakukan sinkronisasi data di Admin Portal → Data Sync → Weather.');
                return;
            }
            
            // Add markers for each weather location
            data.forEach((weather, index) => {
                console.log(`Adding marker ${index + 1}:`, weather.country_name, weather.latitude, weather.longitude);
                
                // Validate coordinates
                if (!weather.latitude || !weather.longitude || 
                    isNaN(weather.latitude) || isNaN(weather.longitude)) {
                    console.warn('Invalid coordinates for:', weather.country_name);
                    return;
                }
                
                const marker = L.circleMarker([weather.latitude, weather.longitude], {
                    radius: 10,
                    fillColor: weather.color || '#FFC107',
                    color: '#fff',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.8
                });
                
                // Create popup content
                const popupContent = `
                    <div style="min-width: 250px;">
                        <div class="d-flex align-items-center mb-2">
                            ${weather.flag ? `<img src="${weather.flag}" width="30" class="me-2">` : ''}
                            <strong style="font-size: 16px;">${weather.country_name}</strong>
                        </div>
                        <hr class="my-2">
                        <div class="mb-1">
                            <i class="bi ${weather.icon || 'bi-question-circle'} me-1"></i>
                            <strong>${weather.weather_condition}</strong>
                        </div>
                        <div class="mb-1">
                            <i class="bi bi-thermometer-half text-danger me-1"></i>
                            Suhu: <strong>${weather.temperature}°C</strong>
                        </div>
                        <div class="mb-1">
                            <i class="bi bi-wind text-info me-1"></i>
                            Angin: <strong>${weather.wind_speed} km/h</strong>
                        </div>
                        <div class="mb-1">
                            <i class="bi bi-droplet-fill text-primary me-1"></i>
                            Hujan: <strong>${weather.rainfall} mm</strong>
                        </div>
                        <div class="mb-1">
                            <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>
                            Risiko Badai: <strong>${weather.storm_risk}%</strong>
                        </div>
                        <hr class="my-2">
                        <small class="text-muted">
                            <i class="bi bi-clock me-1"></i>
                            ${weather.recorded_at}
                        </small>
                    </div>
                `;
                
                marker.bindPopup(popupContent);
                marker.addTo(markersLayer);
            });
            
            console.log('Total markers added:', markersLayer.getLayers().length);
            
            // Fit map to markers if there are any
            if (data.length > 0) {
                const group = new L.featureGroup(markersLayer.getLayers());
                map.fitBounds(group.getBounds().pad(0.1));
                console.log('Map bounds adjusted');
            }
        })
        .catch(error => {
            console.error('Error loading weather data:', error);
            alert('❌ Error loading weather data: ' + error.message + '\n\nSilakan cek console browser untuk detail.');
        })
        .finally(() => {
            // Reset button
            document.getElementById('refreshMap').innerHTML = '<i class="bi bi-arrow-clockwise"></i> Refresh';
            document.getElementById('refreshMap').disabled = false;
        });
}
</script>
@endpush
