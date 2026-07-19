@extends('layouts.admin')

@section('title', 'Visualisasi Data Global Supply Chain')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
    .filter-card { border-radius: 12px; background: #fff; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
    .chart-card  { border-radius: 12px; background: #fff; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
    .chart-card .card-header { background: transparent; border-bottom: 1px solid #f1f5f9; border-radius: 12px 12px 0 0; }
    .chart-card .chart-icon { width: 32px; height: 32px; border-radius: 8px; display:inline-flex; align-items:center; justify-content:center; }
    .chart-canvas-wrap { position: relative; height: 280px; }

    /* Table */
    .tbl-summary th { font-size: .78rem; text-transform: uppercase; letter-spacing: .05em; color: #64748b; border-top: none; }
    .tbl-summary td { font-size: .875rem; vertical-align: middle; }
    .tbl-summary th.sortable { cursor: pointer; user-select: none; }
    .tbl-summary th.sortable:hover { color: #2563eb; }
    .sort-icon { font-size: .7rem; margin-left: 4px; color: #94a3b8; }
    .sort-icon.active { color: #2563eb; }

    .badge-risk-tinggi  { background: #fee2e2; color: #dc2626; }
    .badge-risk-sedang  { background: #fef3c7; color: #d97706; }
    .badge-risk-rendah  { background: #dcfce7; color: #16a34a; }

    .stat-pill { display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 999px; font-size: .82rem; font-weight: 600; }

    /* Pagination */
    .page-btn { min-width: 36px; height: 36px; border-radius: 8px; border: 1px solid #e2e8f0; background:#fff; color:#374151; font-size:.85rem; transition:.2s; }
    .page-btn:hover, .page-btn.active { background:#2563eb; color:#fff; border-color:#2563eb; }
    .page-btn:disabled { opacity:.4; cursor:default; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4 px-4">

    {{-- ========== HEADER ========== --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark fs-4">
                <i class="bi bi-bar-chart-line text-primary me-2"></i>Visualisasi Data
            </h2>
            <p class="text-muted mb-0 small">Dashboard analitik komprehensif rantai pasok global</p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span class="stat-pill bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-clock-history"></i>
                <span id="last-updated">Memuat...</span>
            </span>
        </div>
    </div>

    {{-- ========== FILTER PANEL ========== --}}
    <div class="filter-card p-4 mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-lg-3 col-md-6">
                <label class="form-label fw-semibold small text-muted mb-1">
                    <i class="bi bi-globe2 me-1"></i>Negara
                </label>
                <select id="f-country" class="form-select form-select-sm">
                    <option value="">— Semua Negara —</option>
                    @foreach($countries as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-6">
                <label class="form-label fw-semibold small text-muted mb-1">
                    <i class="bi bi-map me-1"></i>Benua
                </label>
                <select id="f-continent" class="form-select form-select-sm">
                    <option value="">— Semua Benua —</option>
                    <option value="Asia">Asia</option>
                    <option value="Europe">Eropa</option>
                    <option value="Africa">Afrika</option>
                    <option value="Americas">Amerika</option>
                    <option value="Oceania">Oceania</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <label class="form-label fw-semibold small text-muted mb-1">
                    <i class="bi bi-calendar-range me-1"></i>Tahun Mulai
                </label>
                <select id="f-year-start" class="form-select form-select-sm">
                    @for($y = 2018; $y <= date('Y'); $y++)
                        <option value="{{ $y }}" {{ $y == 2018 ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <label class="form-label fw-semibold small text-muted mb-1">
                    <i class="bi bi-calendar-check me-1"></i>Tahun Akhir
                </label>
                <select id="f-year-end" class="form-select form-select-sm">
                    @for($y = 2018; $y <= date('Y'); $y++)
                        <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-lg-2 col-md-6 d-flex gap-2">
                <button id="btn-filter" class="btn btn-primary btn-sm flex-grow-1">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                <button id="btn-reset" class="btn btn-outline-secondary btn-sm px-3">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- ========== CHARTS 2x2 GRID ========== --}}
    <div class="row g-4 mb-4">

        {{-- GDP Trend --}}
        <div class="col-xl-6 col-lg-6">
            <div class="chart-card h-100">
                <div class="card-header d-flex align-items-center gap-2 px-4 py-3">
                    <span class="chart-icon bg-primary bg-opacity-10">
                        <i class="bi bi-graph-up text-primary fs-6"></i>
                    </span>
                    <div class="flex-grow-1">
                        <div class="fw-semibold text-dark small mb-0">GDP Trend</div>
                        <div class="text-muted" style="font-size:.72rem">Total GDP per tahun (USD)</div>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary small" id="gdp-label">All</span>
                </div>
                <div class="card-body px-4 pb-4 pt-3">
                    <div class="chart-canvas-wrap">
                        <canvas id="chart-gdp"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Inflation Trend --}}
        <div class="col-xl-6 col-lg-6">
            <div class="chart-card h-100">
                <div class="card-header d-flex align-items-center gap-2 px-4 py-3">
                    <span class="chart-icon bg-danger bg-opacity-10">
                        <i class="bi bi-arrow-up-right text-danger fs-6"></i>
                    </span>
                    <div class="flex-grow-1">
                        <div class="fw-semibold text-dark small mb-0">Inflation Trend</div>
                        <div class="text-muted" style="font-size:.72rem">Rata-rata laju inflasi per tahun (%)</div>
                    </div>
                    <span class="badge bg-danger bg-opacity-10 text-danger small" id="infl-label">All</span>
                </div>
                <div class="card-body px-4 pb-4 pt-3">
                    <div class="chart-canvas-wrap">
                        <canvas id="chart-inflation"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Currency Trend --}}
        <div class="col-xl-6 col-lg-6">
            <div class="chart-card h-100">
                <div class="card-header d-flex align-items-center gap-2 px-4 py-3">
                    <span class="chart-icon bg-success bg-opacity-10">
                        <i class="bi bi-currency-exchange text-success fs-6"></i>
                    </span>
                    <div class="flex-grow-1">
                        <div class="fw-semibold text-dark small mb-0">Currency Trend</div>
                        <div class="text-muted" style="font-size:.72rem">Rata-rata nilai tukar per bulan</div>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success small" id="curr-label">All</span>
                </div>
                <div class="card-body px-4 pb-4 pt-3">
                    <div class="chart-canvas-wrap">
                        <canvas id="chart-currency"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Risk Trend --}}
        <div class="col-xl-6 col-lg-6">
            <div class="chart-card h-100">
                <div class="card-header d-flex align-items-center gap-2 px-4 py-3">
                    <span class="chart-icon bg-warning bg-opacity-10">
                        <i class="bi bi-shield-exclamation text-warning fs-6"></i>
                    </span>
                    <div class="flex-grow-1">
                        <div class="fw-semibold text-dark small mb-0">Risk Score Trend</div>
                        <div class="text-muted" style="font-size:.72rem">Perkembangan skor risiko per bulan</div>
                    </div>
                    <span class="badge bg-warning bg-opacity-10 text-warning small" id="risk-label">All</span>
                </div>
                <div class="card-body px-4 pb-4 pt-3">
                    <div class="chart-canvas-wrap">
                        <canvas id="chart-risk"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ========== SUMMARY TABLE ========== --}}
    <div class="chart-card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between px-4 py-3">
            <div class="d-flex align-items-center gap-2">
                <span class="chart-icon bg-info bg-opacity-10">
                    <i class="bi bi-table text-info fs-6"></i>
                </span>
                <div>
                    <div class="fw-semibold text-dark small mb-0">Ringkasan Data</div>
                    <div class="text-muted" style="font-size:.72rem">Klik header kolom untuk sorting</div>
                </div>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <div class="input-group input-group-sm" style="width:220px">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" id="tbl-search" class="form-control border-start-0 ps-0"
                           placeholder="Cari negara...">
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table tbl-summary mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="sortable ps-4" data-col="country">
                                Negara <i class="bi bi-arrow-down-up sort-icon" id="si-country"></i>
                            </th>
                            <th>GDP</th>
                            <th>Inflasi</th>
                            <th>Nilai Tukar</th>
                            <th class="sortable" data-col="weather_score">
                                Skor Cuaca <i class="bi bi-arrow-down-up sort-icon" id="si-weather_score"></i>
                            </th>
                            <th class="sortable" data-col="economy_score">
                                Skor Ekonomi <i class="bi bi-arrow-down-up sort-icon" id="si-economy_score"></i>
                            </th>
                            <th class="sortable" data-col="exchange_score">
                                Skor Kurs <i class="bi bi-arrow-down-up sort-icon" id="si-exchange_score"></i>
                            </th>
                            <th class="sortable" data-col="news_score">
                                Skor Berita <i class="bi bi-arrow-down-up sort-icon" id="si-news_score"></i>
                            </th>
                            <th class="sortable" data-col="total_score">
                                Total <i class="bi bi-arrow-down-up sort-icon active" id="si-total_score"></i>
                            </th>
                            <th class="sortable pe-4" data-col="risk_level">
                                Level <i class="bi bi-arrow-down-up sort-icon" id="si-risk_level"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tbl-body">
                        <tr><td colspan="10" class="text-center py-5 text-muted">
                            <div class="spinner-border spinner-border-sm text-primary me-2"></div>Memuat data...
                        </td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 d-flex align-items-center justify-content-between px-4 py-3">
            <small class="text-muted" id="tbl-info">—</small>
            <div id="tbl-pagination" class="d-flex gap-1"></div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
(function () {
    'use strict';

    // ── Chart instances ──────────────────────────────────────────────────────
    let cGdp, cInfl, cCurr, cRisk;

    // ── Table state ──────────────────────────────────────────────────────────
    let tblState = {
        search  : '',
        sortBy  : 'total_score',
        sortDir : 'desc',
        page    : 1,
        perPage : 15,
    };

    // ── Filter helpers ───────────────────────────────────────────────────────
    function getFilters() {
        return {
            country_id : document.getElementById('f-country').value,
            continent  : document.getElementById('f-continent').value,
            year_start : document.getElementById('f-year-start').value,
            year_end   : document.getElementById('f-year-end').value,
        };
    }

    function buildParams(extra = {}) {
        return new URLSearchParams({ ...getFilters(), ...extra }).toString();
    }

    // ── Chart.js defaults ────────────────────────────────────────────────────
    Chart.defaults.font.family = "'Inter','Segoe UI',sans-serif";
    Chart.defaults.font.size   = 11;
    Chart.defaults.color       = '#64748b';

    const lineOpts = (xLabel = 'Tahun', yLabel = '') => ({
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { display: false },
            tooltip: { mode: 'index', intersect: false },
        },
        scales: {
            x: { grid: { display: false }, title: { display: !!xLabel, text: xLabel, font:{size:10} } },
            y: { grid: { color: '#f1f5f9' }, title: { display: !!yLabel, text: yLabel, font:{size:10} } },
        },
    });

    function makeChart(id, opts) {
        const ctx = document.getElementById(id).getContext('2d');
        return new Chart(ctx, opts);
    }

    function destroyAll() {
        [cGdp, cInfl, cCurr, cRisk].forEach(c => c && c.destroy());
    }

    // ── Loading state for charts ─────────────────────────────────────────────
    function showChartLoading(id) {
        const ctx = document.getElementById(id).getContext('2d');
        ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
        ctx.fillStyle = '#94a3b8';
        ctx.font = '12px Inter,sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('Memuat data...', ctx.canvas.width/2, ctx.canvas.height/2);
    }

    // ── Load all 4 charts ────────────────────────────────────────────────────
    function loadCharts() {
        ['chart-gdp','chart-inflation','chart-currency','chart-risk'].forEach(showChartLoading);
        destroyAll();

        fetch('/api/visualizations/trend-data?' + buildParams())
            .then(r => r.json())
            .then(res => {
                if (!res.success) return;
                const d = res.data;

                // Label badges
                const f = getFilters();
                const lbl = f.country_id
                    ? document.querySelector('#f-country option:checked')?.text || 'Negara'
                    : f.continent || 'All';
                ['gdp-label','infl-label','curr-label','risk-label'].forEach(id => {
                    document.getElementById(id).textContent = lbl;
                });
                document.getElementById('last-updated').textContent =
                    'Update: ' + new Date().toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'});

                // --- GDP ---
                cGdp = makeChart('chart-gdp', {
                    type: 'line',
                    data: d.gdp,
                    options: lineOpts('Tahun', 'GDP (USD)'),
                });

                // --- Inflation ---
                cInfl = makeChart('chart-inflation', {
                    type: 'line',
                    data: d.inflation,
                    options: lineOpts('Tahun', 'Inflasi (%)'),
                });

                // --- Currency ---
                cCurr = makeChart('chart-currency', {
                    type: 'line',
                    data: d.currency,
                    options: lineOpts('Bulan', 'Nilai Tukar'),
                });

                // --- Risk (multi-line) ---
                const riskOpts = lineOpts('Bulan', 'Skor');
                riskOpts.plugins.legend = { display: true, position: 'bottom', labels:{boxWidth:12,padding:12,font:{size:10}} };
                cRisk = makeChart('chart-risk', {
                    type: 'line',
                    data: d.risk,
                    options: riskOpts,
                });
            })
            .catch(() => {});
    }

    // ── Load table ───────────────────────────────────────────────────────────
    function loadTable(page = null) {
        if (page !== null) tblState.page = page;

        const params = buildParams({
            search   : tblState.search,
            sort_by  : tblState.sortBy,
            sort_dir : tblState.sortDir,
            page     : tblState.page,
            per_page : tblState.perPage,
        });

        document.getElementById('tbl-body').innerHTML =
            '<tr><td colspan="10" class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Memuat...</td></tr>';

        fetch('/api/visualizations/trend-summary?' + params)
            .then(r => r.json())
            .then(res => {
                if (!res.success) return;

                const tbody = document.getElementById('tbl-body');
                if (!res.data.length) {
                    tbody.innerHTML = '<tr><td colspan="10" class="text-center py-5 text-muted">Tidak ada data ditemukan.</td></tr>';
                    document.getElementById('tbl-info').textContent = '0 data';
                    document.getElementById('tbl-pagination').innerHTML = '';
                    return;
                }

                tbody.innerHTML = res.data.map(row => {
                    const lvlClass = row.risk_level === 'Tinggi' ? 'badge-risk-tinggi'
                                   : row.risk_level === 'Sedang' ? 'badge-risk-sedang'
                                   : 'badge-risk-rendah';
                    return `<tr>
                        <td class="ps-4 fw-semibold">${row.country}</td>
                        <td class="text-muted">${row.gdp}</td>
                        <td class="text-muted">${row.inflation}</td>
                        <td class="text-muted">${row.exchange_rate}</td>
                        <td>${scoreBar(row.weather_score)}</td>
                        <td>${scoreBar(row.economy_score)}</td>
                        <td>${scoreBar(row.exchange_score)}</td>
                        <td>${scoreBar(row.news_score)}</td>
                        <td class="fw-bold">${row.total_score}</td>
                        <td class="pe-4"><span class="badge ${lvlClass} px-2 py-1 rounded-2">${row.risk_level}</span></td>
                    </tr>`;
                }).join('');

                // Info
                const from = (res.page - 1) * res.per_page + 1;
                const to   = Math.min(res.page * res.per_page, res.total);
                document.getElementById('tbl-info').textContent =
                    `Menampilkan ${from}–${to} dari ${res.total} data`;

                // Pagination
                renderPagination(res.page, res.last_page);
            })
            .catch(() => {});
    }

    function scoreBar(val) {
        const color = val >= 35 ? '#ef4444' : val >= 20 ? '#f59e0b' : '#22c55e';
        return `<div class="d-flex align-items-center gap-2">
            <div style="width:50px;height:6px;background:#f1f5f9;border-radius:3px;">
                <div style="width:${Math.min(val,100)}%;height:100%;background:${color};border-radius:3px;"></div>
            </div>
            <span style="font-size:.8rem">${val}</span>
        </div>`;
    }

    function renderPagination(current, last) {
        const el = document.getElementById('tbl-pagination');
        let html = '';
        html += `<button class="page-btn" onclick="goPage(${current-1})" ${current===1?'disabled':''}>
                    <i class="bi bi-chevron-left" style="font-size:.7rem"></i>
                 </button>`;

        const start = Math.max(1, current-2);
        const end   = Math.min(last, current+2);
        for (let p = start; p <= end; p++) {
            html += `<button class="page-btn ${p===current?'active':''}" onclick="goPage(${p})">${p}</button>`;
        }

        html += `<button class="page-btn" onclick="goPage(${current+1})" ${current===last?'disabled':''}>
                    <i class="bi bi-chevron-right" style="font-size:.7rem"></i>
                 </button>`;
        el.innerHTML = html;
    }

    window.goPage = function(p) {
        loadTable(p);
    };

    // ── Sorting ──────────────────────────────────────────────────────────────
    document.querySelectorAll('.tbl-summary th.sortable').forEach(th => {
        th.addEventListener('click', function () {
            const col = this.dataset.col;
            if (tblState.sortBy === col) {
                tblState.sortDir = tblState.sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                tblState.sortBy  = col;
                tblState.sortDir = 'desc';
            }
            // Update icons
            document.querySelectorAll('.sort-icon').forEach(i => i.classList.remove('active'));
            const icon = document.getElementById('si-' + col);
            if (icon) {
                icon.classList.add('active');
                icon.className = icon.className.replace('arrow-down-up', tblState.sortDir === 'asc' ? 'arrow-up' : 'arrow-down');
            }
            loadTable(1);
        });
    });

    // ── Search debounce ──────────────────────────────────────────────────────
    let searchTimer;
    document.getElementById('tbl-search').addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            tblState.search = this.value.trim();
            loadTable(1);
        }, 400);
    });

    // ── Filter buttons ───────────────────────────────────────────────────────
    document.getElementById('btn-filter').addEventListener('click', () => {
        loadCharts();
        loadTable(1);
    });

    document.getElementById('btn-reset').addEventListener('click', () => {
        document.getElementById('f-country').value   = '';
        document.getElementById('f-continent').value = '';
        document.getElementById('f-year-start').value = '2018';
        document.getElementById('f-year-end').value   = '{{ date("Y") }}';
        tblState.search  = '';
        tblState.sortBy  = 'total_score';
        tblState.sortDir = 'desc';
        document.getElementById('tbl-search').value = '';
        loadCharts();
        loadTable(1);
    });

    // ── Select2 for country ──────────────────────────────────────────────────
    $('#f-country').select2({ placeholder: '— Semua Negara —', allowClear: true, width: '100%' });

    // ── Init ─────────────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        loadCharts();
        loadTable(1);
    });

})();
</script>
@endpush
