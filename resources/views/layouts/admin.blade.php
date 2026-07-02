<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Global Supply Chain Monitoring')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            background:#f4f6f9;
            font-family:'Segoe UI',sans-serif;
        }

        .wrapper{
            display:flex;
        }

        .main-content{
            margin-left:260px;
            width:100%;
            min-height:100vh;
            display:flex;
            flex-direction:column;
        }

        .content{
            padding:25px;
            flex:1;
        }

        .card-dashboard{

            border:none;
            border-radius:15px;
            box-shadow:0 3px 10px rgba(0,0,0,.08);

        }

        .page-title{

            font-size:28px;
            font-weight:bold;
            margin-bottom:25px;

        }

    </style>

    @stack('styles')

</head>

<body>

<div class="wrapper">

    @include('layouts.sidebar')

    <div class="main-content">

        @include('layouts.navbar')

        <div class="content">

            @yield('content')

        </div>

        @include('layouts.footer')

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

@stack('scripts')

</body>

</html>