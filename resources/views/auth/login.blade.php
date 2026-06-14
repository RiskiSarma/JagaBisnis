<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>JagaBisnis — Multi-Bisnis POS</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/dx21r1pko/image/upload/q_auto/f_auto/v1776681943/logo_jagabisnis_usq1pu.png">
    {{-- Atau bisa juga pakai format ico --}}
    {{-- <link rel="icon" type="image/x-icon" href="https://res.cloudinary.com/dx21r1pko/image/upload/q_auto/f_auto/v1776681943/logo_jagabisnis_usq1pu.png"> --}}
    
    {{-- Untuk Apple devices (iPhone, iPad) --}}
    <link rel="apple-touch-icon" href="https://res.cloudinary.com/dx21r1pko/image/upload/q_auto/f_auto/v1776681943/logo_jagabisnis_usq1pu.png">
    <style>
        :root {
            --brand: #1A56DB;
            --danger: #EF4444;
            --accent2: #10B981;
            --text: #0F172A;
            --text2: #475569;
            --text3: #94A3B8;
            --border: #E2E8F0;
            --surface: #F8FAFC;
            --radius-sm: 8px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(140deg, #080f1f 0%, #0c2161 55%, #1A56DB 100%);
            color: var(--text);
        }

        /* ── LOGIN SPLASH ── */
        #splash {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            min-height: 100vh;
        }

        .splash-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 32px;
        }

        .splash-logo img {
            height: 64px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 4px 20px rgba(26, 86, 219, 0.4));
        }

        .login-card {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-radius: 24px;
            padding: 40px 36px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 32px 96px rgba(0, 0, 0, 0.5);
        }

        .login-header {
            margin-bottom: 32px;
        }

        .login-card h2 {
            color: #fff;
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .login-card p {
            color: rgba(255,255,255,.5);
            font-size: 14px;
            font-weight: 400;
        }

        .fg {
            margin-bottom: 20px;
        }

        .fg label {
            display: block;
            color: rgba(255, 255, 255, 0.7);
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .fg-inner {
            position: relative;
            display: flex;
            align-items: center;
        }

        .fg-inner svg {
            position: absolute;
            left: 14px;
            pointer-events: none;
            stroke: rgba(255, 255, 255, 0.4);
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            transition: stroke 0.2s ease;
        }

        .fg input {
            width: 100%;
            padding: 14px 14px 14px 44px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            color: #fff;
            font-family: inherit;
            font-size: 14px;
            outline: none;
            transition: all 0.2s ease;
        }

        .fg input::placeholder {
            color: rgba(255, 255, 255, 0.35);
        }

        .fg input:focus {
            border-color: rgba(26, 86, 219, 0.6);
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 4px rgba(26, 86, 219, 0.15);
        }

        .fg input:focus + svg,
        .fg-inner:focus-within svg {
            stroke: rgba(26, 86, 219, 0.8);
        }

        .btn-login {
            width: 100%;
            padding: 16px 24px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #1A56DB 0%, #3B82F6 100%);
            color: #fff;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 24px rgba(26, 86, 219, 0.35);
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(26, 86, 219, 0.45);
        }

        .btn-login:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn-login:disabled {
            background: rgba(148, 163, 184, 0.5);
            box-shadow: none;
            cursor: not-allowed;
        }

        .btn-login svg {
            width: 20px;
            height: 20px;
            stroke-width: 2.5;
        }

        .login-error {
            display: none;
            align-items: center;
            gap: 10px;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 20px;
            color: #FCA5A5;
            font-size: 13px;
            font-weight: 500;
            animation: shake 0.4s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-8px); }
            75% { transform: translateX(8px); }
        }

        .login-error.show {
            display: flex;
        }

        .login-error svg {
            stroke: #FCA5A5;
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .login-support {
            margin-top: 24px;
            text-align: center;
        }

        .login-support a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.5);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            padding: 8px 16px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.03);
        }

        .login-support a:hover {
            color: #fff;
            border-color: rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-1px);
        }

        .login-support a svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
        }

        .pass-toggle {
            position: absolute;
            right: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.4);
            transition: all 0.2s;
            padding: 4px;
            border-radius: 6px;
        }

        .pass-toggle:hover {
            color: rgba(255, 255, 255, 0.7);
            background: rgba(255, 255, 255, 0.1);
        }

        .pass-toggle svg {
            width: 18px;
            height: 18px;
            stroke-width: 2;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 32px 24px;
                max-width: 100%;
            }
            
            .splash-logo img {
                height: 56px;
            }
            
            .login-card h2 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div id="splash">
        <div class="splash-logo">
            <img src="https://res.cloudinary.com/dx21r1pko/image/upload/q_auto/f_auto/v1776681943/logo_jagabisnis_usq1pu.png" alt="JagaBisnis">
        </div>
        
        <div class="login-card">
            <div class="login-header">
                <h2>Selamat Datang</h2>
                <p>Masuk ke sistem POS multi-bisnis Anda</p>
            </div>

            <div class="login-error" id="loginError">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span id="errorMsg">Email atau password salah. Silakan coba lagi.</span>
            </div>

            <form id="loginForm" onsubmit="handleLogin(event)">
                @csrf
                <div class="fg">
                    <label for="email">EMAIL</label>
                    <div class="fg-inner">
                        <input type="email" id="email" name="email" placeholder="email@bisnis.com" autocomplete="username" required autofocus>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    </div>
                </div>
                
                <div class="fg">
                    <label for="password">PASSWORD</label>
                    <div class="fg-inner" style="position: relative;">
                        <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <span class="pass-toggle" onclick="togglePassVis()" title="Lihat password">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </span>
                    </div>
                </div>
                
                <button type="submit" class="btn-login" id="btnLogin">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    <span>Masuk ke Platform</span>
                </button>
            </form>

            <div class="login-support">
                <a href="https://wa.me/6282297207284" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    Pusat Bantuan
                </a>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            document.getElementById('email').focus();
        });

        function togglePassVis() {
            const inp = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            const isPassword = inp.type === 'password';
            inp.type = isPassword ? 'text' : 'password';
            icon.innerHTML = isPassword
                ? '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>'
                : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
            lucide.createIcons();
        }

        function handleLogin(event) {
            event.preventDefault();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const errorEl = document.getElementById('loginError');
            const btnLogin = document.getElementById('btnLogin');

            if (!email || !password) {
                document.getElementById('errorMsg').textContent = 'Email dan password wajib diisi.';
                errorEl.classList.add('show');
                return;
            }

            btnLogin.disabled = true;
            btnLogin.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg><span>Memproses...</span>';
            lucide.createIcons();

            fetch('{{ route("login") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email, password })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    errorEl.classList.remove('show');
                    document.getElementById('splash').style.transition = 'opacity 0.4s ease';
                    document.getElementById('splash').style.opacity = '0';
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 400);
                } else {
                    document.getElementById('errorMsg').textContent = data.message || 'Email atau password salah. Silakan coba lagi.';
                    errorEl.classList.add('show');
                    btnLogin.disabled = false;
                    btnLogin.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg><span>Masuk ke Platform</span>';
                    lucide.createIcons();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('errorMsg').textContent = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
                errorEl.classList.add('show');
                btnLogin.disabled = false;
                btnLogin.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg><span>Masuk ke Platform</span>';
                lucide.createIcons();
            });
        }
    </script>
</body>
</html>