@extends('layouts.admin')

@section('title', 'Perbandingan Negara')

@push('styles')
<style>
    .cmp-card { border-radius:12px; background:#fff; box-shadow:0 2px 12px rgba(0,0,0,.07); border:none; }

    /* Selector panel */
    .selector-panel { background:#fff; border-radius:14px; padding:24px 28px; margin-bottom:24px; box-shadow:0 2px 12px rgba(0,0,0,.07); }
    .sel-label { color:#64748b; font-size:.71rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; display:block; margin-bottom:6px; }
    .cmp-select {
        width:100%; height:42px; padding:0 40px 0 14px; border-radius:8px;
        border:1.5px solid #e2e8f0; background-color:#f8fafc; color:#1e293b;
        font-size:.875rem; cursor:pointer; appearance:none;
        background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%2394a3b8' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat:no-repeat; background-position:right 14px center; transition:.2s;
    }
    .cmp-select:focus { outline:none; border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,.15); background-color:#fff; }
    .cmp-select option { background:#fff; color:#1e293b; }

    .vs-badge {
        width:46px; height:46px; border-radius:50%; flex-shrink:0;
        background:#1e293b; color:#fff; font-weight:900; font-size:.9rem;
        display:flex; align-items:center; justify-content:center;
    }
    #btn-compare {
        height:42px; border-radius:8px; font-weight:700; font-size:.85rem;
        background:#3b82f6; border:none; color:#fff; width:100%; cursor:pointer;
        transition:.2s; display:flex; align-items:center; justify-content:center; gap:6px;
    }
    #btn-compare:hover:not(:disabled) { background:#2563eb; transform:translateY(-1px); box-shadow:0 4px 14px rgba(37,99,235,.3); }
    #btn-compare:disabled { background:#e2e8f0; color:#94a3b8; cursor:not-allowed; }

    /* Profile cards */
    .card-a { border-top:4px solid #3b82f6 !important; }
    .card-b { border-top:4px solid #ef4444 !important; }
    .profile-flag     { width:52px; height:34px; object-fit:cover; border-radius:5px; flex-shrink:0; }
    .profile-flag-box { width:52px; height:34px; border-radius:5px; flex-shrink:0; }

    .metric-row { display:flex; align-items:center; padding:8px 0; border-bottom:1px solid #f8fafc; }
    .metric-row:last-child { border-bottom:none; }
    .metric-label { font-size:.8rem; color:#94a3b8; flex:1; }
    .metric-val   { font-weight:600; font-size:.85rem; text-align:right; }
    .val-a { color:#2563eb; }
    .val-b { color:#dc2626; }

    /* Risk badges */
    .risk-tinggi { background:#fef2f2; color:#dc2626; border:1px solid #fecaca; }
    .risk-sedang { background:#fffbeb; color:#d97706; border:1px solid #fde68a; }
    .risk-rendah { background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0; }

    /* Score bars */
    .score-row { display:grid; grid-template-columns:1fr 120px 1fr; gap:10px; align-items:center; padding:10px 0; border-bottom:1px solid #f8fafc; }
    .score-row:last-child { border-bottom:none; }
    .bar-track { height:8px; border-radius:4px; background:#f1f5f9; overflow:hidden; }
    .bar-fill-a { height:100%; background:linear-gradient(90deg,#60a5fa,#2563eb); border-radius:4px; transition:width .6s ease; }
    .bar-fill-b { height:100%; background:linear-gradient(90deg,#f87171,#dc2626); border-radius:4px; float:right; transition:width .6s ease; }
    .sval-a { font-size:.875rem; font-weight:700; color:#2563eb; text-align:right; display:block; margin-bottom:4px; }
    .sval-b { font-size:.875rem; font-weight:700; color:#dc2626; display:block; margin-bottom:4px; }
    .slabel  { font-size:.72rem; color:#64748b; font-weight:600; text-align:center; }

    /* Map & Charts */
    #route-map  { height:360px; border-radius:10px; z-index:1; }
    .chart-wrap { position:relative; height:220px; }

    /* Legend pills */
    .pill-a { background:#eff6ff; color:#1d4ed8; border-radius:6px; padding:3px 10px; font-size:.78rem; font-weight:600; }
    .pill-b { background:#fef2f2; color:#b91c1c; border-radius:6px; padding:3px 10px; font-size:.78rem; font-weight:600; }

    /* Hint */
    .hint-box { text-align:center; padding:80px 20px; }
    .hint-icon { font-size:3rem; color:#cbd5e1; display:block; margin-bottom:14px; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4 px-4">

    {{-- Header --}}
    <div class="mb-4">
        <h2 class="fw-bold mb-1 fs-4 text-dark">
            <i class="bi bi-arrow-left-right text-primary me-2"></i>Country Comparison Engine
        </h2>
        <p class="text-muted small mb-0">Bandingkan dua negara berdasarkan GDP, Inflasi, Nilai Tukar, Cuaca, dan Skor Risiko</p>
    </div>

    {{-- SELECTOR --}}
    <div class="selector-panel">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <span class="sel-label">🏴 Negara Pertama (A)</span>
                <select id="sel-a" class="cmp-select">
                    <option value="">— Pilih Negara A —</option>
                    @foreach($countries as $c)
                        <option value="{{ $c->id }}"
                                data-lat="{{ $c->latitude ?? 0 }}"
                                data-lng="{{ $c->longitude ?? 0 }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 d-flex justify-content-center pb-1">
                <div class="vs-badge">VS</div>
            </div>
            <div class="col-md-5">
                <span class="sel-label">🏴 Negara Kedua (B)</span>
                <select id="sel-b" class="cmp-select">
                    <option value="">— Pilih Negara B —</option>
                    @foreach($countries as $c)
                        <option value="{{ $c->id }}"
                                data-lat="{{ $c->latitude ?? 0 }}"
                                data-lng="{{ $c->longitude ?? 0 }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <button id="btn-compare" disabled>
                    <i class="bi bi-bar-chart-line-fill"></i> Bandingkan
                </button>
            </div>
        </div>
    </div>

    {{-- RESULT --}}
    <div id="result-area">
        <div class="hint-box">
            <i class="bi bi-arrow-left-right hint-icon"></i>
            <div class="fw-semibold fs-6 text-secondary mb-1">Pilih dua negara lalu klik <strong class="text-primary">Bandingkan</strong></div>
            <small class="text-muted">GDP, Inflasi, Nilai Tukar, Cuaca, Risiko, dan peta rute akan muncul di sini</small>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    var charts    = {};
    var leafletMap = null;
    var selA = document.getElementById('sel-a');
    var selB = document.getElementById('sel-b');
    var btn  = document.getElementById('btn-compare');

    /* ── Enable button ──────────────────────────────────────────── */
    function checkReady() {
        var a = selA.value, b = selB.value;
        btn.disabled = !(a && b && a !== b);
    }
    selA.addEventListener('change', checkReady);
    selB.addEventListener('change', checkReady);

    /* ── Fetch ──────────────────────────────────────────────────── */
    btn.addEventListener('click', function () {
        var idA = selA.value, idB = selB.value;
        if (!idA || !idB || idA === idB) return;

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Memuat...';

        var area = document.getElementById('result-area');
        area.innerHTML = '<div class="text-center py-5 text-muted"><div class="spinner-border text-primary"></div><div class="mt-2 small">Memuat data perbandingan...</div></div>';

        fetch('/api/comparison?country_a=' + idA + '&country_b=' + idB)
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (!res.success) throw new Error(res.message || 'Error');
                renderAll(res.data);
            })
            .catch(function(e) {
                area.innerHTML = '<div class="alert alert-danger m-3"><i class="bi bi-exclamation-triangle me-2"></i>' + e.message + '</div>';
            })
            .finally(function() {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-bar-chart-line-fill"></i> Bandingkan';
                checkReady();
            });
    });

    /* ── Master render ──────────────────────────────────────────── */
    function renderAll(d) {
        var a = d.country_a, b = d.country_b;

        Object.values(charts).forEach(function(c) { if (c) c.destroy(); });
        charts = {};
        if (leafletMap) { leafletMap.remove(); leafletMap = null; }

        document.getElementById('result-area').innerHTML = skeleton(a.name, b.name);

        renderProfile('pfA','pnA','pcA','pbA','pmA', a, '#2563eb', 'val-a', 'card-a');
        renderProfile('pfB','pnB','pcB','pbB','pmB', b, '#dc2626', 'val-b', 'card-b');
        renderScoreBars(a, b);
        initMap(a, b);
        renderCharts(d);
    }

    /* ── HTML skeleton ───────────────────────────────────────────── */
    function skeleton(na, nb) {
        return (
        '<div class="row g-4 mb-4">' +
          /* Card A */
          '<div class="col-lg-5">' +
            '<div class="cmp-card card-a p-4 h-100">' +
              '<div class="d-flex align-items-center gap-3 mb-3">' +
                '<div id="pfA"></div>' +
                '<div class="flex-grow-1"><h5 class="fw-bold mb-0" id="pnA"></h5><small class="text-muted" id="pcA"></small></div>' +
                '<span class="badge rounded-pill px-3 py-2" id="pbA"></span>' +
              '</div>' +
              '<div id="pmA"></div>' +
            '</div>' +
          '</div>' +
          /* VS */
          '<div class="col-lg-2 d-flex align-items-center justify-content-center">' +
            '<div style="background:#1e293b;color:#fff;width:54px;height:54px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:1rem">VS</div>' +
          '</div>' +
          /* Card B */
          '<div class="col-lg-5">' +
            '<div class="cmp-card card-b p-4 h-100">' +
              '<div class="d-flex align-items-center gap-3 mb-3">' +
                '<div id="pfB"></div>' +
                '<div class="flex-grow-1"><h5 class="fw-bold mb-0" id="pnB"></h5><small class="text-muted" id="pcB"></small></div>' +
                '<span class="badge rounded-pill px-3 py-2" id="pbB"></span>' +
              '</div>' +
              '<div id="pmB"></div>' +
            '</div>' +
          '</div>' +
        '</div>' +

        /* Score bars */
        '<div class="cmp-card p-4 mb-4">' +
          '<div class="d-flex align-items-center gap-2 mb-3">' +
            '<i class="bi bi-shield-half text-warning"></i>' +
            '<h6 class="fw-bold mb-0">Perbandingan Skor Risiko</h6>' +
            '<div class="ms-auto d-flex gap-2">' +
              '<span class="pill-a">' + na + '</span>' +
              '<span class="pill-b">' + nb + '</span>' +
            '</div>' +
          '</div>' +
          '<div id="score-bars"></div>' +
        '</div>' +

        /* Map */
        '<div class="cmp-card p-4 mb-4">' +
          '<div class="d-flex align-items-center gap-2 mb-3">' +
            '<i class="bi bi-geo-alt-fill text-danger"></i>' +
            '<h6 class="fw-bold mb-0">Peta Rute: <span class="pill-a">' + na + '</span> → <span class="pill-b">' + nb + '</span></h6>' +
          '</div>' +
          '<div id="route-map"></div>' +
        '</div>' +

        /* 4 charts */
        '<div class="row g-4 mb-2">' +
          '<div class="col-xl-6"><div class="cmp-card p-4">' +
            '<h6 class="fw-semibold mb-3"><i class="bi bi-graph-up text-primary me-2"></i>GDP Trend</h6>' +
            '<div class="chart-wrap"><canvas id="chGdp"></canvas></div>' +
          '</div></div>' +
          '<div class="col-xl-6"><div class="cmp-card p-4">' +
            '<h6 class="fw-semibold mb-3"><i class="bi bi-arrow-up-right text-danger me-2"></i>Inflation Trend</h6>' +
            '<div class="chart-wrap"><canvas id="chInfl"></canvas></div>' +
          '</div></div>' +
          '<div class="col-xl-6"><div class="cmp-card p-4">' +
            '<h6 class="fw-semibold mb-3"><i class="bi bi-currency-exchange text-success me-2"></i>Exchange Rate Trend</h6>' +
            '<div class="chart-wrap"><canvas id="chExch"></canvas></div>' +
          '</div></div>' +
          '<div class="col-xl-6"><div class="cmp-card p-4">' +
            '<h6 class="fw-semibold mb-3"><i class="bi bi-shield-exclamation text-warning me-2"></i>Risk Score Trend</h6>' +
            '<div class="chart-wrap"><canvas id="chRisk"></canvas></div>' +
          '</div></div>' +
        '</div>'
        );
    }

    /* ── Profile ─────────────────────────────────────────────────── */
    function renderProfile(fId, nId, cId, bId, mId, p, color, valCls, cardCls) {
        var fEl = document.getElementById(fId);
        if (fEl) {
            fEl.innerHTML = p.flag
                ? '<img src="'+p.flag+'" class="profile-flag" alt="'+p.name+'" onerror="this.style.display=\'none\'">'
                : '<div class="profile-flag-box" style="background:'+color+'"></div>';
        }
        setText(nId, p.name);
        setText(cId, (p.continent||'-') + ' · ' + (p.capital||'-'));

        var bEl = document.getElementById(bId);
        if (bEl) {
            var lvl = p.risk_level || '-';
            var bc  = lvl === 'Tinggi' ? 'risk-tinggi' : lvl === 'Sedang' ? 'risk-sedang' : 'risk-rendah';
            bEl.className = 'badge rounded-pill px-3 py-2 ' + bc;
            bEl.textContent = lvl + ' · ' + (p.total_score || 0);
        }

        var mEl = document.getElementById(mId);
        if (!mEl) return;
        var rows = [
            ['GDP',        p.gdp || '-'],
            ['Inflasi',    p.inflation || '-'],
            ['Nilai Tukar',p.exchange_rate ? p.exchange_rate + ' (' + (p.base_currency||'') + '→' + (p.target_currency||'') + ')' : '-'],
            ['Suhu',       p.temperature || '-'],
            ['Kelembaban', p.humidity || '-'],
            ['Angin',      p.wind_speed || '-'],
            ['Cuaca',      p.weather_condition || '-'],
            ['Berita',     (p.news_count || 0) + ' artikel'],
        ];
        mEl.innerHTML = rows.map(function(r) {
            return '<div class="metric-row">' +
                   '<span class="metric-label">'+r[0]+'</span>' +
                   '<span class="metric-val '+valCls+'">'+r[1]+'</span></div>';
        }).join('');
    }

    /* ── Score bars ──────────────────────────────────────────────── */
    function renderScoreBars(a, b) {
        var el = document.getElementById('score-bars');
        if (!el) return;
        var inds = [
            ['Skor Cuaca',   'weather_score'],
            ['Skor Ekonomi', 'economy_score'],
            ['Skor Kurs',    'exchange_score'],
            ['Skor Berita',  'news_score'],
            ['Total Risiko', 'total_score'],
        ];
        el.innerHTML = inds.map(function(ind) {
            var va = +(a[ind[1]] || 0), vb = +(b[ind[1]] || 0);
            var mx = Math.max(va, vb, 0.01);
            return '<div class="score-row">' +
              '<div>' +
                '<span class="sval-a">'+va+'</span>' +
                '<div class="bar-track"><div class="bar-fill-a" style="width:'+((va/mx)*100).toFixed(1)+'%"></div></div>' +
              '</div>' +
              '<div class="slabel">'+ind[0]+'</div>' +
              '<div>' +
                '<span class="sval-b">'+vb+'</span>' +
                '<div class="bar-track"><div class="bar-fill-b" style="width:'+((vb/mx)*100).toFixed(1)+'%"></div></div>' +
              '</div>' +
            '</div>';
        }).join('');
    }

    /* ── Map ─────────────────────────────────────────────────────── */
    function initMap(a, b) {
        var la = +a.latitude||0, lo = +a.longitude||0;
        var lb = +b.latitude||0, lob = +b.longitude||0;

        leafletMap = L.map('route-map');
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors', maxZoom: 18,
        }).addTo(leafletMap);

        function mkIcon(lbl, bg) {
            return L.divIcon({
                className: '',
                html: '<div style="background:'+bg+';color:#fff;border-radius:50%;width:40px;height:40px;'+
                      'display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.8rem;'+
                      'border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.3)">'+lbl+'</div>',
                iconSize:[40,40], iconAnchor:[20,40],
            });
        }

        L.marker([la,lo],  {icon:mkIcon('A','#2563eb')}).addTo(leafletMap)
            .bindPopup('<b style="color:#2563eb">'+a.name+'</b><br>'+a.risk_level+' · '+a.total_score+'<br>GDP: '+a.gdp);
        L.marker([lb,lob], {icon:mkIcon('B','#dc2626')}).addTo(leafletMap)
            .bindPopup('<b style="color:#dc2626">'+b.name+'</b><br>'+b.risk_level+' · '+b.total_score+'<br>GDP: '+b.gdp);

        var pts = gcPts([la,lo],[lb,lob],50);
        L.polyline(pts, {color:'#6366f1', weight:2.5, dashArray:'10 6', opacity:.8}).addTo(leafletMap);
        leafletMap.fitBounds([[la,lo],[lb,lob]], {padding:[70,70]});
    }

    function gcPts(s, e, n) {
        var r=function(d){return d*Math.PI/180;}, d2=function(x){return x*180/Math.PI;};
        var la1=r(s[0]),lo1=r(s[1]),la2=r(e[0]),lo2=r(e[1]);
        var D=2*Math.asin(Math.sqrt(Math.pow(Math.sin((la2-la1)/2),2)+Math.cos(la1)*Math.cos(la2)*Math.pow(Math.sin((lo2-lo1)/2),2)));
        if (D<.001) return [s,e];
        var pts=[];
        for(var i=0;i<=n;i++){
            var f=i/n,A=Math.sin((1-f)*D)/Math.sin(D),B=Math.sin(f*D)/Math.sin(D);
            var x=A*Math.cos(la1)*Math.cos(lo1)+B*Math.cos(la2)*Math.cos(lo2);
            var y=A*Math.cos(la1)*Math.sin(lo1)+B*Math.cos(la2)*Math.sin(lo2);
            var z=A*Math.sin(la1)+B*Math.sin(la2);
            pts.push([d2(Math.atan2(z,Math.sqrt(x*x+y*y))),d2(Math.atan2(y,x))]);
        }
        return pts;
    }

    /* ── Charts ──────────────────────────────────────────────────── */
    function renderCharts(d) {
        Chart.defaults.font.family = "'Segoe UI',sans-serif";
        Chart.defaults.font.size   = 11;
        Chart.defaults.color       = '#64748b';
        var opts = {
            responsive:true, maintainAspectRatio:false,
            interaction:{mode:'index',intersect:false},
            plugins:{legend:{position:'bottom',labels:{boxWidth:12,padding:10,font:{size:10}}}},
            scales:{
                x:{grid:{display:false}},
                y:{grid:{color:'#f0f4f8'}, beginAtZero:false}
            },
        };
        function mk(id, data) {
            // Convert datasets to bar style: add borderRadius, remove fill/tension
            var barData = JSON.parse(JSON.stringify(data));
            barData.datasets = barData.datasets.map(function(ds) {
                return Object.assign({}, ds, {
                    borderRadius: 6,
                    borderSkipped: false,
                    fill: false,
                    tension: 0,
                    pointRadius: 0,
                    backgroundColor: ds.backgroundColor || ds.borderColor,
                });
            });
            var el = document.getElementById(id);
            if (el) charts[id] = new Chart(el.getContext('2d'), {type:'bar', data:barData, options:opts});
        }
        mk('chGdp',  d.gdp_trend);
        mk('chInfl', d.inflation_trend);
        mk('chExch', d.exchange_trend);
        mk('chRisk', d.risk_trend);
    }

    function setText(id, val) {
        var el = document.getElementById(id);
        if (el) el.textContent = val || '-';
    }

})();
</script>
@endpush
