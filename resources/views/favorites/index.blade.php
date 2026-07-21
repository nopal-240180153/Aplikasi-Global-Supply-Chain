@extends('layouts.admin')

@section('title', 'Negara Favorit')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-star-fill text-warning me-2"></i>Negara Favorit</h2>
            <p class="text-muted mb-0">Daftar negara pantauan khusus Anda.</p>
        </div>
        <a href="{{ route('countries.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Pantauan
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">

        <div class="card-header bg-white border-0 pt-4 pb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Daftar Pantauan</h5>
                @if($favorites->count() > 0)
                    <span class="badge bg-warning text-dark">{{ $favorites->count() }} Negara</span>
                @endif
            </div>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>Bendera</th>
                            <th>Negara</th>
                            <th>Ibukota</th>
                            <th>Region</th>
                            <th>Suhu</th>
                            <th>Kondisi Cuaca</th>
                            <th>Tingkat Risiko</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($favorites as $fav)
                        @php
                            $country = $fav->country;
                        @endphp
                        @if($country)
                        <tr>
                            <td width="70">
                                @if($country->flag)
                                    <img src="{{ $country->flag }}" width="40" class="rounded border">
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('countries.show', $country->id) }}" class="text-decoration-none fw-bold text-dark">
                                    {{ $country->name }}
                                </a>
                            </td>
                            <td>{{ $country->capital ?? '-' }}</td>
                            <td><span class="badge bg-light text-secondary border">{{ $country->region ?? '-' }}</span></td>
                            <td>
                                @if($country->weatherLogs()->latest()->first())
                                    {{ $country->weatherLogs()->latest()->first()->temperature }} °C
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($country->weatherLogs()->latest()->first())
                                    <small>{{ $country->weatherLogs()->latest()->first()->weather_condition }}</small>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($country->risk_level == 'Rendah')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success">Rendah</span>
                                @elseif($country->risk_level == 'Sedang')
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">Sedang</span>
                                @elseif($country->risk_level == 'Tinggi')
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">Tinggi</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('countries.show', $country->id) }}" 
                                       class="btn btn-sm btn-light text-primary" 
                                       title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-light text-danger btn-remove-favorite" 
                                            data-id="{{ $country->id }}"
                                            title="Hapus dari Pantauan">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-star d-block mb-3" style="font-size: 3rem; color: #dee2e6;"></i>
                                    <h6 class="fw-bold">Belum Ada Pantauan</h6>
                                    <p class="small mb-3">Anda belum menambahkan negara ke daftar favorit.</p>
                                    <a href="{{ route('countries.index') }}" class="btn btn-primary btn-sm px-4 rounded-pill">Mulai Pantau Negara</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const removeBtns = document.querySelectorAll('.btn-remove-favorite');
    
    removeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const countryId = this.getAttribute('data-id');
            const row = this.closest('tr');
            
            if(!confirm('Hapus negara ini dari daftar pantauan?')) return;
            
            const originalHtml = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            this.disabled = true;

            fetch('{{ route("favorites.toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    country_id: countryId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.status === 'removed') {
                    // Animasi hapus baris
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '0';
                    setTimeout(() => {
                        row.remove();
                        // Refresh halaman jika kosong
                        if(document.querySelectorAll('tbody tr').length === 0) {
                            window.location.reload();
                        }
                    }, 300);
                } else {
                    this.innerHTML = originalHtml;
                    this.disabled = false;
                    alert('Gagal menghapus: ' + (data.message || 'Error'));
                }
            })
            .catch(error => {
                this.innerHTML = originalHtml;
                this.disabled = false;
                console.error('Error:', error);
                alert('Terjadi kesalahan jaringan.');
            });
        });
    });
});
</script>
@endpush
