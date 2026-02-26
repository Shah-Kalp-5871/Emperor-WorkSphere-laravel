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
                <input type="password" id="password" name="password" placeholder="••••••••" required>
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
            document.getElementById('loginForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                const btn = document.getElementById('loginBtn');
                const errorDiv = document.getElementById('login-error');

                btn.disabled = true;
                btn.innerText = 'Signing in...';
                errorDiv.style.display = 'none';

                try {
                    const response = await axios.post('/api/admin/login', { email, password });
                    const token = response.data.access_token;

                    // Store token
                    sessionStorage.setItem('token', token);

                    // Set global axios header for immediate next call
                    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

                    // Call /api/me to verify role
                    const userResponse = await axios.get('/api/me');
                    const user = userResponse.data;

                    if (user.role === 'admin' || user.role === 'super_admin') {
                        // Initialize Echo if available
                        if (typeof window.initializeEcho === 'function') {
                            window.initializeEcho();
                        }
                        window.location.href = '/admin/dashboard';
                    } else {
                        sessionStorage.removeItem('token');
                        errorDiv.innerText = 'Unauthorized. Admin access only.';
                        errorDiv.style.display = 'block';
                        btn.disabled = false;
                        btn.innerText = 'Sign In';
                    }
                } catch (error) {
                    console.error('Login Error:', error);
                    errorDiv.innerText = error.response?.data?.error || 'Invalid credentials or server error.';
                    errorDiv.style.display = 'block';
                    btn.disabled = false;
                    btn.innerText = 'Sign In';
                }
            });
        </script>

        <div class="footer-text">
            Protected by WorkSphere Security.
        </div>
    </div>
</body>
</html>
