<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'WorkSphere — Employee Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/employee-style.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/tabulator-tables@6.3.0/dist/css/tabulator.min.css">
    <link rel="stylesheet" href="{{ asset('css/tabulator-custom.css') }}">
    @stack('styles')
</head>
<body>
    <div class="layout">
        @include('partials.employee.sidebar')

        <!-- MAIN -->
        <main class="main">
            @include('partials.employee.topbar')

            <div class="content">
                @yield('content')
            </div>
        </main>
    </div>

    @include('partials.employee.footer')
    @stack('scripts')
</body>
</html>
