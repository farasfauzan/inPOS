<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - inPOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            min-height: 100vh;
            overflow: hidden;
        }

        .login-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        /* ========== LEFT PANEL ========== */
        .left-panel {
            background: linear-gradient(160deg, #0a0f1e 0%, #0d1526 40%, #0f1e3a 70%, #0a1628 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(44, 123, 229, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(44, 123, 229, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(44, 123, 229, 0.12) 0%, transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        .left-logo {
            position: relative;
            z-index: 1;
            margin-bottom: 2rem;
            text-align: center;
        }

        .left-logo-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #2c7be5, #1a5fc9);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 0 40px rgba(44, 123, 229, 0.4);
        }

        .left-logo-icon svg { width: 40px; height: 40px; }

        .left-logo h1 {
            font-size: 2.8rem;
            font-weight: 800;
            color: white;
            letter-spacing: -1px;
            line-height: 1;
        }

        .left-logo h1 span { color: #2c7be5; }

        .left-logo p {
            color: rgba(255,255,255,0.4);
            font-size: 0.85rem;
            margin-top: 0.5rem;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .illustration {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            margin-top: 1rem;
        }

        .illust-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            padding: 2rem;
            backdrop-filter: blur(10px);
        }

        .pos-screen {
            background: #0d1526;
            border-radius: 12px;
            padding: 1.25rem;
            border: 1px solid rgba(44, 123, 229, 0.2);
        }

        .pos-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .pos-dot { width: 10px; height: 10px; border-radius: 50%; }
        .pos-dot.r { background: #ff5f57; }
        .pos-dot.y { background: #febc2e; }
        .pos-dot.g { background: #28c840; }

        .pos-title {
            color: rgba(255,255,255,0.5);
            font-size: 0.7rem;
            margin-left: auto;
            font-weight: 500;
            letter-spacing: 1px;
        }

        .pos-items { display: flex; flex-direction: column; gap: 0.6rem; }

        .pos-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0.75rem;
            background: rgba(255,255,255,0.03);
            border-radius: 8px;
        }

        .pos-item-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
            overflow: hidden;
        }

        .pos-item-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pos-item-info { flex: 1; }
        .pos-item-name { color: rgba(255,255,255,0.7); font-size: 0.78rem; font-weight: 500; }
        .pos-item-qty { color: rgba(255,255,255,0.3); font-size: 0.7rem; }

        .pos-item-price {
            color: rgba(255,255,255,0.5);
            font-size: 0.8rem;
            font-weight: 600;
        }

        .quote-section {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.06);
        }

        .quote-text {
            font-size: 1rem;
            font-weight: 600;
            color: rgba(255,255,255,0.6);
            line-height: 1.5;
            font-style: italic;
        }

        .quote-text span { color: #2c7be5; }

        .quote-author {
            margin-top: 0.5rem;
            font-size: 0.7rem;
            color: rgba(255,255,255,0.2);
            letter-spacing: 1px;
        }

        /* ========== RIGHT PANEL ========== */
        .right-panel {
            background: #080c14;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            position: relative;
        }

        .right-panel::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(44, 123, 229, 0.08) 0%, transparent 65%);
            pointer-events: none;
        }

        .right-panel::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(26, 95, 201, 0.05) 0%, transparent 65%);
            pointer-events: none;
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 1;
        }

        .login-header { text-align: center; margin-bottom: 2.5rem; }

        .login-header h2 {
            color: white;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 0.5rem;
        }

        .login-header h2 span {
            background: linear-gradient(135deg, #2c7be5, #1a5fc9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .login-header p {
            color: rgba(255,255,255,0.3);
            font-size: 0.85rem;
        }

        .input-group { margin-bottom: 1.25rem; }

        .input-group label {
            display: block;
            color: rgba(255,255,255,0.5);
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 0.6rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            color: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            pointer-events: none;
        }

        .input-wrapper input {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            padding: 0.9rem 1rem 0.9rem 2.75rem;
            color: white;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            outline: none;
        }

        .input-wrapper input::placeholder { color: rgba(255,255,255,0.2); }

        .input-wrapper input:focus {
            border-color: rgba(44, 123, 229, 0.5);
            background: rgba(44, 123, 229, 0.05);
            box-shadow: 0 0 0 3px rgba(44, 123, 229, 0.1);
        }

        .toggle-pass {
            position: absolute;
            right: 1rem;
            background: none;
            border: none;
            color: rgba(255,255,255,0.2);
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
        }

        .toggle-pass:hover { color: rgba(255,255,255,0.5); }

        .btn-login {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #2c7be5, #1a5fc9);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(44, 123, 229, 0.35);
        }

        .btn-login:active { transform: translateY(0); }

        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-login .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        #loginError {
            color: #ff6b6b;
            background: rgba(255, 107, 107, 0.1);
            border: 1px solid rgba(255, 107, 107, 0.2);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.82rem;
            margin-bottom: 1rem;
            display: none;
            text-align: center;
        }

        @media (max-width: 768px) {
            .login-wrapper { grid-template-columns: 1fr; }
            .left-panel { display: none; }
            .right-panel { padding: 2rem 1.5rem; }
            .login-header h2 { font-size: 1.75rem; }
        }
    </style>
</head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
<body>
    <div class="login-wrapper">
        <!-- LEFT PANEL -->
        <div class="left-panel">
            <div class="left-logo">
                <div class="left-logo-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <rect x="2" y="7" width="20" height="14" rx="2"/>
                        <path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>
                        <line x1="6" y1="12" x2="6" y2="12"/>
                        <line x1="10" y1="12" x2="10" y2="12"/>
                    </svg>
                </div>
                <h1>in<span>POS</span></h1>
                <p>Point of Sale & Inventory</p>
            </div>

            <div class="illustration">
                <div class="illust-card">
                    <div class="pos-screen">
                        <div class="pos-header">
                            <div class="pos-dot r"></div>
                            <div class="pos-dot y"></div>
                            <div class="pos-dot g"></div>
                            <span class="pos-title">inPOS Kasir</span>
                        </div>
                        <div class="pos-items">
                            <div class="pos-item">
                                <div class="pos-item-icon"><img src="{{ asset('assets/images/nasigoreng.jpg') }}" alt="Nasi Goreng"></div>
                                <div class="pos-item-info">
                                    <div class="pos-item-name">Nasi Goreng</div>
                                    <div class="pos-item-qty">1x Rp 25.000</div>
                                </div>
                                <div class="pos-item-price">Rp 25.000</div>
                            </div>
                            <div class="pos-item">
                                <div class="pos-item-icon"><img src="{{ asset('assets/images/es-teh-manis.jpg') }}" alt="Es Teh Manis"></div>
                                <div class="pos-item-info">
                                    <div class="pos-item-name">Es Teh Manis</div>
                                    <div class="pos-item-qty">2x Rp 5.000</div>
                                </div>
                                <div class="pos-item-price">Rp 10.000</div>
                            </div>
                            <div class="pos-item">
                                <div class="pos-item-icon"><img src="{{ asset('assets/images/pisang-goreng.jpg') }}" alt="Pisang Goreng"></div>
                                <div class="pos-item-info">
                                    <div class="pos-item-name">Pisang Goreng</div>
                                    <div class="pos-item-qty">1x Rp 10.000</div>
                                </div>
                                <div class="pos-item-price">Rp 10.000</div>
                            </div>
                        </div>
                    </div>

                    <div class="quote-section">
                        <p class="quote-text">"Being Neat Is <span>Your Choice</span>"</p>
                        <p class="quote-author">— inPOS Philosophy</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="right-panel">
            <div class="login-form-wrapper">
                <div class="login-header">
                    <h2>Log In <span>First!!</span></h2>
                    <p>Masuk untuk mengakses sistem inPOS</p>
                </div>

                <form id="loginForm" onsubmit="return false;">
                    <div id="loginError">
                        @if(session('error'))
                            {{ session('error') }}
                        @endif
                    </div>

                    <div class="input-group">
                        <label>Username</label>
                        <div class="input-wrapper">
                            <div class="input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </div>
                            <input type="text" id="username" placeholder="Masukkan username" autocomplete="off">
                        </div>
                    </div>

                    <div class="input-group">
                        <label>Password</label>
                        <div class="input-wrapper">
                            <div class="input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                                </svg>
                            </div>
                            <input type="password" id="password" placeholder="Masukkan password">
                            <button type="button" class="toggle-pass" onclick="togglePassword()">
                                <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-login" id="loginBtn" onclick="handleLogin()">
                        <span id="loginText">Masuk</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
            }
        }

        async function handleLogin() {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            const errorEl = document.getElementById('loginError');
            const btn = document.getElementById('loginBtn');
            const btnText = document.getElementById('loginText');

            if (!username || !password) {
                errorEl.textContent = 'Username dan password wajib diisi';
                errorEl.style.display = 'block';
                return;
            }

            errorEl.style.display = 'none';
            btn.disabled = true;
            btnText.innerHTML = '<div class="spinner"></div>';

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

            try {
                const res = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ username, password })
                });

                const data = await res.json();

                if (res.ok) {
                    btnText.textContent = 'Berhasil ✓';
                    btn.style.background = 'linear-gradient(135deg, #28a745, #1e7e34)';
                    setTimeout(() => window.location.href = '/dashboard', 600);
                } else {
                    errorEl.textContent = data.error || 'Login gagal. Periksa username dan password.';
                    errorEl.style.display = 'block';
                    btn.disabled = false;
                    btnText.textContent = 'Masuk';
                }
            } catch (e) {
                errorEl.textContent = 'Terjadi kesalahan koneksi. Pastikan server berjalan.';
                errorEl.style.display = 'block';
                btn.disabled = false;
                btnText.textContent = 'Masuk';
            }
        }

        // Check session on load
        fetch('/api/auth/session').then(r => r.json()).then(data => {
            if (data.loggedIn) window.location.href = '/dashboard';
        });
    </script>
</body>
</html>
