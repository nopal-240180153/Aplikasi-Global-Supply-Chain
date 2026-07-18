@extends('layouts.admin')

@section('title', 'Kamus Berita')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="mb-4">
        <h2 class="fw-bold">
            <i class="bi bi-book text-primary"></i>
            Kamus Berita
        </h2>
        <p class="text-muted mb-0">
            Kelola kata-kata positif dan negatif untuk analisis sentimen berita secara otomatis.
        </p>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Alert Error -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle-fill"></i>
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Info Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-light">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-1 text-center">
                    <i class="bi bi-info-circle fs-1 text-primary"></i>
                </div>
                <div class="col-md-11">
                    <h6 class="fw-bold mb-2">Cara Kerja Analisis Sentimen</h6>
                    <p class="mb-0 text-muted small">
                        Sistem akan menghitung jumlah <strong>kata positif</strong> dan <strong>kata negatif</strong> 
                        dalam judul dan deskripsi berita. Berita dengan lebih banyak kata positif akan diberi label 
                        <span class="badge bg-success">Positif</span>, sedangkan yang lebih banyak kata negatif 
                        diberi label <span class="badge bg-danger">Negatif</span>. Jika jumlahnya sama atau tidak ada, 
                        akan diberi label <span class="badge bg-warning text-dark">Netral</span>.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <!-- KATA POSITIF -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-success text-white border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-emoji-smile"></i> Kata Positif
                        </h5>
                        <span class="badge bg-white text-success">
                            {{ $positiveWords->total() }} Kata
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    
                    <!-- Form Tambah -->
                    <form method="POST" action="{{ route('admin.lexicon.positive.store') }}" class="mb-4">
                        @csrf
                        <div class="input-group">
                            <input 
                                type="text" 
                                name="word" 
                                class="form-control" 
                                placeholder="Tambah kata positif baru..."
                                required>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-plus-lg"></i> Tambah
                            </button>
                        </div>
                        <small class="text-muted">
                            Contoh: sukses, meningkat, untung, stabil, ekspansi, dll.
                        </small>
                    </form>

                    <!-- List Kata -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="60">#</th>
                                    <th>Kata</th>
                                    <th width="100" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($positiveWords as $word)
                                    <tr>
                                        <td>{{ $loop->iteration + ($positiveWords->currentPage()-1) * $positiveWords->perPage() }}</td>
                                        <td>
                                            <span class="badge bg-success-subtle text-success">
                                                {{ $word->word }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <form 
                                                method="POST" 
                                                action="{{ route('admin.lexicon.positive.destroy', $word) }}"
                                                onsubmit="return confirm('Yakin ingin menghapus kata ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            Belum ada kata positif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($positiveWords->hasPages())
                        <div class="mt-3">
                            {{ $positiveWords->appends(['negative_page' => request('negative_page')])->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <!-- KATA NEGATIF -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-danger text-white border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-emoji-frown"></i> Kata Negatif
                        </h5>
                        <span class="badge bg-white text-danger">
                            {{ $negativeWords->total() }} Kata
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    
                    <!-- Form Tambah -->
                    <form method="POST" action="{{ route('admin.lexicon.negative.store') }}" class="mb-4">
                        @csrf
                        <div class="input-group">
                            <input 
                                type="text" 
                                name="word" 
                                class="form-control" 
                                placeholder="Tambah kata negatif baru..."
                                required>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-plus-lg"></i> Tambah
                            </button>
                        </div>
                        <small class="text-muted">
                            Contoh: krisis, perang, turun, resesi, bencana, dll.
                        </small>
                    </form>

                    <!-- List Kata -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="60">#</th>
                                    <th>Kata</th>
                                    <th width="100" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($negativeWords as $word)
                                    <tr>
                                        <td>{{ $loop->iteration + ($negativeWords->currentPage()-1) * $negativeWords->perPage() }}</td>
                                        <td>
                                            <span class="badge bg-danger-subtle text-danger">
                                                {{ $word->word }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <form 
                                                method="POST" 
                                                action="{{ route('admin.lexicon.negative.destroy', $word) }}"
                                                onsubmit="return confirm('Yakin ingin menghapus kata ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            Belum ada kata negatif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($negativeWords->hasPages())
                        <div class="mt-3">
                            {{ $negativeWords->appends(['positive_page' => request('positive_page')])->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>

</div>

@endsection
