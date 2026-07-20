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

        <h4>ADMIN PORTAL</h4>
        <small>Control Panel GSCMS</small>
    </div>

    <div class="sidebar-menu">

        <div class="menu-title">
            Navigasi
        </div>

        <a href="{{ route('dashboard') }}">
            <i class="bi bi-arrow-left-circle"></i>
            Kembali ke Aplikasi Utama
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

        <a href="{{ route('admin.lexicon') }}"
           class="{{ request()->routeIs('admin.lexicon*') ? 'active' : '' }}">

            <i class="bi bi-book"></i>

            Kamus Berita

        </a>

        <a href="{{ route('admin.users.index') }}"
           class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">

            <i class="bi bi-people-fill"></i>

            Manajemen User

        </a>

        <a href="{{ route('admin.articles.index') }}"
           class="{{ request()->routeIs('admin.articles*') ? 'active' : '' }}">

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