<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WorkOS — Admin Panel')</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin/admin-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/tabulator-custom.css') }}">
    <script src="{{ asset('js/admin/tabulator-init.js') }}" defer></script>
    <link rel="stylesheet" href="https://unpkg.com/tabulator-tables@6.3.0/dist/css/tabulator.min.css">
    @stack('styles')
</head>
<body>
    @include('partials.admin.sidebar')

    <!-- MAIN -->
    <main class="main">
        @include('partials.admin.topbar')

        <div class="content">
            @yield('content')
        </div>
    </main>

    @include('partials.admin.footer')
    @stack('scripts')
</body>
</html>
