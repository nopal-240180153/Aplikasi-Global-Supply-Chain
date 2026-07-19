@extends('layouts.admin')

@section('title','Intelijen Berita')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        .news-card {
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
            border: none;
            overflow: hidden;
        }
        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.12) !important;
        }
        .news-img-wrap {
            position: relative;
            height: 190px;
            overflow: hidden;
        }
        .news-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s ease;
        }
        .news-card:hover .news-img-wrap img {
            transform: scale(1.05);
        }
        .news-placeholder {
            height: 190px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: white;
            font-size: 2.5rem;
        }
        .news-source-tag {
            position: absolute;
            bottom: 10px;
            left: 12px;
            background: rgba(0,0,0,.55);
            backdrop-filter: blur(4px);
            color: #fff;
            font-size: .7rem;
            padding: 3px 10px;
            border-radius: 20px;
            max-width: 80%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .news-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 3em;
            font-size: .95rem;
        }
        .news-description {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            font-size: .85rem;
        }
        .sentiment-bar {
            height: 3px;
            width: 100%;
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
                    @php
                        $gradients = [
                            'Positive' => 'linear-gradient(135deg,#10b981,#059669)',
                            'Negative' => 'linear-gradient(135deg,#ef4444,#dc2626)',
                            'Neutral'  => 'linear-gradient(135deg,#6366f1,#4f46e5)',
                        ];
                        $grad = $gradients[$item->sentiment] ?? 'linear-gradient(135deg,#667eea,#764ba2)';
                        $sentColor = $item->sentiment === 'Positive' ? '#10b981' : ($item->sentiment === 'Negative' ? '#ef4444' : '#6366f1');
                    @endphp
                    <div class="card shadow-sm rounded-4 news-card">

                        {{-- Top sentiment bar --}}
                        <div class="sentiment-bar" style="background:{{ $sentColor }}"></div>

                        {{-- Image / Placeholder --}}
                        <div class="news-img-wrap">
                            @if($item->image_url)
                                <img src="{{ $item->image_url }}"
                                     alt="{{ $item->title }}"
                                     loading="lazy"
                                     onerror="this.parentElement.innerHTML='<div class=news-placeholder style=background:{{ addslashes($grad) }}><i class=\'bi bi-newspaper\'></i><small style=font-size:.7rem>{{ addslashes(Str::limit($item->source, 20)) }}</small></div>'">
                            @else
                                <div class="news-placeholder" style="background:{{ $grad }}">
                                    <i class="bi bi-newspaper"></i>
                                    <small style="font-size:.72rem;opacity:.85">{{ Str::limit($item->source, 22) }}</small>
                                </div>
                            @endif
                            <div class="news-source-tag">
                                <i class="bi bi-broadcast me-1"></i>{{ $item->source }}
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column p-3">

                            {{-- Country & Sentiment --}}
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center gap-1">
                                    @if($item->country?->flag)
                                        <img src="{{ $item->country->flag }}" width="18" class="rounded-1" alt="">
                                    @endif
                                    <small class="text-muted fw-semibold" style="font-size:.75rem">
                                        {{ $item->country?->name ?? 'Global' }}
                                    </small>
                                </div>
                                @if($item->sentiment === 'Positive')
                                    <span class="badge rounded-pill" style="background:#d1fae5;color:#065f46;font-size:.7rem">
                                        <i class="bi bi-emoji-smile"></i> Positif
                                    </span>
                                @elseif($item->sentiment === 'Negative')
                                    <span class="badge rounded-pill" style="background:#fee2e2;color:#991b1b;font-size:.7rem">
                                        <i class="bi bi-emoji-frown"></i> Negatif
                                    </span>
                                @else
                                    <span class="badge rounded-pill" style="background:#e0e7ff;color:#3730a3;font-size:.7rem">
                                        <i class="bi bi-emoji-neutral"></i> Netral
                                    </span>
                                @endif
                            </div>

                            {{-- Title --}}
                            <h6 class="news-title fw-bold mb-2">{{ $item->title }}</h6>

                            {{-- Description --}}
                            @if($item->description)
                                <p class="news-description text-muted mb-3" style="font-size:.83rem">{{ $item->description }}</p>
                            @endif

                            {{-- Footer --}}
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <small class="text-muted" style="font-size:.74rem">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ optional($item->published_at)->format('d M Y · H:i') ?? 'T/A' }} UTC
                                </small>
                                <a href="{{ $item->url }}" target="_blank"
                                   class="btn btn-sm btn-primary rounded-3 px-3" style="font-size:.78rem">
                                    Baca <i class="bi bi-arrow-right"></i>
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