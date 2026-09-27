{{-- Praktikum 3 --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Praktikum Laravel')</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 30px; background-color: #f8f9fa; }
        .container { background: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .card { border: 1px solid #e9ecef; border-left: 4px solid #457b9d; padding: 15px; margin-bottom: 15px; background: #fafafa; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #dee2e6; padding: 10px; text-align: left; }
        th { background-color: #e63946; color: white; }
        .text-danger { color: #e63946; font-weight: bold; }
        .badge { background: #2a9d8f; color: white; padding: 3px 8px; border-radius: 4px; font-size: 12px; }
    </style>
</head>
<body>

    <!-- Include Sub-View Partial Navbar -->
    @include('partials.navbar')

    <!-- Container Utama tempat Halaman Anak Ditempatkan -->
    <div class="container">
        @yield('content')
    </div>

</body>
</html>
