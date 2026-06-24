<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Bisnis — JagaBisnis</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/dx21r1pko/image/upload/q_auto/f_auto/v1776681943/logo_jagabisnis_usq1pu.png">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            min-height: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(140deg, #080f1f 0%, #0c2161 55%, #1A56DB 100%);
            color: #fff;
        }

        /* ── PROGRESS BAR ── */
        .progress-bar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(8,15,31,0.75);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .progress-brand {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .progress-brand img { height: 28px; }

        .progress-brand span {
            font-size: 14px;
            font-weight: 800;
            color: #fff;
        }

        .progress-steps {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .step-dot {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            border: 1.5px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.35);
            transition: all 0.3s;
        }

        .step-dot.active {
            background: #1A56DB;
            border-color: #1A56DB;
            color: #fff;
            box-shadow: 0 0 14px rgba(26,86,219,0.5);
        }

        .step-dot.done {
            background: #10B981;
            border-color: #10B981;
            color: #fff;
        }

        .step-line {
            width: 28px;
            height: 2px;
            background: rgba(255,255,255,0.1);
            border-radius: 2px;
            transition: background 0.3s;
        }

        .step-line.done { background: #10B981; }

        .step-label {
            font-size: 11px;
            color: rgba(255,255,255,0.35);
            white-space: nowrap;
        }

        .progress-login {
            font-size: 13px;
            color: rgba(255,255,255,0.4);
            text-decoration: none;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .progress-login:hover { color: rgba(255,255,255,0.8); }

        /* ── WRAP ── */
        .register-wrap {
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 96px 20px 60px;
        }

        .register-card {
            background: rgba(255,255,255,0.055);
            border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(24px);
            border-radius: 24px;
            padding: 38px 34px;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 32px 96px rgba(0,0,0,0.5);
        }

        /* ── PANELS ── */
        .step-panel { display: none; }
        .step-panel.active { display: block; }

        .step-eyebrow {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #60a5fa;
            margin-bottom: 6px;
        }

        .step-title {
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }

        .step-desc {
            font-size: 13px;
            color: rgba(255,255,255,0.4);
            margin-bottom: 24px;
        }

        /* ── FORM ── */
        .fg { margin-bottom: 14px; }

        .fg label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: rgba(255,255,255,0.55);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 7px;
        }

        .fg-inner { position: relative; display: flex; align-items: center; }

        .fg-inner .bi {
            position: absolute;
            left: 14px;
            font-size: 15px;
            color: rgba(255,255,255,0.3);
            pointer-events: none;
            z-index: 1;
        }

        .fg input,
        .fg select {
            width: 100%;
            padding: 13px 14px 13px 42px;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 11px;
            color: #fff;
            font-family: inherit;
            font-size: 14px;
            outline: none;
            transition: all 0.2s;
            appearance: none;
            -webkit-appearance: none;
        }

        .fg input::placeholder { color: rgba(255,255,255,0.28); }

        .fg input:focus,
        .fg select:focus {
            border-color: rgba(26,86,219,0.65);
            background: rgba(255,255,255,0.1);
            box-shadow: 0 0 0 3px rgba(26,86,219,0.18);
        }

        .fg select option { background: #0c2161; color: #fff; }

        .pass-toggle {
            position: absolute;
            right: 12px;
            cursor: pointer;
            color: rgba(255,255,255,0.35);
            font-size: 16px;
            padding: 4px 6px;
            border-radius: 6px;
            transition: all 0.2s;
            z-index: 1;
        }

        .pass-toggle:hover {
            color: rgba(255,255,255,0.7);
            background: rgba(255,255,255,0.08);
        }

        /* Password strength */
        .pass-strength {
            display: flex;
            gap: 4px;
            margin-top: 7px;
        }

        .pass-bar {
            height: 3px;
            flex: 1;
            border-radius: 3px;
            background: rgba(255,255,255,0.08);
            transition: background 0.3s;
        }

        .pass-hint {
            font-size: 11px;
            color: rgba(255,255,255,0.3);
            margin-top: 5px;
        }

        /* ── PAKET ── */
        .paket-grid {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 16px;
        }

        .paket-option { position: relative; }

        .paket-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .paket-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 13px 16px;
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            background: rgba(255,255,255,0.04);
            cursor: pointer;
            transition: all 0.2s;
        }

        .paket-option input:checked + .paket-label {
            border-color: #1A56DB;
            background: rgba(26,86,219,0.14);
            box-shadow: 0 0 0 1px rgba(26,86,219,0.25);
        }

        .paket-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .paket-icon {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
        }

        .paket-name {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
        }

        .paket-sub {
            font-size: 11px;
            color: rgba(255,255,255,0.38);
            margin-top: 1px;
        }

        .paket-price {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            text-align: right;
        }

        .paket-price small {
            display: block;
            font-size: 10px;
            color: rgba(255,255,255,0.35);
            font-weight: 400;
        }

        .paket-popular {
            position: absolute;
            top: -9px;
            right: 12px;
            background: #F59E0B;
            color: #0B1426;
            font-size: 9px;
            font-weight: 800;
            padding: 2px 9px;
            border-radius: 10px;
            letter-spacing: 0.3px;
        }

        /* ── JENIS BISNIS GRID ── */
        .biztype-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .biztype-option {
            position: relative;
        }

        .biztype-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .biztype-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 14px 8px;
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            background: rgba(255,255,255,0.04);
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
        }

        .biztype-card i {
            font-size: 20px;
            color: rgba(255,255,255,0.4);
            transition: color 0.2s;
        }

        .biztype-card span {
            font-size: 11px;
            font-weight: 600;
            color: rgba(255,255,255,0.6);
            line-height: 1.3;
        }

        .biztype-option input:checked + .biztype-card {
            border-color: #1A56DB;
            background: rgba(26,86,219,0.14);
            box-shadow: 0 0 0 1px rgba(26,86,219,0.25);
        }

        .biztype-option input:checked + .biztype-card i {
            color: #60a5fa;
        }

        .biztype-option input:checked + .biztype-card span {
            color: #fff;
        }

        @media (max-width: 480px) {
            .biztype-grid { grid-template-columns: repeat(2, 1fr); }
        }
        /* ── BUTTONS ── */
        .btn-next {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #1A56DB, #3B82F6);
            color: #fff;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 6px 20px rgba(26,86,219,0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 6px;
        }

        .btn-next:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 8px 28px rgba(26,86,219,0.45);
        }

        .btn-next:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-back {
            width: 100%;
            padding: 11px;
            margin-top: 10px;
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 12px;
            background: transparent;
            color: rgba(255,255,255,0.5);
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-back:hover {
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.8);
        }

        /* ── ERROR ── */
        .reg-error {
            display: none;
            align-items: center;
            gap: 8px;
            background: rgba(239,68,68,0.12);
            border: 1px solid rgba(239,68,68,0.25);
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 16px;
            color: #FCA5A5;
            font-size: 12px;
            font-weight: 500;
            animation: shake 0.35s ease;
        }

        .reg-error.show { display: flex; }

        @keyframes shake {
            0%,100% { transform: translateX(0); }
            25%      { transform: translateX(-6px); }
            75%      { transform: translateX(6px); }
        }

        /* ── SUCCESS ── */
        .success-wrap { text-align: center; padding: 8px 0; }

        .success-icon {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: rgba(16,185,129,0.12);
            border: 2px solid rgba(16,185,129,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            font-size: 30px;
        }

        .summary-box {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.09);
            border-radius: 12px;
            padding: 16px;
            margin: 20px 0;
            text-align: left;
        }

        .summary-label {
            font-size: 10px;
            font-weight: 700;
            color: rgba(255,255,255,0.35);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 12px;
        }

        .summary-row {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: rgba(255,255,255,0.65);
            padding: 5px 0;
        }

        .summary-row .bi { color: #60a5fa; font-size: 14px; }

        .divider {
            height: 1px;
            background: rgba(255,255,255,0.07);
            margin: 18px 0;
        }

        .trial-box {
            background: rgba(16,185,129,0.07);
            border: 1px solid rgba(16,185,129,0.18);
            border-radius: 10px;
            padding: 11px 14px;
            margin-bottom: 14px;
        }

        .trial-box-title {
            font-size: 12px;
            color: #6ee7b7;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .trial-box-sub {
            font-size: 11px;
            color: rgba(110,231,183,0.55);
        }

        @media (max-width: 480px) {
            .register-card { padding: 28px 18px; }
            .progress-steps { display: none; }
            .progress-login span { display: none; }
        }
    </style>
</head>
<body>

<!-- Progress Bar -->
<div class="progress-bar">
    <a href="{{ url('/') }}" class="progress-brand">
        <img src="https://res.cloudinary.com/dx21r1pko/image/upload/q_auto/f_auto/v1776681943/logo_jagabisnis_usq1pu.png" alt="JagaBisnis">
        <span>JagaBisnis</span>
    </a>

    <div class="progress-steps" id="progressSteps">
        <div class="step-dot active" id="dot1">1</div>
        <div class="step-line" id="line1"></div>
        <div class="step-dot" id="dot2">2</div>
        <div class="step-line" id="line2"></div>
        <div class="step-dot" id="dot3">3</div>
    </div>

    <a href="{{ route('login') }}" class="progress-login">
        <i class="bi bi-box-arrow-in-right"></i>
        <span>Sudah punya akun?</span>
    </a>
</div>

<div class="register-wrap">
    <div class="register-card">

        <div class="reg-error" id="regError">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span id="regErrorMsg">Terjadi kesalahan.</span>
        </div>

        <!-- ── STEP 1: Akun ── -->
        <div class="step-panel active" id="step1">
            <div class="step-eyebrow">Langkah 1 dari 3</div>
            <div class="step-title">Buat Akun Anda</div>
            <div class="step-desc">Informasi login ke sistem JagaBisnis</div>

            <div class="fg">
                <label>Nama Lengkap</label>
                <div class="fg-inner">
                    <i class="bi bi-person"></i>
                    <input type="text" id="reg_name" placeholder="Nama Anda" autocomplete="name">
                </div>
            </div>

            <div class="fg">
                <label>Email</label>
                <div class="fg-inner">
                    <i class="bi bi-envelope"></i>
                    <input type="email" id="reg_email" placeholder="email@bisnis.com" autocomplete="email">
                </div>
            </div>

            <div class="fg">
                <label>Password</label>
                <div class="fg-inner">
                    <i class="bi bi-lock"></i>
                    <input type="password" id="reg_password" placeholder="Min. 8 karakter" oninput="checkStrength(this.value)" autocomplete="new-password">
                    <span class="pass-toggle" onclick="togglePass('reg_password', this)" title="Lihat password">
                        <i class="bi bi-eye" id="eye_pass"></i>
                    </span>
                </div>
                <div class="pass-strength">
                    <div class="pass-bar" id="pb1"></div>
                    <div class="pass-bar" id="pb2"></div>
                    <div class="pass-bar" id="pb3"></div>
                    <div class="pass-bar" id="pb4"></div>
                </div>
                <div class="pass-hint" id="passHint">Masukkan password untuk cek kekuatannya</div>
            </div>

            <div class="fg">
                <label>Konfirmasi Password</label>
                <div class="fg-inner">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" id="reg_password_confirm" placeholder="Ulangi password" autocomplete="new-password">
                    <span class="pass-toggle" onclick="togglePass('reg_password_confirm', this)" title="Lihat password">
                        <i class="bi bi-eye" id="eye_confirm"></i>
                    </span>
                </div>
            </div>

            <button class="btn-next" onclick="goStep2()">
                Lanjut — Setup Bisnis
                <i class="bi bi-arrow-right"></i>
            </button>
        </div>

        <!-- ── STEP 2: Bisnis ── -->
        <div class="step-panel" id="step2">
            <div class="step-eyebrow">Langkah 2 dari 3</div>
            <div class="step-title">Setup Bisnis Anda</div>
            <div class="step-desc">Informasi dasar tentang bisnis yang akan dikelola</div>

            <div class="fg">
                <label>Nama Bisnis</label>
                <div class="fg-inner">
                    <i class="bi bi-shop"></i>
                    <input type="text" id="reg_biz_name" placeholder="Contoh: Kopi Nusantara">
                </div>
            </div>

            <div class="fg">
                <label>Jenis Bisnis</label>
                <div class="biztype-grid">
                    <label class="biztype-option">
                        <input type="radio" name="biz_type_radio" value="fnb">
                        <div class="biztype-card">
                            <i class="bi bi-cup-hot"></i>
                            <span>F&B / Café</span>
                        </div>
                    </label>
                    <label class="biztype-option">
                        <input type="radio" name="biz_type_radio" value="retail">
                        <div class="biztype-card">
                            <i class="bi bi-shop-window"></i>
                            <span>Retail / Toko</span>
                        </div>
                    </label>
                    <label class="biztype-option">
                        <input type="radio" name="biz_type_radio" value="laundry">
                        <div class="biztype-card">
                            <i class="bi bi-droplet-half"></i>
                            <span>Laundry</span>
                        </div>
                    </label>
                    <label class="biztype-option">
                        <input type="radio" name="biz_type_radio" value="jasa">
                        <div class="biztype-card">
                            <i class="bi bi-tools"></i>
                            <span>Jasa / Service</span>
                        </div>
                    </label>
                    <label class="biztype-option">
                        <input type="radio" name="biz_type_radio" value="fashion">
                        <div class="biztype-card">
                            <i class="bi bi-bag-heart"></i>
                            <span>Fashion</span>
                        </div>
                    </label>
                    <label class="biztype-option">
                        <input type="radio" name="biz_type_radio" value="lainnya">
                        <div class="biztype-card">
                            <i class="bi bi-grid-3x3-gap"></i>
                            <span>Lainnya</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="fg">
                <label>Nomor WhatsApp Bisnis <span style="color:rgba(255,255,255,0.25);font-weight:400;text-transform:none;letter-spacing:0">(opsional)</span></label>
                <div class="fg-inner">
                    <i class="bi bi-whatsapp"></i>
                    <input type="tel" id="reg_biz_wa" placeholder="628xxxxxxxxx">
                </div>
            </div>

            <div class="fg">
                <label>Kota <span style="color:rgba(255,255,255,0.25);font-weight:400;text-transform:none;letter-spacing:0">(opsional)</span></label>
                <div class="fg-inner">
                    <i class="bi bi-geo-alt"></i>
                    <input type="text" id="reg_city" placeholder="Contoh: Bandung">
                </div>
            </div>

            <button class="btn-next" onclick="goStep3()">
                Lanjut — Pilih Paket
                <i class="bi bi-arrow-right"></i>
            </button>
            <button class="btn-back" onclick="goBack(1)">
                <i class="bi bi-arrow-left"></i> Kembali
            </button>
        </div>

        <!-- ── STEP 3: Paket ── -->
        <div class="step-panel" id="step3">
            <div class="step-eyebrow">Langkah 3 dari 3</div>
            <div class="step-title">Pilih Paket</div>
            <div class="step-desc">Upgrade atau downgrade kapan saja, tanpa kontrak</div>

            <div class="paket-grid">
                <label class="paket-option">
                    <input type="radio" name="paket" value="starter" id="p_starter">
                    <div class="paket-label">
                        <div class="paket-left">
                            <div class="paket-icon" style="background:rgba(148,163,184,0.1)">
                                <i class="bi bi-box" style="color:#94A3B8"></i>
                            </div>
                            <div>
                                <div class="paket-name">Starter</div>
                                <div class="paket-sub">1 kasir · 100 produk</div>
                            </div>
                        </div>
                        <div class="paket-price">
                            Gratis
                            <small>/ bulan</small>
                        </div>
                    </div>
                </label>

                <label class="paket-option">
                    <span class="paket-popular">⭐ POPULER</span>
                    <input type="radio" name="paket" value="pro" id="p_pro" checked>
                    <div class="paket-label">
                        <div class="paket-left">
                            <div class="paket-icon" style="background:rgba(26,86,219,0.15)">
                                <i class="bi bi-rocket-takeoff" style="color:#60a5fa"></i>
                            </div>
                            <div>
                                <div class="paket-name">Pro</div>
                                <div class="paket-sub">5 kasir · unlimited produk</div>
                            </div>
                        </div>
                        <div class="paket-price">
                            299K
                            <small>/ bulan</small>
                        </div>
                    </div>
                </label>

                <label class="paket-option">
                    <input type="radio" name="paket" value="business" id="p_business">
                    <div class="paket-label">
                        <div class="paket-left">
                            <div class="paket-icon" style="background:rgba(139,92,246,0.12)">
                                <i class="bi bi-buildings" style="color:#a78bfa"></i>
                            </div>
                            <div>
                                <div class="paket-name">Business</div>
                                <div class="paket-sub">Unlimited kasir · 5 bisnis</div>
                            </div>
                        </div>
                        <div class="paket-price">
                            799K
                            <small>/ bulan</small>
                        </div>
                    </div>
                </label>
            </div>

            <div class="trial-box">
                <div class="trial-box-title"><i class="bi bi-gift"></i> 14 hari uji coba gratis</div>
                <div class="trial-box-sub">Tidak perlu kartu kredit · Batalkan kapan saja</div>
            </div>

            <button class="btn-next" id="btnRegister" onclick="submitRegister()">
                <i class="bi bi-check-circle"></i>
                Buat Akun & Mulai Gratis
            </button>
            <button class="btn-back" onclick="goBack(2)">
                <i class="bi bi-arrow-left"></i> Kembali
            </button>
        </div>

        <!-- ── SUKSES ── -->
        <div class="step-panel" id="stepSuccess">
            <div class="success-wrap">
                <div class="success-icon">
                    <i class="bi bi-check-lg" style="color:#10B981;font-size:32px"></i>
                </div>
                <div style="font-size:22px;font-weight:800;color:#fff;margin-bottom:6px">Akun Berhasil Dibuat!</div>
                <div style="font-size:13px;color:rgba(255,255,255,0.45);line-height:1.6">
                    Bisnis Anda sudah aktif dan siap digunakan.
                </div>

                <div class="summary-box">
                    <div class="summary-label">Ringkasan Akun</div>
                    <div class="summary-row"><i class="bi bi-person-circle"></i> <span id="sum_name">-</span></div>
                    <div class="summary-row"><i class="bi bi-envelope"></i> <span id="sum_email">-</span></div>
                    <div class="summary-row"><i class="bi bi-shop"></i> <span id="sum_biz">-</span></div>
                    <div class="summary-row"><i class="bi bi-box-seam"></i> <span id="sum_paket">-</span></div>
                </div>

                <a href="{{ route('admin.dashboard') }}"
                   style="display:flex;align-items:center;justify-content:center;gap:8px;padding:14px;border-radius:12px;background:linear-gradient(135deg,#1A56DB,#3B82F6);color:#fff;font-size:15px;font-weight:700;text-decoration:none;box-shadow:0 6px 20px rgba(26,86,219,0.4);transition:all 0.2s"
                   onmouseover="this.style.transform='translateY(-1px)'"
                   onmouseout="this.style.transform='none'">
                    <i class="bi bi-speedometer2"></i>
                    Buka Dashboard
                </a>
            </div>
        </div>

    </div>
</div>

<script>
    let currentStep = 1;

    function showError(msg) {
        const el = document.getElementById('regError');
        document.getElementById('regErrorMsg').textContent = msg;
        el.classList.add('show');
        window.scrollTo({ top: 0, behavior: 'smooth' });
        setTimeout(() => el.classList.remove('show'), 5000);
    }

    function updateProgress(step) {
        for (let i = 1; i <= 3; i++) {
            const dot = document.getElementById('dot' + i);
            dot.classList.remove('active', 'done');
            if (i < step) {
                dot.classList.add('done');
                dot.innerHTML = '<i class="bi bi-check" style="font-size:13px"></i>';
            } else if (i === step) {
                dot.classList.add('active');
                dot.textContent = i;
            } else {
                dot.textContent = i;
            }
        }
        for (let i = 1; i <= 2; i++) {
            document.getElementById('line' + i).classList.toggle('done', i < step);
        }
    }

    function goStep2() {
        const name    = document.getElementById('reg_name').value.trim();
        const email   = document.getElementById('reg_email').value.trim();
        const pass    = document.getElementById('reg_password').value;
        const confirm = document.getElementById('reg_password_confirm').value;

        if (!name)                          return showError('Nama lengkap wajib diisi.');
        if (!email || !/\S+@\S+\.\S+/.test(email)) return showError('Format email tidak valid.');
        if (pass.length < 8)                return showError('Password minimal 8 karakter.');
        if (pass !== confirm)               return showError('Password dan konfirmasi tidak cocok.');

        document.getElementById('step1').classList.remove('active');
        document.getElementById('step2').classList.add('active');
        updateProgress(2);
        currentStep = 2;
    }

    function goStep3() {
        const biz  = document.getElementById('reg_biz_name').value.trim();
        const type = document.querySelector('input[name="biz_type_radio"]:checked')?.value;

        if (!biz)  return showError('Nama bisnis wajib diisi.');
        if (!type) return showError('Pilih jenis bisnis terlebih dahulu.');

        document.getElementById('step2').classList.remove('active');
        document.getElementById('step3').classList.add('active');
        updateProgress(3);
        currentStep = 3;
    }

    function goBack(to) {
        document.getElementById('step' + (to + 1)).classList.remove('active');
        document.getElementById('step' + to).classList.add('active');
        updateProgress(to);
        currentStep = to;
    }

    function checkStrength(val) {
        const bars = [1,2,3,4].map(i => document.getElementById('pb' + i));
        const hint = document.getElementById('passHint');
        bars.forEach(b => b.style.background = 'rgba(255,255,255,0.08)');

        if (!val) {
            hint.textContent = 'Masukkan password untuk cek kekuatannya';
            hint.style.color = 'rgba(255,255,255,0.3)';
            return;
        }

        let score = 0;
        if (val.length >= 8)           score++;
        if (/[A-Z]/.test(val))         score++;
        if (/[0-9]/.test(val))         score++;
        if (/[^A-Za-z0-9]/.test(val))  score++;

        const colors = ['#EF4444', '#F59E0B', '#3B82F6', '#10B981'];
        const labels = ['Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];

        for (let i = 0; i < score; i++) bars[i].style.background = colors[score - 1];
        hint.textContent = 'Kekuatan: ' + labels[score - 1];
        hint.style.color = colors[score - 1];
    }

    function togglePass(id, btn) {
        const inp = document.getElementById(id);
        const icon = btn.querySelector('i');
        if (inp.type === 'password') {
            inp.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            inp.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    function submitRegister() {
        const paket = document.querySelector('input[name="paket"]:checked')?.value;
        if (!paket) return showError('Pilih paket terlebih dahulu.');

        const btn = document.getElementById('btnRegister');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Membuat akun...';

        const payload = {
            name:                  document.getElementById('reg_name').value.trim(),
            email:                 document.getElementById('reg_email').value.trim(),
            password:              document.getElementById('reg_password').value,
            password_confirmation: document.getElementById('reg_password_confirm').value,
            business_name:         document.getElementById('reg_biz_name').value.trim(),
            business_type: document.querySelector('input[name="biz_type_radio"]:checked')?.value || '',
            business_wa:           document.getElementById('reg_biz_wa').value.trim(),
            city:                  document.getElementById('reg_city').value.trim(),
            paket:                 paket
        };

        fetch('{{ route("register") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const paketMap = {
                    starter:  'Starter (Gratis)',
                    pro:      'Pro — Rp 299.000/bln',
                    business: 'Business — Rp 799.000/bln'
                };
                document.getElementById('sum_name').textContent   = payload.name;
                document.getElementById('sum_email').textContent  = payload.email;
                document.getElementById('sum_biz').textContent    = payload.business_name;
                document.getElementById('sum_paket').textContent  = paketMap[paket];

                document.getElementById('step3').classList.remove('active');
                document.getElementById('stepSuccess').classList.add('active');
                document.getElementById('progressSteps').style.display = 'none';
            } else {
                showError(data.message || 'Pendaftaran gagal. Silakan coba lagi.');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-circle"></i> Buat Akun & Mulai Gratis';
            }
        })
        .catch(() => {
            showError('Terjadi kesalahan jaringan. Silakan coba lagi.');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-circle"></i> Buat Akun & Mulai Gratis';
        });
    }
</script>
</body>
</html>