<style>

.sidebar{
    width:260px;
    height:100vh;
    position:fixed;
    left:0;
    top:0;
    background:#0f172a;
    color:white;
    overflow-y:auto;
    z-index:1000;
}

.sidebar-header{
    padding:25px;
    border-bottom:1px solid rgba(255,255,255,.08);
    text-align:center;
}

.sidebar-header h4{
    margin-bottom:5px;
    font-weight:700;
}

.sidebar-header small{
    color:#94a3b8;
}

.sidebar-menu{
    padding:15px 0;
}

.sidebar-menu .menu-title{
    color:#94a3b8;
    font-size:12px;
    font-weight:bold;
    padding:12px 25px;
    text-transform:uppercase;
}

.sidebar-menu a{
    display:flex;
    align-items:center;
    color:#cbd5e1;
    text-decoration:none;
    padding:12px 25px;
    transition:.3s;
}

.sidebar-menu a:hover{
    background:#1e293b;
    color:white;
}

.sidebar-menu a.active{
    background:#2563eb;
    color:white;
}

.sidebar-menu i{
    width:24px;
    font-size:18px;
    margin-right:10px;
}

.sidebar-footer{
    padding:20px;
    border-top:1px solid rgba(255,255,255,.08);
    color:#94a3b8;
    font-size:12px;
    text-align:center;
}

</style>

<div class="sidebar">

    <div class="sidebar-header">

        <h4>GSCMS</h4>

        <small>Global Supply Chain Monitoring</small>

    </div>

    <div class="sidebar-menu">

        {{-- DASHBOARD --}}
        <div class="menu-title">
            Dashboard
        </div>

        <a href="{{ route('dashboard') }}"
           class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">

            <i class="bi bi-speedometer2"></i>

            Dashboard

        </a>

        {{-- MASTER DATA --}}
        <div class="menu-title">
            Master Data
        </div>

        <a href="{{ route('countries.index') }}"
           class="{{ request()->routeIs('countries.*') ? 'active' : '' }}">

            <i class="bi bi-globe2"></i>

            Data Negara

        </a>

        <a href="#">

            <i class="bi bi-geo-alt-fill"></i>

            Data Pelabuhan

        </a>

        {{-- MONITORING --}}
        <div class="menu-title">
            Monitoring
        </div>

        <a href="{{ route('weather.index') }}"
           class="{{ request()->routeIs('weather.*') ? 'active' : '' }}">

            <i class="bi bi-cloud-sun"></i>

            Monitoring Cuaca

        </a>

        <a href="{{ route('economy.index') }}"
        class="{{ request()->routeIs('economy.*') ? 'active' : '' }}">

        <i class="bi bi-graph-up-arrow"></i>

        Data Ekonomi

        </a>

        <a href="{{ route('exchange.index') }}"
   class="{{ request()->routeIs('exchange.*') ? 'active' : '' }}">

    <i class="bi bi-currency-exchange"></i>

    Nilai Tukar Mata Uang


        </a>

        <a href="{{ route('news.index') }}"
           class="{{ request()->routeIs('news.*') ? 'active' : '' }}">

            <i class="bi bi-newspaper"></i>

            Berita Global

        </a>

        {{-- ANALISIS --}}
        <div class="menu-title">
            Analisis
        </div>

        <a href="{{ route('risk.index') }}"
           class="{{ request()->routeIs('risk.*') ? 'active' : '' }}">

            <i class="bi bi-exclamation-triangle"></i>

            Analisis Risiko

        </a>

        <a href="#">

            <i class="bi bi-bar-chart-line"></i>

            Visualisasi Data

        </a>

        <a href="#">

            <i class="bi bi-arrow-left-right"></i>

            Perbandingan Negara

        </a>

        <a href="#">

            <i class="bi bi-star-fill"></i>

            Negara Favorit

        </a>

        {{-- ADMINISTRASI --}}
        <div class="menu-title">
            Administrasi
        </div>

        <a href="{{ route('admin.sync') }}"
           class="{{ request()->routeIs('admin.sync') ? 'active' : '' }}">

            <i class="bi bi-arrow-repeat"></i>

            Sinkronisasi Data

        </a>

        <a href="#">

            <i class="bi bi-people-fill"></i>

            Manajemen User

        </a>

        <a href="#">

            <i class="bi bi-file-earmark-text"></i>

            Artikel Analisis

        </a>

        <a href="#">

            <i class="bi bi-gear-fill"></i>

            Pengaturan

        </a>

    </div>

    <div class="sidebar-footer">

        <strong>Versi 1.0</strong>

        <br>

        Global Supply Chain Monitoring System

    </div>

</div>