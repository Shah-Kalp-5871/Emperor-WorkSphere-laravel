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
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Global URL base
        window.APP_URL = '{{ rtrim(url('/'), '/') }}';
        
        (async function() {
            const token = sessionStorage.getItem('token');
            const isLoginPage = window.location.pathname.includes('/employee/login');

            if (!token) {
                if (!isLoginPage) {
                    window.location.href = window.APP_URL + '/employee/login';
                }
                return;
            }

            // Set global axios header
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

            try {
                const response = await axios.get(window.APP_URL + '/api/me');
                const user = response.data;

                if (user.role !== 'employee') {
                    throw new Error('Unauthorized');
                }

                // If on login page but already logged in, redirect to dashboard
                if (isLoginPage) {
                    window.location.href = window.APP_URL + '/employee/dashboard';
                }

                // Initialize Echo and Update User Info
                const updateUserInfo = () => {
                    if (typeof window.initializeEcho === 'function') {
                        window.initializeEcho();
                    }
                    const avatarEls = document.querySelectorAll('.sidebar-user .avatar, .topbar-avatar');
                    avatarEls.forEach(el => el.textContent = user.initials || 'U');
                    
                    const nameEl = document.querySelector('.sidebar-user .name');
                    if(nameEl) nameEl.textContent = user.name || 'User';
                    
                    const roleEl = document.querySelector('.sidebar-user .role');
                    if(roleEl) {
                        roleEl.textContent = (user.employee && user.employee.designation && user.employee.designation.name) 
                            ? user.employee.designation.name 
                            : 'Employee';
                    }
                };

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', updateUserInfo);
                } else {
                    updateUserInfo();
                }
            } catch (error) {
                console.error('Session validation failed:', error);
                sessionStorage.removeItem('token');
                if (!isLoginPage) {
                    window.location.href = window.APP_URL + '/employee/login';
                }
            }
        })();

        async function employeeLogout() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out of your session!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, logout!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        await axios.post(window.APP_URL + '/api/logout');
                    } catch (err) {
                        console.error('Logout error:', err);
                    } finally {
                        sessionStorage.removeItem('token');
                        window.location.href = window.APP_URL + '/employee/login';
                    }
                }
            });
        }
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
