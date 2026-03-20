<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BES Digital Library')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:        #0d1b2e;
            --navy-deep:   #091525;
            --navy-mid:    #1a3a6b;
            --navy-light:  #1e4d8c;
            --blue:        #2563b0;
            --blue-light:  #3b7dd8;
            --blue-pale:   #dce8f7;
            --gold:        #b8922a;
            --gold-light:  #d4a93a;
            --gold-pale:   #fdf3dc;
            --gold-border: #e8c96a;
            --white:       #ffffff;
            --bg:          #f4f6f9;
            --text:        #2c3e55;
            --text-muted:  #6b7f96;
            --border:      #dde3ec;
            --font-serif:  'Playfair Display', Georgia, serif;
            --font-sans:   'Inter', sans-serif;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font-sans);
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── NAV ── */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            background: rgba(9, 21, 37, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(184,146,42,0.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2.5rem;
            height: 64px;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .nav-logo-placeholder {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            display: flex; align-items: center; justify-content: center;
            font-family: var(--font-serif);
            font-size: 1rem; font-weight: 700;
            color: var(--navy-deep);
        }

        .nav-brand-text {
            font-family: var(--font-serif);
            font-size: 1.1rem; font-weight: 700;
            color: #fff; letter-spacing: 0.02em;
        }

        .nav-brand-text span { color: var(--gold-light); }

        .nav-links {
            display: flex; align-items: center; gap: 0.25rem;
        }

        .nav-links a {
            padding: 0.45rem 0.875rem;
            text-decoration: none;
            color: rgba(255,255,255,0.7);
            font-size: 0.835rem; font-weight: 500;
            border-radius: 6px;
            transition: all 0.18s;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: #fff;
            background: rgba(255,255,255,0.08);
        }

        .nav-login {
            display: inline-flex;
            align-items: center; gap: 0.4rem;
            padding: 0.45rem 1.25rem;
            background: transparent;
            border: 1px solid var(--gold);
            border-radius: 4px;
            color: var(--gold-light);
            font-size: 0.82rem;
            font-family: var(--font-sans);
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            letter-spacing: 0.04em;
        }

        .nav-login:hover { background: var(--gold); color: var(--navy-deep); }

        /* ── PAGE HERO BANNER ── */
        .page-hero {
            background: var(--navy-deep);
            padding: 7rem 2.5rem 4rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .page-hero::before {
            content: '';
            position: absolute; inset: 0;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(37,99,176,0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(184,146,42,0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .page-hero::after {
            content: '';
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
        }

        .page-hero-inner {
            position: relative; z-index: 1;
        }

        .page-hero-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.35rem 1rem;
            background: rgba(184,146,42,0.12);
            border: 1px solid rgba(184,146,42,0.3);
            border-radius: 999px;
            font-size: 0.72rem; font-weight: 600;
            color: var(--gold-light);
            letter-spacing: 0.12em; text-transform: uppercase;
            margin-bottom: 1.25rem;
        }

        .page-hero-badge::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--gold-light);
        }

        .page-hero-title {
            font-family: var(--font-serif);
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 700; color: #fff;
            line-height: 1.15;
        }

        .page-hero-title em { font-style: italic; color: var(--gold-light); }

        .page-hero-divider {
            width: 48px; height: 2px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light));
            border-radius: 2px;
            margin: 1.5rem auto 0;
        }

        /* ── MAIN CONTENT ── */
        .page-content {
            flex: 1;
            max-width: 900px;
            margin: 0 auto;
            padding: 3.5rem 2rem;
            width: 100%;
        }

        /* ── FOOTER ── */
        footer {
            background: var(--navy-deep);
            border-top: 1px solid rgba(184,146,42,0.15);
            padding: 2rem 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-brand {
            font-family: var(--font-serif);
            font-size: 0.9rem;
            color: rgba(168,188,212,0.7);
        }

        .footer-brand strong { color: var(--gold-light); }

        .footer-copy {
            font-size: 0.75rem;
            color: rgba(168,188,212,0.4);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            nav { padding: 0 1.25rem; }
            .nav-links { display: none; }
            .page-content { padding: 2rem 1.25rem; }
            footer { flex-direction: column; text-align: center; }
        }

        @yield('extra-styles')
    </style>
    @yield('head')
</head>
<body>

{{-- ── NAV ── --}}
<nav>
    <a href="{{ url('/') }}" class="nav-brand">
        <div class="nav-logo-placeholder">B</div>
        <span class="nav-brand-text">BES <span>Digital Library</span></span>
    </a>

    <div class="nav-links">
        <a href="{{ url('/') }}" {{ request()->is('/') ? 'class=active' : '' }}>Home</a>
        <a href="{{ route('about') }}" {{ request()->is('about') ? 'class=active' : '' }}>About</a>
        <a href="{{ route('resources') }}" {{ request()->is('resources') ? 'class=active' : '' }}>Resources</a>
        <a href="{{ route('services') }}" {{ request()->is('services') ? 'class=active' : '' }}>Services</a>
    </div>

    <a href="{{ route('login') }}" class="nav-login">
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
        </svg>
        Sign In
    </a>
</nav>

{{-- ── PAGE HERO ── --}}
<div class="page-hero">
    <div class="page-hero-inner">
        <div class="page-hero-badge">@yield('hero-badge', 'BES Digital Library')</div>
        <h1 class="page-hero-title">@yield('hero-title')</h1>
        <div class="page-hero-divider"></div>
    </div>
</div>

{{-- ── CONTENT ── --}}
<div class="page-content">
    @yield('content')
</div>

{{-- ── FOOTER ── --}}
<footer>
    <div class="footer-brand">
        <strong>BES Digital Library</strong> — Empowering learners through knowledge
    </div>
    <div class="footer-copy">
        &copy; {{ date('Y') }} BES Digital Library. All rights reserved.
    </div>
</footer>

@yield('scripts')
</body>
</html>