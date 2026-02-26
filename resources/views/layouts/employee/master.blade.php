<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'WorkSphere — Employee Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/employee/employee-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/employee/tabulator-custom.css') }}">
    <script src="{{ asset('js/employee/tabulator-init.js') }}" defer></script>
    <link rel="stylesheet" href="https://unpkg.com/tabulator-tables@6.3.0/dist/css/tabulator.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        (async function() {
            const token = sessionStorage.getItem('token');
            const isLoginPage = window.location.pathname.includes('/employee/login');

            if (!token) {
                if (!isLoginPage) {
                    window.location.href = '/employee/login';
                }
                return;
            }

            // Set global axios header
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

            try {
                const response = await axios.get('/api/me');
                const user = response.data;

                if (user.role !== 'employee') {
                    throw new Error('Unauthorized');
                }

                // If on login page but already logged in, redirect to dashboard
                if (isLoginPage) {
                    window.location.href = '/employee/dashboard';
                }

                // Initialize Echo
                window.onload = () => {
                    if (typeof window.initializeEcho === 'function') {
                        window.initializeEcho();
                    }
                };
            } catch (error) {
                console.error('Session validation failed:', error);
                sessionStorage.removeItem('token');
                if (!isLoginPage) {
                    window.location.href = '/employee/login';
                }
            }
        })();
    </script>
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
