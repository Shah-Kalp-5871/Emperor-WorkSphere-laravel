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

            <form>
                <div class="form-group">
                    <label for="email">Work Email</label>
                    <input type="email" id="email" placeholder="name@worksphere.com" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" placeholder="••••••••" required>
                </div>

                <div class="options">
                    <label class="remember">
                        <input type="checkbox" style="width: auto;"> Remember for 30 days
                    </label>
                    <a href="#" class="forgot">Reset password</a>
                </div>

                <button type="button" class="login-btn" onclick="window.location.href='/employee/dashboard'">Sign In</button>
            </form>
        </div>
        <div class="help-text">
            Need help? <a href="#">Contact Support</a>
        </div>
    </div>
</body>
</html>
