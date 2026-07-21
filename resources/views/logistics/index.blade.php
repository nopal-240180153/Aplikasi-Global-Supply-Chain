@extends('layouts.admin')

@section('title', 'Logistics Simulator')

@push('styles')
<style>
    #map {
        height: 500px;
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .result-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        height: 100%;
        transition: transform 0.2s;
    }
    .result-card:hover {
        transform: translateY(-5px);
    }
    .result-icon {
        font-size: 2.5rem;
        color: #4f46e5;
        margin-bottom: 15px;
    }
    .result-value {
        font-size: 1.5rem;
        font-weight: bold;
        color: #1f2937;
    }
    .result-label {
        color: #6b7280;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .weather-info {
        font-size: 0.95rem;
    }
</style>
@endpush

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h2 class="fw-bold">
            🚢 Logistics Simulator
        </h2>
        <p class="text-muted mb-0">
            Simulasi pengiriman kargo antar pelabuhan beserta kalkulasi jarak, estimasi waktu, dan analisis kondisi pelabuhan.
        </p>
    </div>

    <div class="row g-4">
        <!-- Input Form -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold"><i class="bi bi-geo-alt text-primary me-2"></i> Parameter Rute</h5>
                </div>
                <div class="card-body">
                    <form id="logisticsForm">
                        
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted mb-3 border-bottom pb-2">📍 Pelabuhan Asal (Origin)</h6>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Negara Asal</label>
                                <select class="form-select country-select" id="originCountry" required>
                                    <option value="" selected disabled>Pilih Negara Asal</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Pelabuhan Asal</label>
                                <select class="form-select port-select" id="originPort" required disabled>
                                    <option value="" selected disabled>Pilih Negara Dahulu</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold text-muted mb-3 border-bottom pb-2">🏁 Pelabuhan Tujuan (Destination)</h6>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Negara Tujuan</label>
                                <select class="form-select country-select" id="destCountry" required>
                                    <option value="" selected disabled>Pilih Negara Tujuan</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Pelabuhan Tujuan</label>
                                <select class="form-select port-select" id="destPort" required disabled>
                                    <option value="" selected disabled>Pilih Negara Dahulu</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3" id="btnCalculate">
                                <i class="bi bi-calculator me-1"></i> Kalkulasi Rute
                            </button>
                        </div>

                        <div id="errorMsg" class="alert alert-danger mt-3 d-none"></div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Map & Results -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-0">
                    <div id="map"></div>
                </div>
            </div>

            <div id="resultsContainer" class="row g-3 d-none">
                <div class="col-md-4">
                    <div class="result-card text-center">
                        <div class="result-icon"><i class="bi bi-signpost-split"></i></div>
                        <div class="result-label">Jarak Tempuh</div>
                        <div class="result-value mt-2" id="resDistance">0 km</div>
                        <div class="text-muted small mt-1" id="resDistanceNm">0 NM</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="result-card text-center">
                        <div class="result-icon"><i class="bi bi-clock-history"></i></div>
                        <div class="result-label">Estimasi Waktu (ETA)</div>
                        <div class="result-value mt-2" id="resTime">0 Hari</div>
                        <div class="text-muted small mt-1">Kapal Kargo (~20 knots)</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="result-card text-center">
                        <div class="result-icon"><i class="bi bi-shield-exclamation"></i></div>
                        <div class="result-label">Risk Score & Ekonomi (Tujuan)</div>
                        <div class="result-value mt-2" id="resRiskScore">Low</div>
                        <div class="text-muted small mt-1" id="resExchange">USD = 0</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="result-card">
                        <div class="result-label mb-2"><i class="bi bi-cloud-sun text-info"></i> Cuaca Origin</div>
                        <div class="weather-info" id="resOriginWeather"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="result-card">
                        <div class="result-label mb-2"><i class="bi bi-cloud-sun text-info"></i> Cuaca Destination</div>
                        <div class="weather-info" id="resDestWeather"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // API URL Base
    const baseUrl = window.location.origin;

    // Initialize Map
    const map = L.map('map').setView([0, 0], 2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18,
        minZoom: 2
    }).addTo(map);

    let routeLayer = null;
    let markersLayer = L.layerGroup().addTo(map);

    // Handle Country Selection
    function setupCountrySelect(countrySelectId, portSelectId) {
        document.getElementById(countrySelectId).addEventListener('change', function() {
            const countryId = this.value;
            const portSelect = document.getElementById(portSelectId);
            
            portSelect.innerHTML = '<option value="" selected disabled>Loading...</option>';
            portSelect.disabled = true;

            fetch(`${baseUrl}/logistics/ports/${countryId}`)
                .then(response => response.json())
                .then(data => {
                    portSelect.innerHTML = '<option value="" selected disabled>Pilih Pelabuhan</option>';
                    if(data.length === 0) {
                        portSelect.innerHTML = '<option value="" selected disabled>Tidak ada pelabuhan</option>';
                    } else {
                        data.forEach(port => {
                            portSelect.innerHTML += `<option value="${port.id}">${port.port_name}</option>`;
                        });
                        portSelect.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error fetching ports:', error);
                    portSelect.innerHTML = '<option value="" selected disabled>Error memuat data</option>';
                });
        });
    }

    setupCountrySelect('originCountry', 'originPort');
    setupCountrySelect('destCountry', 'destPort');

    // Handle Form Submit
    document.getElementById('logisticsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const originPortId = document.getElementById('originPort').value;
        const destPortId = document.getElementById('destPort').value;
        const errorMsg = document.getElementById('errorMsg');
        const btn = document.getElementById('btnCalculate');
        
        errorMsg.classList.add('d-none');

        if(!originPortId || !destPortId) {
            errorMsg.textContent = 'Silakan pilih pelabuhan asal dan tujuan.';
            errorMsg.classList.remove('d-none');
            return;
        }

        if(originPortId === destPortId) {
            errorMsg.textContent = 'Pelabuhan asal dan tujuan tidak boleh sama.';
            errorMsg.classList.remove('d-none');
            return;
        }

        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menghitung...';
        btn.disabled = true;

        fetch(`${baseUrl}/logistics/calculate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                origin_port_id: originPortId,
                destination_port_id: destPortId
            })
        })
        .then(response => response.json())
        .then(data => {
            btn.innerHTML = '<i class="bi bi-calculator me-1"></i> Kalkulasi Rute';
            btn.disabled = false;

            if(data.error) {
                errorMsg.textContent = data.error;
                errorMsg.classList.remove('d-none');
                return;
            }

            // Update UI with results
            document.getElementById('resultsContainer').classList.remove('d-none');
            
            document.getElementById('resDistance').textContent = `${data.distance_km.toLocaleString('id-ID')} km`;
            document.getElementById('resDistanceNm').textContent = `${data.distance_nm.toLocaleString('id-ID')} Nautical Miles`;
            
            document.getElementById('resTime').textContent = `${data.estimated_days} Hari ${data.estimated_hours} Jam`;
            
            // Risk & Economy
            const riskColor = data.destination_risk.score >= 40 ? 'danger' : (data.destination_risk.score >= 20 ? 'warning' : 'success');
            document.getElementById('resRiskScore').innerHTML = `<span class="badge bg-${riskColor}">${data.destination_risk.level} (${data.destination_risk.score})</span>`;
            document.getElementById('resExchange').textContent = `1 USD = ${data.destination_exchange.rate} ${data.destination_exchange.currency}`;

            // Weather Update helper
            const updateWeatherUI = (elId, weather) => {
                let html = `<strong>${weather.port_name}</strong><br>`;
                if(weather.status !== 'Berhasil mengambil data') {
                    html += `<span class="text-muted">Data cuaca tidak tersedia</span>`;
                } else {
                    html += `Suhu: ${weather.temperature}°C | Angin: ${weather.wind_speed} km/h<br>`;
                    if(weather.warning) {
                        html += `<span class="badge bg-danger mt-2"><i class="bi bi-exclamation-triangle"></i> ${weather.warning_msg}</span>`;
                    } else {
                        html += `<span class="badge bg-success mt-2"><i class="bi bi-check-circle"></i> Cuaca Terang / Aman</span>`;
                    }
                }
                document.getElementById(elId).innerHTML = html;
            };

            updateWeatherUI('resOriginWeather', data.weather.origin);
            updateWeatherUI('resDestWeather', data.weather.destination);

            // Draw Map Route
            drawRoute(data.origin_port, data.destination_port);

            // Scroll to results
            document.getElementById('resultsContainer').scrollIntoView({ behavior: 'smooth' });

        })
        .catch(error => {
            console.error('Error:', error);
            btn.innerHTML = '<i class="bi bi-calculator me-1"></i> Kalkulasi Rute';
            btn.disabled = false;
            errorMsg.textContent = 'Terjadi kesalahan sistem.';
            errorMsg.classList.remove('d-none');
        });
    });

    function drawRoute(origin, dest) {
        markersLayer.clearLayers();
        if(routeLayer) map.removeLayer(routeLayer);

        const latlngs = [
            [origin.latitude, origin.longitude],
            [dest.latitude, dest.longitude]
        ];

        // Draw Markers
        L.marker([origin.latitude, origin.longitude]).bindPopup(`<b>${origin.port_name}</b> (Asal)`).addTo(markersLayer);
        L.marker([dest.latitude, dest.longitude]).bindPopup(`<b>${dest.port_name}</b> (Tujuan)`).addTo(markersLayer);

        // Draw Polyline with curve effect by adding multiple points or just straight line
        routeLayer = L.polyline(latlngs, {color: '#4f46e5', weight: 4, dashArray: '10, 10'}).addTo(map);

        // Fit Bounds
        map.fitBounds(routeLayer.getBounds().pad(0.2));
    }
});
</script>
@endpush
