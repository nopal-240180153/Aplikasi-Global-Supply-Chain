@extends('layouts.admin-portal')

@section('title', 'Dataset Pelabuhan')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">
                <i class="bi bi-geo-alt text-primary"></i>
                Dataset Pelabuhan
            </h2>
            <p class="text-muted mb-0">
                Kelola daftar pelabuhan dari seluruh dunia secara manual.
            </p>
        </div>
        <a href="{{ route('admin.ports.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Tambah Pelabuhan
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter & Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.ports.index') }}" class="row g-3">
                <div class="col-md-5">
                    <input 
                        type="text" 
                        name="search" 
                        class="form-control" 
                        placeholder="Cari nama pelabuhan, kode, atau negara..."
                        value="{{ request('search') }}"
                    >
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <a href="{{ route('admin.ports.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Ports Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 pb-3">
            <h5 class="mb-0">
                <i class="bi bi-table"></i>
                Daftar Pelabuhan
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4">#</th>
                            <th>Kode</th>
                            <th>Nama Pelabuhan</th>
                            <th>Negara</th>
                            <th>Kota</th>
                            <th>Tipe</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ports as $port)
                        <tr>
                            <td class="px-4">{{ $ports->firstItem() + $loop->index }}</td>
                            <td><span class="badge bg-secondary">{{ $port->port_code ?? '-' }}</span></td>
                            <td class="fw-bold">{{ $port->port_name }}</td>
                            <td>{{ $port->country->name ?? '-' }}</td>
                            <td>{{ $port->city ?? '-' }}</td>
                            <td>{{ $port->type ?? '-' }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.ports.edit', $port) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip" 
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $port->id }}"
                                            title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal{{ $port->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus pelabuhan <strong>{{ $port->port_name }}</strong>?
                                        <br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('admin.ports.destroy', $port) }}" method="POST" class="d-inline">
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
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">Tidak ada data pelabuhan ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($ports->hasPages())
            <div class="card-footer bg-white border-0 pt-3 pb-3">
                {{ $ports->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
@endpush
