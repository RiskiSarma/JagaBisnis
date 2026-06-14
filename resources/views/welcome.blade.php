<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JagaBisnis POS — Sistem Kasir Modern untuk Bisnis Indonesia</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand: #1A56DB;
            --brand-dark: #1043b5;
            --brand-light: #EBF2FF;
            --accent: #F59E0B;
            --accent2: #10B981;
            --dark: #0B1426;
            --dark2: #0f172a;
            --text: #0F172A;
            --text2: #475569;
            --text3: #94A3B8;
            --border: #E2E8F0;
            --white: #FFFFFF;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text);
            background: var(--white);
            overflow-x: hidden;
        }

        /* ── NAVBAR ── */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            padding: 0 24px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(11,20,38,0.92);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            transition: all 0.3s;
        }

        .navbar.scrolled {
            background: rgba(11,20,38,0.98);
            box-shadow: 0 4px 24px rgba(0,0,0,0.4);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .nav-brand img {
            height: 36px;
            object-fit: contain;
        }

        .nav-brand span {
            font-size: 16px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.3px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            color: rgba(255,255,255,0.65);
            transition: color 0.2s;
        }

        .nav-links a:hover { color: #fff; }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-nav-login {
            padding: 8px 18px;
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.2s;
            background: transparent;
        }

        .btn-nav-login:hover {
            border-color: rgba(255,255,255,0.5);
            color: #fff;
            background: rgba(255,255,255,0.07);
        }

        .btn-nav-cta {
            padding: 8px 20px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            background: var(--brand);
            border: 1px solid var(--brand);
            transition: all 0.2s;
        }

        .btn-nav-cta:hover {
            background: var(--brand-dark);
            box-shadow: 0 4px 16px rgba(26,86,219,0.4);
        }

        /* Mobile nav toggle */
        .nav-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 4px;
        }

        .nav-toggle span {
            display: block;
            width: 22px;
            height: 2px;
            background: rgba(255,255,255,0.75);
            border-radius: 2px;
            transition: all 0.3s;
        }

        .mobile-menu {
            display: none;
            position: fixed;
            top: 68px; left: 0; right: 0;
            background: rgba(11,20,38,0.98);
            backdrop-filter: blur(16px);
            padding: 16px 24px 24px;
            z-index: 99;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .mobile-menu.open { display: block; }

        .mobile-menu a {
            display: block;
            padding: 12px 0;
            font-size: 15px;
            font-weight: 500;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .mobile-menu a:last-child { border-bottom: none; }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            background: linear-gradient(140deg, #080f1f 0%, #0c1e48 45%, #1A56DB 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 120px 24px 80px;
            position: relative;
            overflow: hidden;
        }

        /* Grid pattern */
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        /* Glow */
        .hero::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(26,86,219,0.25) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 820px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            background: rgba(26,86,219,0.2);
            border: 1px solid rgba(26,86,219,0.4);
            font-size: 12px;
            font-weight: 700;
            color: #93c5fd;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 24px;
        }

        .hero-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #60a5fa;
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }

        .hero h1 {
            font-size: clamp(38px, 6vw, 72px);
            font-weight: 900;
            color: #fff;
            line-height: 1.07;
            letter-spacing: -1.5px;
            margin-bottom: 24px;
        }

        .hero h1 .highlight {
            background: linear-gradient(135deg, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-desc {
            font-size: clamp(16px, 2vw, 20px);
            color: rgba(255,255,255,0.6);
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto 40px;
        }

        .hero-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 56px;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 16px 32px;
            border-radius: 14px;
            background: linear-gradient(135deg, #1A56DB, #3B82F6);
            color: #fff;
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 8px 32px rgba(26,86,219,0.4);
            transition: all 0.2s;
        }

        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(26,86,219,0.5);
        }

        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 16px 32px;
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.85);
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-hero-secondary:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.4);
            color: #fff;
        }

        /* Stats bar */
        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 48px;
            flex-wrap: wrap;
            padding-top: 40px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .hero-stat {
            text-align: center;
        }

        .hero-stat-num {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 32px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -1px;
        }

        .hero-stat-label {
            font-size: 13px;
            color: rgba(255,255,255,0.45);
            margin-top: 2px;
        }

        /* ── MARQUEE ── */
        .marquee-section {
            background: #0f172a;
            padding: 20px 0;
            overflow: hidden;
            border-top: 1px solid rgba(255,255,255,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .marquee-track {
            display: flex;
            gap: 48px;
            animation: marquee 20s linear infinite;
            white-space: nowrap;
        }

        .marquee-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            color: rgba(255,255,255,0.35);
            flex-shrink: 0;
        }

        .marquee-item span { color: rgba(255,255,255,0.55); }

        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* ── SECTIONS ── */
        section { padding: 96px 24px; }

        .container {
            max-width: 1180px;
            margin: 0 auto;
        }

        .section-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--brand);
            margin-bottom: 12px;
        }

        .section-title {
            font-size: clamp(28px, 4vw, 44px);
            font-weight: 800;
            color: var(--text);
            letter-spacing: -1px;
            line-height: 1.15;
            margin-bottom: 16px;
        }

        .section-desc {
            font-size: 17px;
            color: var(--text2);
            line-height: 1.7;
            max-width: 560px;
        }

        /* ── FEATURES ── */
        .features-section { background: #f8fafc; }

        .features-header {
            text-align: center;
            margin-bottom: 64px;
        }

        .features-header .section-desc {
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 24px;
        }

        .feature-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 28px;
            transition: all 0.25s;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--brand), #60a5fa);
            opacity: 0;
            transition: opacity 0.25s;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.1);
            border-color: rgba(26,86,219,0.2);
        }

        .feature-card:hover::before { opacity: 1; }

        .feature-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .feature-icon svg {
            width: 24px;
            height: 24px;
        }

        .feature-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 8px;
        }

        .feature-desc {
            font-size: 14px;
            color: var(--text2);
            line-height: 1.65;
        }

        /* ── HOW IT WORKS ── */
        .how-section { background: #fff; }

        .how-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .steps-list {
            display: flex;
            flex-direction: column;
            gap: 32px;
            margin-top: 40px;
        }

        .step-item {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .step-num {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--brand);
            color: #fff;
            font-size: 14px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-family: 'Space Grotesk', sans-serif;
        }

        .step-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 4px;
        }

        .step-desc {
            font-size: 14px;
            color: var(--text2);
            line-height: 1.6;
        }

        /* Mock POS display */
        .how-visual {
            position: relative;
        }

        .mock-device {
            background: linear-gradient(145deg, #0B1426, #1a2e5a);
            border-radius: 24px;
            padding: 20px;
            box-shadow: 0 40px 80px rgba(0,0,0,0.3), 0 0 0 1px rgba(255,255,255,0.05);
        }

        .mock-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: rgba(255,255,255,0.04);
            border-radius: 12px;
            margin-bottom: 16px;
        }

        .mock-brand {
            font-size: 13px;
            font-weight: 700;
            color: #fff;
        }

        .mock-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1A56DB, #60a5fa);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 700;
            color: #fff;
        }

        .mock-content {
            display: grid;
            grid-template-columns: 1fr 140px;
            gap: 12px;
        }

        .mock-products {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .mock-product {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 10px;
            padding: 10px;
        }

        .mock-product-name {
            font-size: 10px;
            font-weight: 600;
            color: rgba(255,255,255,0.8);
            margin-bottom: 2px;
        }

        .mock-product-price {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 11px;
            font-weight: 700;
            color: #60a5fa;
        }

        .mock-cart {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 12px;
            padding: 12px;
            display: flex;
            flex-direction: column;
        }

        .mock-cart-title {
            font-size: 11px;
            font-weight: 700;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .mock-cart-item {
            font-size: 10px;
            color: rgba(255,255,255,0.7);
            padding: 5px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex;
            justify-content: space-between;
        }

        .mock-total {
            margin-top: auto;
            padding-top: 8px;
        }

        .mock-total-label { font-size: 9px; color: rgba(255,255,255,0.4); }

        .mock-total-val {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: #fff;
        }

        .mock-pay-btn {
            margin-top: 8px;
            width: 100%;
            padding: 8px;
            background: linear-gradient(135deg, #1A56DB, #3B82F6);
            border: none;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            color: #fff;
            cursor: default;
        }

        /* ── ROLES ── */
        .roles-section {
            background: linear-gradient(135deg, #080f1f 0%, #0c2161 60%, #0f172a 100%);
            position: relative;
            overflow: hidden;
        }

        .roles-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 64px 64px;
        }

        .roles-section .container { position: relative; z-index: 1; }

        .roles-section .section-title { color: #fff; }

        .roles-section .section-desc { color: rgba(255,255,255,0.55); }

        .roles-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 56px;
        }

        .role-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            padding: 28px;
            transition: all 0.25s;
        }

        .role-card:hover {
            background: rgba(255,255,255,0.07);
            border-color: rgba(255,255,255,0.15);
            transform: translateY(-4px);
        }

        .role-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .role-title {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 8px;
        }

        .role-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        .role-features {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .role-features li {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: 13px;
            color: rgba(255,255,255,0.6);
        }

        .role-features li::before {
            content: '✓';
            color: #10B981;
            font-weight: 700;
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* ── TESTIMONIALS ── */
        .testi-section { background: #f8fafc; }

        .testi-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 56px;
        }

        .testi-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 28px;
        }

        .testi-stars {
            display: flex;
            gap: 2px;
            margin-bottom: 16px;
        }

        .testi-stars span {
            font-size: 14px;
            color: #F59E0B;
        }

        .testi-quote {
            font-size: 15px;
            color: var(--text2);
            line-height: 1.7;
            margin-bottom: 24px;
        }

        .testi-author {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .testi-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .testi-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
        }

        .testi-biz {
            font-size: 12px;
            color: var(--text3);
        }

        /* ── CTA ── */
        .cta-section {
            background: linear-gradient(135deg, #1A56DB 0%, #1043b5 50%, #0c2161 100%);
            text-align: center;
            padding: 100px 24px;
            position: relative;
            overflow: hidden;
        }

        .cta-section::after {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }

        .cta-section::before {
            content: '';
            position: absolute;
            bottom: -60px; left: -60px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }

        .cta-section .container { position: relative; z-index: 1; }

        .cta-section h2 {
            font-size: clamp(28px, 4vw, 48px);
            font-weight: 800;
            color: #fff;
            letter-spacing: -1px;
            margin-bottom: 16px;
        }

        .cta-section p {
            font-size: 18px;
            color: rgba(255,255,255,0.7);
            margin-bottom: 40px;
        }

        .cta-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-cta-white {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 16px 36px;
            border-radius: 14px;
            background: #fff;
            color: var(--brand);
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            transition: all 0.2s;
        }

        .btn-cta-white:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.25);
        }

        .btn-cta-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 16px 36px;
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,0.3);
            color: rgba(255,255,255,0.9);
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-cta-outline:hover {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.5);
            color: #fff;
        }

        /* ── FOOTER ── */
        footer {
            background: #080f1f;
            padding: 56px 24px 32px;
        }

        .footer-top {
            display: flex;
            justify-content: space-between;
            gap: 48px;
            flex-wrap: wrap;
            padding-bottom: 40px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            margin-bottom: 28px;
        }

        .footer-brand img {
            height: 32px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .footer-brand-name {
            font-size: 15px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 8px;
        }

        .footer-brand-desc {
            font-size: 13px;
            color: rgba(255,255,255,0.35);
            max-width: 260px;
            line-height: 1.6;
        }

        .footer-links-title {
            font-size: 12px;
            font-weight: 700;
            color: rgba(255,255,255,0.4);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 14px;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .footer-links a {
            font-size: 13px;
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover { color: rgba(255,255,255,0.85); }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .footer-copy {
            font-size: 12px;
            color: rgba(255,255,255,0.25);
        }

        .footer-wa {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 10px;
            background: rgba(37,211,102,0.12);
            border: 1px solid rgba(37,211,102,0.25);
            color: #25D366;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .footer-wa:hover {
            background: rgba(37,211,102,0.2);
            border-color: rgba(37,211,102,0.4);
        }

        /* ── SCROLL REVEAL ── */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .roles-grid { grid-template-columns: repeat(2, 1fr); }
            .testi-grid { grid-template-columns: repeat(2, 1fr); }
            .how-layout { grid-template-columns: 1fr; gap: 48px; }
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .btn-nav-login { display: none; }
            .nav-toggle { display: flex; }
            section { padding: 72px 20px; }
            .roles-grid { grid-template-columns: 1fr; }
            .testi-grid { grid-template-columns: 1fr; }
            .features-grid { grid-template-columns: 1fr; }
            .hero-stats { gap: 28px; }
            .footer-top { flex-direction: column; gap: 32px; }
        }

        @media (max-width: 480px) {
            .hero h1 { font-size: 32px; }
            .btn-hero-primary, .btn-hero-secondary { padding: 14px 24px; font-size: 14px; }
        }
    </style>
</head>
<body>

<!-- ── NAVBAR ── -->
<nav class="navbar" id="navbar">
    <a href="#" class="nav-brand">
        <img src="https://res.cloudinary.com/dx21r1pko/image/upload/q_auto/f_auto/v1776681943/logo_jagabisnis_usq1pu.png" alt="JagaBisnis">
        <span>JagaBisnis</span>
    </a>

    <ul class="nav-links">
        <li><a href="#fitur">Fitur</a></li>
        <li><a href="#cara-kerja">Cara Kerja</a></li>
        <li><a href="#role">Akses</a></li>
        <li><a href="#testimoni">Testimoni</a></li>
    </ul>

    <div class="nav-right">
        <a href="{{ route('login') }}" class="btn-nav-login">Masuk</a>
        <a href="{{ route('login') }}" class="btn-nav-cta">Mulai Gratis →</a>
        <div class="nav-toggle" id="navToggle" onclick="toggleMobileMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</nav>

<div class="mobile-menu" id="mobileMenu">
    <a href="#fitur" onclick="closeMobileMenu()">Fitur</a>
    <a href="#cara-kerja" onclick="closeMobileMenu()">Cara Kerja</a>
    <a href="#role" onclick="closeMobileMenu()">Akses</a>
    <a href="#testimoni" onclick="closeMobileMenu()">Testimoni</a>
    <a href="{{ route('login') }}" style="color:#60a5fa;font-weight:700">Masuk ke Sistem →</a>
</div>

<!-- ── HERO ── -->
<section class="hero">
    <div class="hero-content">
        <div class="hero-badge">🚀 Sistem POS Multi-Bisnis Modern</div>

        <h1>
            Satu Platform,<br>
            <span class="highlight">Semua Bisnis</span><br>
            Terkendali
        </h1>

        <p class="hero-desc">
            JagaBisnis POS menyederhanakan operasional kasir, stok, laporan,
            dan customer dalam satu sistem yang mudah digunakan — dari warung
            hingga jaringan toko.
        </p>

        <div class="hero-actions">
            <a href="{{ route('login') }}" class="btn-hero-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                Masuk ke Sistem
            </a>
            <a href="#fitur" class="btn-hero-secondary">
                Lihat Fitur
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
            </a>
        </div>

        <div class="hero-stats">
            <div class="hero-stat">
                <div class="hero-stat-num">500+</div>
                <div class="hero-stat-label">Bisnis Aktif</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num">50rb+</div>
                <div class="hero-stat-label">Transaksi / Bulan</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num">3</div>
                <div class="hero-stat-label">Level Akses</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num">99.9%</div>
                <div class="hero-stat-label">Uptime</div>
            </div>
        </div>
    </div>
</section>

<!-- ── MARQUEE ── -->
<div class="marquee-section">
    <div class="marquee-track">
        <div class="marquee-item">☕ <span>F&B</span></div>
        <div class="marquee-item">🧺 <span>Laundry</span></div>
        <div class="marquee-item">🛒 <span>Retail</span></div>
        <div class="marquee-item">🔧 <span>Jasa</span></div>
        <div class="marquee-item">✅ <span>Manajemen Stok</span></div>
        <div class="marquee-item">📊 <span>Laporan Real-time</span></div>
        <div class="marquee-item">💬 <span>Kirim Nota via WA</span></div>
        <div class="marquee-item">🎁 <span>Promo & Diskon</span></div>
        <div class="marquee-item">👥 <span>Multi Kasir</span></div>
        <div class="marquee-item">🏪 <span>Multi Bisnis</span></div>
        <!-- Duplikasi untuk loop seamless -->
        <div class="marquee-item">☕ <span>F&B</span></div>
        <div class="marquee-item">🧺 <span>Laundry</span></div>
        <div class="marquee-item">🛒 <span>Retail</span></div>
        <div class="marquee-item">🔧 <span>Jasa</span></div>
        <div class="marquee-item">✅ <span>Manajemen Stok</span></div>
        <div class="marquee-item">📊 <span>Laporan Real-time</span></div>
        <div class="marquee-item">💬 <span>Kirim Nota via WA</span></div>
        <div class="marquee-item">🎁 <span>Promo & Diskon</span></div>
        <div class="marquee-item">👥 <span>Multi Kasir</span></div>
        <div class="marquee-item">🏪 <span>Multi Bisnis</span></div>
    </div>
</div>

<!-- ── FEATURES ── -->
<section class="features-section" id="fitur">
    <div class="container">
        <div class="features-header reveal">
            <div class="section-eyebrow">✦ Fitur Unggulan</div>
            <h2 class="section-title">Semua yang Bisnis Anda Butuhkan</h2>
            <p class="section-desc">Dari transaksi kasir hingga laporan keuangan — semuanya dalam satu platform yang terintegrasi dan mudah digunakan.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card reveal" style="transition-delay:0.05s">
                <div class="feature-icon" style="background:#EBF2FF">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#1A56DB" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2"/><line x1="8" y1="6" x2="16" y2="6"/><line x1="8" y1="10" x2="10" y2="10"/><line x1="12" y1="10" x2="14" y2="10"/><line x1="16" y1="10" x2="16" y2="10"/></svg>
                </div>
                <div class="feature-title">Kasir / POS Cepat</div>
                <p class="feature-desc">Antarmuka kasir yang intuitif dengan pencarian produk, keranjang belanja real-time, dan berbagai metode pembayaran (tunai & transfer).</p>
            </div>

            <div class="feature-card reveal" style="transition-delay:0.1s">
                <div class="feature-icon" style="background:#ECFDF5">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                </div>
                <div class="feature-title">Laporan Real-time</div>
                <p class="feature-desc">Pantau revenue harian, bulanan, tahunan. Laporan produk terlaris dan customer terbaik disajikan dalam grafik yang mudah dibaca.</p>
            </div>

            <div class="feature-card reveal" style="transition-delay:0.15s">
                <div class="feature-icon" style="background:#FEF3C7">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/></svg>
                </div>
                <div class="feature-title">Manajemen Stok</div>
                <p class="feature-desc">Stok produk berkurang otomatis saat transaksi. Notifikasi stok menipis dan tampilan stok di POS agar kasir tahu ketersediaan produk.</p>
            </div>

            <div class="feature-card reveal" style="transition-delay:0.2s">
                <div class="feature-icon" style="background:#F3E8FF">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#8B5CF6" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div class="feature-title">Data Customer Terintegrasi</div>
                <p class="feature-desc">Rekam histori belanja, kunjungan, dan nilai spending setiap customer. Sistem tier otomatis: VIP, Reguler, dan Baru.</p>
            </div>

            <div class="feature-card reveal" style="transition-delay:0.25s">
                <div class="feature-icon" style="background:#D1FAE5">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                </div>
                <div class="feature-title">Nota Digital via WhatsApp</div>
                <p class="feature-desc">Kirim struk belanja langsung ke WhatsApp pelanggan. Termasuk fitur follow-up customer dengan template pesan dan promo aktif.</p>
            </div>

            <div class="feature-card reveal" style="transition-delay:0.3s">
                <div class="feature-icon" style="background:#FEE2E2">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                </div>
                <div class="feature-title">Promo & Kode Diskon</div>
                <p class="feature-desc">Buat promo persen atau nominal, atur minimum pembelian, aktifkan/nonaktifkan kapan saja. Promo langsung terapkan di POS.</p>
            </div>
        </div>
    </div>
</section>

<!-- ── HOW IT WORKS ── -->
<section class="how-section" id="cara-kerja">
    <div class="container">
        <div class="how-layout">
            <div>
                <div class="reveal">
                    <div class="section-eyebrow">✦ Cara Kerja</div>
                    <h2 class="section-title">Mulai dalam Hitungan Menit</h2>
                    <p class="section-desc">Setup cepat, antarmuka intuitif — tidak perlu training panjang untuk mulai menggunakan JagaBisnis POS.</p>
                </div>

                <div class="steps-list">
                    <div class="step-item reveal" style="transition-delay:0.1s">
                        <div class="step-num">1</div>
                        <div>
                            <div class="step-title">Daftar & Setup Bisnis</div>
                            <p class="step-desc">Super Admin membuat akun bisnis dan mengatur profil toko, jenis bisnis, dan fitur yang diperlukan.</p>
                        </div>
                    </div>
                    <div class="step-item reveal" style="transition-delay:0.2s">
                        <div class="step-num">2</div>
                        <div>
                            <div class="step-title">Input Produk & Promo</div>
                            <p class="step-desc">Manager menambahkan katalog produk lengkap dengan harga, stok, dan kode promo aktif.</p>
                        </div>
                    </div>
                    <div class="step-item reveal" style="transition-delay:0.3s">
                        <div class="step-num">3</div>
                        <div>
                            <div class="step-title">Tambah Akun Kasir</div>
                            <p class="step-desc">Buat akun kasir untuk setiap pegawai. Kasir hanya bisa akses fitur yang mereka butuhkan.</p>
                        </div>
                    </div>
                    <div class="step-item reveal" style="transition-delay:0.4s">
                        <div class="step-num">4</div>
                        <div>
                            <div class="step-title">Mulai Transaksi!</div>
                            <p class="step-desc">Kasir langsung bisa melayani pelanggan, cetak struk, dan kirim nota via WhatsApp.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mock POS Visual -->
            <div class="how-visual reveal">
                <div class="mock-device">
                    <div class="mock-topbar">
                        <span class="mock-brand">🏪 Kopi Nusantara</span>
                        <div style="display:flex;align-items:center;gap:8px">
                            <span style="font-size:10px;color:rgba(255,255,255,0.4)">Kasir: Dewi</span>
                            <div class="mock-avatar">DK</div>
                        </div>
                    </div>
                    <div style="font-size:10px;color:rgba(255,255,255,0.3);margin-bottom:10px;padding-left:2px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px">Katalog Produk</div>
                    <div class="mock-content">
                        <div class="mock-products">
                            <div class="mock-product" style="border-color:rgba(26,86,219,0.3);background:rgba(26,86,219,0.12)">
                                <div class="mock-product-name">Es Kopi Susu</div>
                                <div class="mock-product-price">Rp 22.000</div>
                                <div style="font-size:9px;color:rgba(255,255,255,0.3);margin-top:2px">Minuman</div>
                            </div>
                            <div class="mock-product">
                                <div class="mock-product-name">Americano</div>
                                <div class="mock-product-price">Rp 18.000</div>
                                <div style="font-size:9px;color:rgba(255,255,255,0.3);margin-top:2px">Minuman</div>
                            </div>
                            <div class="mock-product">
                                <div class="mock-product-name">Croissant</div>
                                <div class="mock-product-price">Rp 25.000</div>
                                <div style="font-size:9px;color:rgba(255,255,255,0.3);margin-top:2px">Makanan</div>
                            </div>
                            <div class="mock-product">
                                <div class="mock-product-name">Matcha Latte</div>
                                <div class="mock-product-price">Rp 28.000</div>
                                <div style="font-size:9px;color:rgba(255,255,255,0.3);margin-top:2px">Minuman</div>
                            </div>
                        </div>
                        <div class="mock-cart">
                            <div class="mock-cart-title">Pesanan</div>
                            <div class="mock-cart-item">
                                <span>Es Kopi Susu ×2</span>
                                <span style="color:#60a5fa">44rb</span>
                            </div>
                            <div class="mock-cart-item">
                                <span>Croissant ×1</span>
                                <span style="color:#60a5fa">25rb</span>
                            </div>
                            <div class="mock-total">
                                <div class="mock-total-label">TOTAL</div>
                                <div class="mock-total-val">Rp 69.000</div>
                            </div>
                            <button class="mock-pay-btn">💳 Bayar</button>
                        </div>
                    </div>
                    <div style="margin-top:12px;padding:10px 12px;background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.2);border-radius:8px;display:flex;align-items:center;gap:8px">
                        <span style="font-size:11px">✅</span>
                        <span style="font-size:10px;color:#10B981;font-weight:600">Transaksi berhasil — Nota dikirim via WA</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── ROLES ── -->
<section class="roles-section" id="role">
    <div class="container">
        <div class="reveal" style="text-align:center;margin-bottom:20px">
            <div class="section-eyebrow" style="color:#60a5fa;justify-content:center">✦ Level Akses</div>
            <h2 class="section-title">Tiga Peran, Satu Sistem</h2>
            <p class="section-desc" style="margin:0 auto">Setiap pengguna mendapat tampilan dan akses yang sesuai dengan perannya — tidak lebih, tidak kurang.</p>
        </div>

        <div class="roles-grid">
            <!-- Super Admin -->
            <div class="role-card reveal" style="transition-delay:0.1s;border-color:rgba(245,158,11,0.3)">
                <div class="role-icon" style="background:rgba(245,158,11,0.12)">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <div class="role-badge" style="background:rgba(245,158,11,0.12);color:#F59E0B">⚡ Super Admin</div>
                <div class="role-title">Super Admin</div>
                <ul class="role-features">
                    <li>Kelola semua bisnis dalam satu platform</li>
                    <li>Buat & nonaktifkan bisnis kapan saja</li>
                    <li>Atur fitur per bisnis (stok, loyalty, dll)</li>
                    <li>Pantau pengguna dan aktivitas seluruh sistem</li>
                    <li>Dashboard monitoring global</li>
                </ul>
            </div>

            <!-- Manager / Admin -->
            <div class="role-card reveal" style="transition-delay:0.2s;border-color:rgba(16,185,129,0.3)">
                <div class="role-icon" style="background:rgba(16,185,129,0.12)">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <div class="role-badge" style="background:rgba(16,185,129,0.12);color:#10B981">🏪 Manager</div>
                <div class="role-title">Manager / Admin</div>
                <ul class="role-features">
                    <li>Kelola produk, stok, dan harga</li>
                    <li>Lihat & kelola semua transaksi</li>
                    <li>Laporan penjualan, produk, customer</li>
                    <li>Buat promo dan kode diskon</li>
                    <li>Tambah dan kelola akun kasir</li>
                </ul>
            </div>

            <!-- Kasir -->
            <div class="role-card reveal" style="transition-delay:0.3s;border-color:rgba(96,165,250,0.3)">
                <div class="role-icon" style="background:rgba(96,165,250,0.12)">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#60A5FA" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2"/><line x1="8" y1="6" x2="16" y2="6"/><line x1="8" y1="10" x2="10" y2="10"/><line x1="12" y1="10" x2="14" y2="10"/></svg>
                </div>
                <div class="role-badge" style="background:rgba(96,165,250,0.12);color:#60A5FA">🧾 Kasir</div>
                <div class="role-title">Kasir</div>
                <ul class="role-features">
                    <li>Antarmuka POS yang simpel dan cepat</li>
                    <li>Proses transaksi tunai & transfer</li>
                    <li>Cetak struk & kirim nota via WhatsApp</li>
                    <li>Riwayat transaksi shift sendiri</li>
                    <li>Update status lunas / belum lunas</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- ── TESTIMONIALS ── -->
<section class="testi-section" id="testimoni">
    <div class="container">
        <div class="reveal" style="text-align:center;margin-bottom:20px">
            <div class="section-eyebrow" style="justify-content:center">✦ Testimoni</div>
            <h2 class="section-title">Dipercaya Pelaku Bisnis Indonesia</h2>
            <p class="section-desc" style="margin:0 auto">Dari warung kopi hingga laundry — JagaBisnis POS membantu ribuan bisnis berkembang lebih cepat.</p>
        </div>

        <div class="testi-grid">
            <div class="testi-card reveal" style="transition-delay:0.1s">
                <div class="testi-stars">
                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                </div>
                <p class="testi-quote">"Dulu kasir kami pakai buku manual, sekarang semua tercatat otomatis. Laporan penjualan langsung bisa saya cek kapanpun dari HP."</p>
                <div class="testi-author">
                    <div class="testi-avatar" style="background:linear-gradient(135deg,#1A56DB,#60a5fa)">BS</div>
                    <div>
                        <div class="testi-name">Budi Santoso</div>
                        <div class="testi-biz">Owner, Kopi Nusantara</div>
                    </div>
                </div>
            </div>

            <div class="testi-card reveal" style="transition-delay:0.2s">
                <div class="testi-stars">
                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                </div>
                <p class="testi-quote">"Fitur kirim nota WA sangat membantu! Customer senang dapat struk digital langsung. Repeat order meningkat sejak pakai JagaBisnis."</p>
                <div class="testi-author">
                    <div class="testi-avatar" style="background:linear-gradient(135deg,#10B981,#059669)">AL</div>
                    <div>
                        <div class="testi-name">Andi Laundry</div>
                        <div class="testi-biz">Pemilik, Laundry Bersih</div>
                    </div>
                </div>
            </div>

            <div class="testi-card reveal" style="transition-delay:0.3s">
                <div class="testi-stars">
                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                </div>
                <p class="testi-quote">"Satu platform bisa kelola 3 toko sekaligus. Super Admin langsung bisa pantau revenue semua cabang dari satu dashboard."</p>
                <div class="testi-author">
                    <div class="testi-avatar" style="background:linear-gradient(135deg,#8B5CF6,#6D28D9)">SA</div>
                    <div>
                        <div class="testi-name">Sari Anindita</div>
                        <div class="testi-biz">Franchisor, Toko Serba Ada</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── CTA ── -->
<section class="cta-section">
    <div class="container">
        <div class="reveal">
            <h2>Siap Kelola Bisnis Lebih Mudah?</h2>
            <p>Bergabunglah dengan ratusan bisnis yang sudah lebih terorganisir bersama JagaBisnis POS.</p>
            <div class="cta-actions">
                <a href="{{ route('login') }}" class="btn-cta-white">
                    🚀 Masuk Sekarang
                </a>
                <a href="https://wa.me/6282269334494" target="_blank" class="btn-cta-outline">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ── FOOTER ── -->
<footer>
    <div class="container">
        <div class="footer-top">
            <div class="footer-brand">
                <img src="https://res.cloudinary.com/dx21r1pko/image/upload/q_auto/f_auto/v1776681943/logo_jagabisnis_usq1pu.png" alt="JagaBisnis">
                <div class="footer-brand-name">JagaBisnis POS</div>
                <p class="footer-brand-desc">Sistem kasir modern untuk bisnis Indonesia. Multi-bisnis, multi-kasir, satu platform.</p>
            </div>

            <div>
                <div class="footer-links-title">Platform</div>
                <ul class="footer-links">
                    <li><a href="#fitur">Fitur</a></li>
                    <li><a href="#cara-kerja">Cara Kerja</a></li>
                    <li><a href="#role">Level Akses</a></li>
                    <li><a href="#testimoni">Testimoni</a></li>
                </ul>
            </div>

            <div>
                <div class="footer-links-title">Akun</div>
                <ul class="footer-links">
                    <li><a href="{{ route('login') }}">Masuk</a></li>
                    <li><a href="{{ route('login') }}">Daftar Bisnis</a></li>
                </ul>
            </div>

            <div>
                <div class="footer-links-title">Bantuan</div>
                <ul class="footer-links">
                    <li><a href="https://wa.me/6282297207284" target="_blank">WhatsApp Support</a></li>
                    <li><a href="#">Panduan Penggunaan</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p class="footer-copy">© {{ date('Y') }} JagaBisnis POS. Dibuat dengan ❤️ untuk UMKM Indonesia.</p>
            <a href="https://wa.me/6282297207284" target="_blank" class="footer-wa">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                Pusat Bantuan
            </a>
        </div>
    </div>
</footer>

<script>
// Navbar scroll effect
window.addEventListener('scroll', function() {
    var navbar = document.getElementById('navbar');
    if (window.scrollY > 20) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Mobile menu
function toggleMobileMenu() {
    var menu = document.getElementById('mobileMenu');
    menu.classList.toggle('open');
}

function closeMobileMenu() {
    document.getElementById('mobileMenu').classList.remove('open');
}

// Scroll reveal
var revealElements = document.querySelectorAll('.reveal');

var revealObserver = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        }
    });
}, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

revealElements.forEach(function(el) {
    revealObserver.observe(el);
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
    anchor.addEventListener('click', function(e) {
        var target = document.querySelector(this.getAttribute('href'));
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
</script>

</body>
</html>