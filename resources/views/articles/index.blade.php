@extends('layouts.app')

@section('title', 'Artikel Analisis')

@section('content')

<div class="container-fluid px-4 py-4">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-gradient-primary text-white">
                <div class="card-body p-5">
                    <h1 class="display-5 fw-bold mb-2">
                        <i class="bi bi-newspaper"></i>
                        Artikel Analisis
                    </h1>
                    <p class="lead mb-0">
                        Wawasan mendalam tentang supply chain, logistics, dan business intelligence
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Filter & Search -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('articles.index') }}" class="row g-3">
                        <div class="col-md-7">
                            <input 
                                type="text" 
                                name="search" 
                                class="form-control" 
                                placeholder="Cari artikel..."
                                value="{{ request('search') }}"
                            >
                        </div>
                        <div class="col-md-3">
                            <select name="category" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Articles Grid -->
            @if($articles->count() > 0)
                <div class="row g-4">
                    @foreach($articles as $article)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift">
                            @if($article->image_url)
                                <img src="{{ $article->image_url }}" 
                                     class="card-img-top rounded-top-4" 
                                     alt="{{ $article->title }}"
                                     style="height: 200px; object-fit: cover;"
                                     onerror="this.src='https://via.placeholder.com/400x200?text=No+Image'">
                            @else
                                <div class="bg-gradient-primary rounded-top-4" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-file-earmark-text text-white" style="font-size: 4rem;"></i>
                                </div>
                            @endif
                            
                            <div class="card-body d-flex flex-column">
                                <!-- Category Badge -->
                                @if($article->category)
                                    <span class="badge bg-primary mb-2 align-self-start">
                                        {{ $article->category }}
                                    </span>
                                @endif

                                <!-- Title -->
                                <h5 class="card-title">
                                    <a href="{{ route('articles.show', $article->slug) }}" 
                                       class="text-decoration-none text-dark hover-primary">
                                        {{ Str::limit($article->title, 60) }}
                                    </a>
                                </h5>

                                <!-- Excerpt -->
                                <p class="card-text text-muted small flex-grow-1">
                                    {{ Str::limit($article->excerpt, 100) }}
                                </p>

                                <!-- Meta -->
                                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                    <small class="text-muted">
                                        <i class="bi bi-person-circle"></i>
                                        {{ $article->author->name ?? 'Admin' }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i>
                                        {{ $article->published_at->diffForHumans() }}
                                    </small>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <small class="text-muted">
                                        <i class="bi bi-eye"></i>
                                        {{ number_format($article->views) }} views
                                    </small>
                                    <a href="{{ route('articles.show', $article->slug) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        Baca Selengkapnya
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($articles->hasPages())
                    <div class="mt-5">
                        {{ $articles->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox text-muted mb-3" style="font-size: 4rem;"></i>
                        <h4 class="text-muted">Belum Ada Artikel</h4>
                        <p class="text-muted">Artikel akan muncul di sini setelah dipublikasikan.</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-3">
            <!-- Popular Articles -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-fire text-danger"></i>
                        Artikel Populer
                    </h6>
                </div>
                <div class="card-body">
                    @forelse($popularArticles as $popular)
                        <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <h6 class="mb-1">
                                <a href="{{ route('articles.show', $popular->slug) }}" 
                                   class="text-decoration-none text-dark hover-primary">
                                    {{ Str::limit($popular->title, 50) }}
                                </a>
                            </h6>
                            <small class="text-muted">
                                <i class="bi bi-eye"></i> {{ number_format($popular->views) }} views
                            </small>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Belum ada artikel populer.</p>
                    @endforelse
                </div>
            </div>

            <!-- Categories -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-tags"></i>
                        Kategori
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('articles.index') }}" 
                           class="badge bg-{{ request('category') ? 'secondary' : 'primary' }} text-decoration-none">
                            Semua
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('articles.index', ['category' => $category]) }}" 
                               class="badge bg-{{ request('category') == $category ? 'primary' : 'secondary' }} text-decoration-none">
                                {{ $category }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .hover-primary:hover {
        color: #667eea !important;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
</style>
@endpush
