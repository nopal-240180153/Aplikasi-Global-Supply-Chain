@extends('layouts.admin')

@section('title','Intelijen Berita')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        .news-card {
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }
        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        .news-image {
            height: 200px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .news-placeholder {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 3rem;
        }
        .news-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: 3em;
        }
        .news-description {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endpush

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="mb-4">
        <h2 class="fw-bold">
            📰 Intelijen Berita
        </h2>
        <p class="text-muted">
            Monitoring berita global terkait rantai pasok, logistik, dan ekonomi.
        </p>
    </div>

    <!-- Filter Section -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Filter Negara</label>
                        <select name="country" class="form-select select2">
                            <option value="">Semua Negara</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ $countryId == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                    @if(request('country'))
                        <div class="col-md-3 d-flex align-items-end">
                            <a href="{{ route('news.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-x-circle"></i> Atur Ulang
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold">Total Berita</small>
                            <h3 class="fw-bold mb-0 mt-1">{{ $news->total() }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-newspaper fs-3 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold">Negara Dipantau</small>
                            <h3 class="fw-bold mb-0 mt-1">{{ $countries->count() }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-globe fs-3 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold">Filter Aktif</small>
                            <h3 class="fw-bold mb-0 mt-1">{{ request('country') ? 'Ya' : 'Tidak' }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-funnel fs-3 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- News Cards Grid -->
    @if($news->count() > 0)
        <div class="row g-4 mb-4">
            @foreach($news as $item)
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 news-card">
                        
                        <!-- Image -->
                        @if($item->image_url)
                            <img src="{{ $item->image_url }}" 
                                 class="card-img-top news-image rounded-top-4" 
                                 alt="{{ $item->title }}"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="news-placeholder rounded-top-4" style="display: none;">
                                <i class="bi bi-newspaper"></i>
                            </div>
                        @else
                            <div class="news-placeholder rounded-top-4">
                                <i class="bi bi-newspaper"></i>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            
                            <!-- Country & Source -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    @if($item->country && $item->country->flag)
                                        <img src="{{ $item->country->flag }}" 
                                             width="24" 
                                             class="rounded me-2" 
                                             alt="{{ $item->country->name }}">
                                    @endif
                                    <small class="text-muted fw-semibold">{{ $item->country?->name ?? 'Global' }}</small>
                                </div>
                                
                                <!-- Sentiment Badge -->
                                @if($item->sentiment == 'Positive')
                                    <span class="badge bg-success">
                                        <i class="bi bi-emoji-smile"></i> Positif
                                    </span>
                                @elseif($item->sentiment == 'Negative')
                                    <span class="badge bg-danger">
                                        <i class="bi bi-emoji-frown"></i> Negatif
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-emoji-neutral"></i> Netral
                                    </span>
                                @endif
                            </div>

                            <!-- Title -->
                            <h5 class="card-title news-title fw-bold mb-3">
                                {{ $item->title }}
                            </h5>

                            <!-- Description -->
                            @if($item->description)
                                <p class="card-text text-muted news-description mb-3">
                                    {{ $item->description }}
                                </p>
                            @endif

                            <!-- Footer -->
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <small class="text-muted">
                                        <i class="bi bi-building"></i> {{ $item->source }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> 
                                        {{ optional($item->published_at)->diffForHumans() ?? 'T/A' }}
                                    </small>
                                </div>
                                
                                <a href="{{ $item->url }}" 
                                   target="_blank" 
                                   class="btn btn-primary w-100">
                                    <i class="bi bi-box-arrow-up-right"></i> Baca Selengkapnya
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $news->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                <h5 class="text-muted mb-2">Belum Ada Berita</h5>
                <p class="text-muted">
                    @if(request('country'))
                        Tidak ada berita untuk negara yang dipilih.
                    @else
                        Belum ada data berita. Silakan lakukan sinkronisasi data.
                    @endif
                </p>
                @if(request('country'))
                    <a href="{{ route('news.index') }}" class="btn btn-primary mt-2">
                        <i class="bi bi-arrow-left"></i> Lihat Semua Berita
                    </a>
                @endif
            </div>
        </div>
    @endif

</div>

@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                placeholder: "Cari negara...",
                allowClear: true
            });
        });
    </script>
@endpush