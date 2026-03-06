<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — WorkOS</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0c0d0e;
            --surface: #141517;
            --border: #242629;
            --accent: #2d6a4f;
            --accent-hover: #40916c;
            --text-1: #ffffff;
            --text-2: #848d97;
            --radius: 12px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background-color: var(--bg);
            color: var(--text-1);
            font-family: 'DM Sans', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px;
        }

        .login-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            width: 100%;
            max-width: 420px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo {
            font-family: 'Syne', sans-serif;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: var(--accent);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 20px;
        }

        h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        p {
            color: var(--text-2);
            font-size: 14px;
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-2);
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            background: #1c1e21;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px 16px;
            color: #fff;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }

        input:focus {
            border-color: var(--accent);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-2);
            cursor: pointer;
        }

        .forgot {
            font-size: 13px;
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }

        .login-btn {
            width: 100%;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 14px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }

        .login-btn:hover {
            background: var(--accent-hover);
        }

        .login-btn:active {
            transform: scale(0.98);
        }

        .footer-text {
            text-align: center;
            margin-top: 32px;
            font-size: 13px;
            color: var(--text-2);
        }

        .footer-text a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }

        /* Password eye toggle */
        .password-wrap {
            position: relative;
        }
        .password-wrap input {
            padding-right: 42px;
        }
        .eye-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-2);
            padding: 0;
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }
        .eye-toggle:hover { color: var(--text-1); }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo">
            <div class="logo-icon">W</div>
            WorkOS
        </div>
        <h1>Admin Portal</h1>
        <p>Please enter your credentials to continue.</p>

        <form id="loginForm">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="admin@worksphere.com" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrap">
                    <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                    <button type="button" class="eye-toggle" id="eyeToggle" aria-label="Toggle password visibility">
                        <!-- Eye icon -->
                        <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <!-- Eye-off icon (hidden by default) -->
                        <svg id="eyeOffIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                            <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="remember-forgot">
                <label class="remember">
                    <input type="checkbox" style="width: auto;"> Remember me
                </label>
                <a href="#" class="forgot">Forgot Password?</a>
            </div>

            <button type="submit" class="login-btn" id="loginBtn">Sign In</button>
            <div id="login-error" style="color: #ff4d4d; font-size: 13px; margin-top: 12px; text-align: center; display: none;"></div>
        </form>

      <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>

// Global URL base
window.APP_URL = '{{ rtrim(url('/'), '/') }}';

// Base API URL
axios.defaults.baseURL = window.APP_URL;
axios.defaults.headers.common['Accept'] = 'application/json';

// UI helpers
function showError(msg) {
    const errorDiv = document.getElementById('login-error');
    errorDiv.innerText = msg;
    errorDiv.style.display = 'block';
}

function resetBtn() {
    const btn = document.getElementById('loginBtn');
    btn.disabled = false;
    btn.innerText = 'Sign In';
}

// Login submit
document.getElementById('loginForm').addEventListener('submit', async (e) => {

    e.preventDefault();

    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const btn = document.getElementById('loginBtn');

    btn.disabled = true;
    btn.innerText = 'Signing in...';
    document.getElementById('login-error').style.display = 'none';

    let token = null;

    try {

        // Step 1 — Login
        const response = await axios.post(window.APP_URL + '/api/admin/login', {
            email: email,
            password: password
        });

        token = response.data.access_token;

        if (!token) {
            throw new Error('Token not received');
        }

        // Save token
        sessionStorage.setItem('token', token);

        // Set axios auth header
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        // Step 2 — Verify user role
        const userResponse = await axios.get(window.APP_URL + '/api/me');

        const user = userResponse.data;

        if (user.role === 'admin' || user.role === 'super_admin') {

            // optional realtime setup
            if (typeof window.initializeEcho === 'function') {
                window.initializeEcho();
            }

            window.location.href = window.APP_URL + '/admin/dashboard';

        } else {

            sessionStorage.removeItem('token');
            showError('Access denied. Admins only.');
            resetBtn();
        }

    } catch (err) {

        console.error('Login Error:', err);

        const status = err.response?.status;

        if (status === 401) {
            showError('Incorrect email or password.');
        }
        else if (status === 422) {
            const errors = err.response?.data?.errors;
            const firstError = errors ? Object.values(errors)[0][0] : null;
            showError(firstError || 'Invalid input.');
        }
        else if (status === 429) {
            showError('Too many login attempts. Please wait.');
        }
        else if (status >= 500) {
            showError('Server error. Try again later.');
        }
        else if (!err.response) {
            showError('Network error. Check internet connection.');
        }
        else {
            showError('Login failed. Please try again.');
        }

        resetBtn();
    }

});


// Password toggle
document.getElementById('eyeToggle').addEventListener('click', function () {

    const input = document.getElementById('password');
    const eye = document.getElementById('eyeIcon');
    const eyeOff = document.getElementById('eyeOffIcon');

    if (input.type === 'password') {
        input.type = 'text';
        eye.style.display = 'none';
        eyeOff.style.display = '';
    } else {
        input.type = 'password';
        eye.style.display = '';
        eyeOff.style.display = 'none';
    }

});

</script>
        <div class="footer-text">
            Protected by WorkSphere Security.
        </div>
    </div>
</body>
</html>
