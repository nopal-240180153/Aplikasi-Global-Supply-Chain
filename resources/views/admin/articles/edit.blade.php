@extends('layouts.admin-portal')

@section('title', 'Edit Artikel')

@push('styles')
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.css" rel="stylesheet">
@endpush

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">
                <i class="bi bi-pencil-square text-warning"></i>
                Edit Artikel
            </h2>
            <p class="text-muted mb-0">
                Edit artikel: <strong>{{ $article->title }}</strong>
            </p>
        </div>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('admin.articles.update', $article) }}">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">
                            <i class="bi bi-file-earmark-text"></i>
                            Konten Artikel
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold">
                                Judul Artikel <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                id="title" 
                                name="title" 
                                value="{{ old('title', $article->title) }}"
                                placeholder="Masukkan judul artikel yang menarik..."
                                required
                                autofocus
                            >
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Slug: {{ $article->slug }}</small>
                        </div>

                        <!-- Excerpt -->
                        <div class="mb-4">
                            <label for="excerpt" class="form-label fw-bold">
                                Ringkasan/Excerpt
                            </label>
                            <textarea 
                                class="form-control @error('excerpt') is-invalid @enderror" 
                                id="excerpt" 
                                name="excerpt" 
                                rows="3"
                                placeholder="Ringkasan singkat artikel (maks 500 karakter)"
                            >{{ old('excerpt', $article->excerpt) }}</textarea>
                            @error('excerpt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Akan ditampilkan di halaman listing artikel</small>
                        </div>

                        <!-- Content (WYSIWYG) -->
                        <div class="mb-4">
                            <label for="content" class="form-label fw-bold">
                                Konten Artikel <span class="text-danger">*</span>
                            </label>
                            <textarea 
                                class="form-control @error('content') is-invalid @enderror" 
                                id="content" 
                                name="content" 
                                required
                            >{{ old('content', $article->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Article Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle"></i>
                            Informasi Artikel
                        </h6>
                    </div>
                    <div class="card-body">
                        <small class="text-muted d-block mb-2">
                            <i class="bi bi-person"></i> 
                            Penulis: <strong>{{ $article->author->name ?? 'Unknown' }}</strong>
                        </small>
                        <small class="text-muted d-block mb-2">
                            <i class="bi bi-calendar"></i> 
                            Dibuat: {{ $article->created_at->format('d M Y H:i') }}
                        </small>
                        <small class="text-muted d-block mb-2">
                            <i class="bi bi-clock-history"></i> 
                            Terakhir diupdate: {{ $article->updated_at->format('d M Y H:i') }}
                        </small>
                        <small class="text-muted d-block mb-2">
                            <i class="bi bi-eye"></i> 
                            Views: <strong>{{ number_format($article->views) }}</strong>
                        </small>
                        @if($article->published_at)
                            <small class="text-muted d-block">
                                <i class="bi bi-check-circle"></i> 
                                Dipublikasi: {{ $article->published_at->format('d M Y H:i') }}
                            </small>
                        @endif
                    </div>
                </div>

                <!-- Publish Settings -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-gear"></i>
                            Pengaturan Publikasi
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select 
                                class="form-select @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status" 
                                required
                            >
                                <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>
                                    Draft (Belum dipublikasikan)
                                </option>
                                <option value="published" {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>
                                    Published (Langsung tayang)
                                </option>
                                <option value="archived" {{ old('status', $article->status) == 'archived' ? 'selected' : '' }}>
                                    Archived (Diarsipkan)
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category" class="form-label fw-bold">
                                Kategori
                            </label>
                            <input 
                                type="text" 
                                class="form-control @error('category') is-invalid @enderror" 
                                id="category" 
                                name="category" 
                                value="{{ old('category', $article->category) }}"
                                placeholder="Contoh: Supply Chain, Logistics"
                                list="categoryList"
                            >
                            <datalist id="categoryList">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">
                                @endforeach
                            </datalist>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tags -->
                        <div class="mb-3">
                            <label for="tags" class="form-label fw-bold">
                                Tags
                            </label>
                            <input 
                                type="text" 
                                class="form-control @error('tags') is-invalid @enderror" 
                                id="tags" 
                                name="tags" 
                                value="{{ old('tags', is_array($article->tags) ? implode(', ', $article->tags) : '') }}"
                                placeholder="risk, supply chain, analysis"
                            >
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Pisahkan dengan koma</small>
                        </div>

                        <!-- Image URL -->
                        <div class="mb-3">
                            <label for="image_url" class="form-label fw-bold">
                                URL Gambar Cover
                            </label>
                            <input 
                                type="url" 
                                class="form-control @error('image_url') is-invalid @enderror" 
                                id="image_url" 
                                name="image_url" 
                                value="{{ old('image_url', $article->image_url) }}"
                                placeholder="https://example.com/image.jpg"
                            >
                            @error('image_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($article->image_url)
                                <div class="mt-2">
                                    <img src="{{ $article->image_url }}" 
                                         alt="Cover" 
                                         class="img-fluid rounded"
                                         style="max-height: 150px;"
                                         onerror="this.style.display='none'">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <button type="submit" class="btn btn-warning w-100 mb-2">
                            <i class="bi bi-save"></i>
                            Update Artikel
                        </button>
                        <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-x-circle"></i>
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>

@endsection

@push('scripts')
<!-- jQuery (Required for Summernote) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#content').summernote({
            height: 400,
            placeholder: 'Tulis konten artikel Anda di sini...',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endpush
