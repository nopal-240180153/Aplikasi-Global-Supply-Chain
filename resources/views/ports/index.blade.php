@extends('layouts.admin')

@section('title', 'Lokasi Pelabuhan Global')

@push('styles')
<style>
    #map {
        height: 600px;
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .filter-sidebar {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        height: fit-content;
        position: sticky;
        top: 20px;
    }

    .port-counter {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 20px;
    }

    .port-counter h3 {
        font-size: 2rem;
        margin: 0;
        font-weight: bold;
    }

    .port-counter small {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .leaflet-popup-content-wrapper {
        border-radius: 8px;
    }

    .leaflet-popup-content {
        margin: 15px;
        min-width: 200px;
    }

    .popup-title {
        font-size: 1.1rem;
        font-weight: bold;
        color: #1e40af;
        margin-bottom: 10px;
    }

    .popup-info {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
        color: #6b7280;
    }

    .popup-info i {
        margin-right: 8px;
        color: #3b82f6;
    }

    @media (max-width: 768px) {
        #map {
            height: 400px;
        }
        
        .filter-sidebar {
            position: static;
            margin-bottom: 20px;
        }
    }
</style>
@endpush

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="mb-4">
        <h2 class="fw-bold">
            🗺️ Lokasi Pelabuhan Global
        </h2>
        <p class="text-muted mb-0">
            Peta interaktif pelabuhan di seluruh dunia untuk monitoring logistik dan rantai pasok.
        </p>
    </div>

    <div class="row g-4">

        <!-- Sidebar Filter -->
        <div class="col-lg-3">
            <div class="filter-sidebar">
                
                <!-- Counter -->
                <div class="port-counter">
                    <small>Total Pelabuhan</small>
                    <h3 id="port-count">{{ number_format($totalPorts) }}</h3>
                </div>

                <!-- Filter Form -->
                <form id="filterForm">
                    
                    <!-- Search -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-search"></i> Cari Pelabuhan
                        </label>
                        <input 
                            type="text" 
                            name="search" 
                            id="searchInput"
                            class="form-control" 
                            placeholder="Ketik nama pelabuhan..."
                            value="{{ $search }}">
                    </div>

                    <!-- Country Filter -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-globe"></i> Filter Negara
                        </label>
                        <select name="country" id="countrySelect" class="form-select">
                            <option value="">Semua Negara</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ $countryId == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel"></i> Terapkan Filter
                        </button>
                        
                        @if($search || $countryId)
                            <a href="{{ route('ports.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Atur Ulang
                            </a>
                        @endif
                    </div>

                </form>

                <!-- Info Box -->
                <div class="alert alert-info mt-4 mb-0" role="alert">
                    <small>
                        <i class="bi bi-info-circle"></i>
                        <strong>Petunjuk:</strong><br>
                        Klik marker di peta untuk melihat detail pelabuhan.
                    </small>
                </div>

            </div>
        </div>

        <!-- Map Container -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div id="map"></div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize map
    const map = L.map('map').setView([0, 20], 2);

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18,
        minZoom: 2
    }).addTo(map);

    // Port data from backend
    const ports = @json($ports);

    // Marker cluster group (optional, jika banyak marker)
    const markers = [];

    // Add markers to map
    ports.forEach(port => {
        if (port.latitude && port.longitude) {
            
            // Create marker
            const marker = L.marker([port.latitude, port.longitude], {
                title: port.port_name
            });

            // Create popup content
            const popupContent = `
                <div class="popup-title">
                    ${port.port_name}
                </div>
                <div class="popup-info">
                    <i class="bi bi-flag"></i>
                    <span>${port.country ? port.country.name : 'T/A'}</span>
                </div>
                <div class="popup-info">
                    <i class="bi bi-geo-alt"></i>
                    <span>${parseFloat(port.latitude).toFixed(4)}° N, ${parseFloat(port.longitude).toFixed(4)}° E</span>
                </div>
            `;

            marker.bindPopup(popupContent);
            marker.addTo(map);
            markers.push(marker);
        }
    });

    // Auto fit bounds if there are markers
    if (markers.length > 0) {
        const group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.1));
    }

    // Update counter
    document.getElementById('port-count').textContent = ports.length.toLocaleString('id-ID');

    // Handle form submission with AJAX (optional enhancement)
    const filterForm = document.getElementById('filterForm');
    filterForm.addEventListener('submit', function(e) {
        // Let the form submit normally to reload page
        // Or use AJAX if you want dynamic update
    });

});
</script>
@endpush
