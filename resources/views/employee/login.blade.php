<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Login — WorkSphere</title>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f8f9fa;
            --surface: #ffffff;
            --border: #e9ecef;
            --accent: #2d6a4f;
            --accent-hover: #1b4332;
            --text-1: #1a1d21;
            --text-2: #6c757d;
            --radius: 16px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background-color: var(--bg);
            color: var(--text-1);
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo {
            font-family: 'Instrument Serif', serif;
            font-size: 32px;
            font-style: italic;
            margin-bottom: 40px;
            text-align: center;
        }

        .login-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 40px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.03);
        }

        h1 {
            font-family: 'Instrument Serif', serif;
            font-size: 28px;
            font-weight: 400;
            margin-bottom: 8px;
            text-align: center;
        }

        p {
            color: var(--text-2);
            font-size: 14px;
            margin-bottom: 32px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 24px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 14px;
            outline: none;
            transition: all 0.2s;
        }

        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(45, 106, 79, 0.1);
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            font-size: 13px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-2);
            cursor: pointer;
        }

        .forgot {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }

        .login-btn {
            width: 100%;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 16px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }

        .login-btn:hover {
            background: var(--accent-hover);
        }

        .login-btn:active {
            transform: scale(0.99);
        }

        .help-text {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: var(--text-2);
        }

        .help-text a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">WorkSphere</div>
        <div class="login-card">
            <h1>Welcome back</h1>
            <p>Enter your details to access your workspace.</p>

            <form id="loginForm">
                <div class="form-group">
                    <label for="email">Work Email</label>
                    <input type="email" id="email" name="email" placeholder="name@worksphere.com" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>

                <div class="options">
                    <label class="remember">
                        <input type="checkbox" style="width: auto;"> Remember for 30 days
                    </label>
                    <a href="#" class="forgot">Reset password</a>
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
                        const response = await axios.post('/api/employee/login', { email, password });
                        const token = response.data.access_token;

                        // Store token
                        sessionStorage.setItem('token', token);

                        // Set global axios header for immediate next call
                        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

                        // Call /api/me to verify role
                        const userResponse = await axios.get('/api/me');
                        const user = userResponse.data;

                        if (user.role === 'employee') {
                            // Initialize Echo if available
                            if (typeof window.initializeEcho === 'function') {
                                window.initializeEcho();
                            }
                            window.location.href = '/employee/dashboard';
                        } else {
                            sessionStorage.removeItem('token');
                            errorDiv.innerText = 'Unauthorized. Employee access only.';
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
        </div>
        <div class="help-text">
            Need help? <a href="#">Contact Support</a>
        </div>
    </div>
</body>
</html>
