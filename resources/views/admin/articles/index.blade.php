@extends('layouts.admin-portal')

@section('title', 'Manajemen Artikel')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">
                <i class="bi bi-file-earmark-text text-primary"></i>
                Manajemen Artikel Analisis
            </h2>
            <p class="text-muted mb-0">
                Kelola artikel analisis supply chain dan business intelligence.
            </p>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Tulis Artikel Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="bi bi-files text-primary fs-3"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Artikel</h6>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="bi bi-check-circle text-success fs-3"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Diterbitkan</h6>
                            <h3 class="mb-0">{{ $stats['published'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 p-3 rounded">
                                <i class="bi bi-pencil-square text-warning fs-3"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Draft</h6>
                            <h3 class="mb-0">{{ $stats['draft'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-secondary bg-opacity-10 p-3 rounded">
                                <i class="bi bi-archive text-secondary fs-3"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Diarsipkan</h6>
                            <h3 class="mb-0">{{ $stats['archived'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.articles.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input 
                        type="text" 
                        name="search" 
                        class="form-control" 
                        placeholder="Cari judul atau konten..."
                        value="{{ request('search') }}"
                    >
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Diterbitkan</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Articles Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 pb-3">
            <h5 class="mb-0">
                <i class="bi bi-table"></i>
                Daftar Artikel
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4">#</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Penulis</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles as $article)
                        <tr>
                            <td class="px-4">{{ $articles->firstItem() + $loop->index }}</td>
                            <td>
                                <div>
                                    <strong>{{ Str::limit($article->title, 50) }}</strong>
                                    @if($article->image_url)
                                        <span class="badge bg-info text-white ms-1">
                                            <i class="bi bi-image"></i>
                                        </span>
                                    @endif
                                </div>
                                <small class="text-muted">{{ Str::limit($article->excerpt, 80) }}</small>
                            </td>
                            <td>
                                @if($article->category)
                                    <span class="badge bg-primary">{{ $article->category }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $article->author->name ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $article->status_color }}">
                                    {{ $article->status_label }}
                                </span>
                            </td>
                            <td>
                                <i class="bi bi-eye"></i> {{ number_format($article->views) }}
                            </td>
                            <td>
                                <small>{{ $article->created_at->format('d M Y') }}</small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.articles.edit', $article) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip" 
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    @if($article->status === 'draft')
                                        <form action="{{ route('admin.articles.publish', $article) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-success"
                                                    data-bs-toggle="tooltip" 
                                                    title="Publikasikan">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($article->status === 'published')
                                        <form action="{{ route('admin.articles.archive', $article) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-secondary"
                                                    data-bs-toggle="tooltip" 
                                                    title="Arsipkan">
                                                <i class="bi bi-archive"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $article->id }}"
                                            title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal{{ $article->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus artikel <strong>{{ $article->title }}</strong>?
                                        <br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">Belum ada artikel. Mulai tulis artikel pertama Anda!</p>
                                <a href="{{ route('admin.articles.create') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-plus-circle"></i> Tulis Artikel Baru
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($articles->hasPages())
            <div class="card-footer bg-white border-0 pt-3 pb-3">
                {{ $articles->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
@endpush
