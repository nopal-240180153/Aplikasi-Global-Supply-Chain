@extends('layouts.app')

@section('title', $article->title)

@section('content')

<div class="container-fluid px-4 py-4">

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-decoration-none">
                            <i class="bi bi-house-door"></i> Home
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('articles.index') }}" class="text-decoration-none">
                            Artikel
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ Str::limit($article->title, 50) }}
                    </li>
                </ol>
            </nav>

            <!-- Article Header -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-5">
                    <!-- Category Badge -->
                    @if($article->category)
                        <span class="badge bg-primary mb-3">
                            {{ $article->category }}
                        </span>
                    @endif

                    <!-- Title -->
                    <h1 class="display-5 fw-bold mb-4">
                        {{ $article->title }}
                    </h1>

                    <!-- Meta Information -->
                    <div class="d-flex flex-wrap align-items-center gap-4 mb-4 pb-4 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                <i class="bi bi-person-circle text-primary fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Penulis</small>
                                <strong>{{ $article->author->name ?? 'Admin' }}</strong>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-circle p-2 me-2">
                                <i class="bi bi-calendar text-success fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Dipublikasikan</small>
                                <strong>{{ $article->published_at->format('d M Y') }}</strong>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-circle p-2 me-2">
                                <i class="bi bi-clock text-info fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Waktu Baca</small>
                                <strong>{{ $article->reading_time }} menit</strong>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-2">
                                <i class="bi bi-eye text-warning fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Views</small>
                                <strong>{{ number_format($article->views) }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Cover Image -->
                    @if($article->image_url)
                        <img src="{{ $article->image_url }}" 
                             class="img-fluid rounded-4 w-100 mb-4" 
                             alt="{{ $article->title }}"
                             style="max-height: 500px; object-fit: cover;"
                             onerror="this.style.display='none'">
                    @endif

                    <!-- Article Content -->
                    <div class="article-content">
                        {!! $article->content !!}
                    </div>

                    <!-- Tags -->
                    @if($article->tags && count($article->tags) > 0)
                        <div class="mt-5 pt-4 border-top">
                            <h6 class="mb-3">
                                <i class="bi bi-tags"></i> Tags:
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($article->tags as $tag)
                                    <span class="badge bg-secondary">
                                        {{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Related Articles -->
            @if($relatedArticles->count() > 0)
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 pt-4 pb-3">
                        <h4 class="mb-0">
                            <i class="bi bi-files text-primary"></i>
                            Artikel Terkait
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            @foreach($relatedArticles as $related)
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift">
                                    @if($related->image_url)
                                        <img src="{{ $related->image_url }}" 
                                             class="card-img-top rounded-top-4" 
                                             alt="{{ $related->title }}"
                                             style="height: 150px; object-fit: cover;"
                                             onerror="this.src='https://via.placeholder.com/300x150?text=No+Image'">
                                    @else
                                        <div class="bg-gradient-primary rounded-top-4" style="height: 150px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-file-earmark-text text-white" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <a href="{{ route('articles.show', $related->slug) }}" 
                                               class="text-decoration-none text-dark hover-primary">
                                                {{ Str::limit($related->title, 60) }}
                                            </a>
                                        </h6>
                                        <p class="card-text text-muted small">
                                            {{ Str::limit($related->excerpt, 80) }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <small class="text-muted">
                                                <i class="bi bi-clock"></i>
                                                {{ $related->published_at->diffForHumans() }}
                                            </small>
                                            <a href="{{ route('articles.show', $related->slug) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                Baca
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Back Button -->
            <div class="text-center mt-4">
                <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Daftar Artikel
                </a>
            </div>

        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
    .article-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #333;
    }

    .article-content h2, 
    .article-content h3, 
    .article-content h4 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .article-content p {
        margin-bottom: 1.5rem;
    }

    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        margin: 1.5rem 0;
    }

    .article-content ul, 
    .article-content ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }

    .article-content li {
        margin-bottom: 0.5rem;
    }

    .article-content blockquote {
        border-left: 4px solid #667eea;
        padding-left: 1.5rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #666;
    }

    .article-content code {
        background-color: #f4f4f4;
        padding: 0.2rem 0.4rem;
        border-radius: 0.25rem;
        font-size: 0.9rem;
    }

    .article-content pre {
        background-color: #f4f4f4;
        padding: 1rem;
        border-radius: 0.5rem;
        overflow-x: auto;
        margin: 1.5rem 0;
    }

    .article-content table {
        width: 100%;
        margin: 1.5rem 0;
        border-collapse: collapse;
    }

    .article-content table th,
    .article-content table td {
        padding: 0.75rem;
        border: 1px solid #dee2e6;
    }

    .article-content table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

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
