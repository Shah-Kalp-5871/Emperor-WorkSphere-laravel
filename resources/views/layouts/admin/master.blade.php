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
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        (async function() {
            const token = sessionStorage.getItem('token');
            const isLoginPage = window.location.pathname.includes('/admin/login');

            if (!token) {
                if (!isLoginPage) {
                    window.location.href = '/admin/login';
                }
                return;
            }

            // Set global axios header
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

            try {
                const response = await axios.get('/api/me');
                const user = response.data;

                if (user.role !== 'admin' && user.role !== 'super_admin') {
                    throw new Error('Unauthorized');
                }

                // If on login page but already logged in, redirect to dashboard
                if (isLoginPage) {
                    window.location.href = '/admin/dashboard';
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
                    window.location.href = '/admin/login';
                }
            }
        })();

        async function adminLogout() {
            if (!confirm('Are you sure you want to logout?')) return;
            try {
                // Optional: Call API logout
                await axios.post('/api/logout');
            } catch (err) {
                console.error('Logout error:', err);
            } finally {
                sessionStorage.removeItem('token');
                window.location.href = '/admin/login';
            }
        }
    </script>
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
