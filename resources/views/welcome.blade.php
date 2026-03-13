<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BES Digital Library</title>
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
            font-size: 1rem;
            font-weight: 700;
            color: var(--navy-deep);
        }

        /* Replace .nav-logo-placeholder with this once you have a logo:
           <img src="{{ asset('images/logo.png') }}" alt="BES Logo" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
        */

        .nav-brand-text {
            font-family: var(--font-serif);
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.02em;
        }

        .nav-brand-text span {
            color: var(--gold-light);
        }

        .nav-login {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
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

        .nav-login:hover {
            background: var(--gold);
            color: var(--navy-deep);
        }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            background: var(--navy-deep);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            overflow: hidden;
            padding: 6rem 2rem 4rem;
        }

        /* Decorative background pattern */
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(37,99,176,0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(184,146,42,0.1) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(26,58,107,0.3) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Grid texture */
        .hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 780px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 1rem;
            background: rgba(184,146,42,0.12);
            border: 1px solid rgba(184,146,42,0.3);
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--gold-light);
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-bottom: 1.75rem;
            animation: fadeUp 0.6s ease both;
        }

        .hero-badge::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--gold-light);
        }

        .hero-title {
            font-family: var(--font-serif);
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 700;
            color: #ffffff;
            line-height: 1.15;
            margin-bottom: 1.25rem;
            animation: fadeUp 0.6s ease 0.1s both;
        }

        .hero-title em {
            font-style: italic;
            color: var(--gold-light);
        }

        .hero-subtitle {
            font-size: 1.05rem;
            color: rgba(168,188,212,0.85);
            max-width: 560px;
            margin: 0 auto 2.5rem;
            font-weight: 300;
            line-height: 1.8;
            animation: fadeUp 0.6s ease 0.2s both;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeUp 0.6s ease 0.3s both;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 2rem;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: var(--navy-deep);
            font-size: 0.875rem;
            font-weight: 600;
            font-family: var(--font-sans);
            border-radius: 4px;
            text-decoration: none;
            transition: all 0.2s;
            letter-spacing: 0.03em;
        }

        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(184,146,42,0.4);
        }

        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 2rem;
            background: transparent;
            color: rgba(255,255,255,0.8);
            font-size: 0.875rem;
            font-weight: 500;
            font-family: var(--font-sans);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 4px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-hero-secondary:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
            border-color: rgba(255,255,255,0.4);
        }

        .hero-divider {
            width: 48px; height: 2px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light));
            border-radius: 2px;
            margin: 3rem auto 0;
            animation: fadeUp 0.6s ease 0.4s both;
        }

        /* ── STATS STRIP ── */
        .stats-strip {
            background: var(--navy);
            border-top: 1px solid rgba(184,146,42,0.15);
            border-bottom: 1px solid rgba(184,146,42,0.15);
            padding: 2rem 2.5rem;
        }

        .stats-inner {
            max-width: 900px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            text-align: center;
        }

        .stat-item {
            padding: 0.5rem;
        }

        .stat-item-value {
            font-family: var(--font-serif);
            font-size: 2rem;
            font-weight: 700;
            color: var(--gold-light);
            line-height: 1;
            margin-bottom: 0.35rem;
        }

        .stat-item-label {
            font-size: 0.72rem;
            font-weight: 600;
            color: rgba(168,188,212,0.6);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        /* ── FEATURES ── */
        .features {
            padding: 5rem 2.5rem;
            background: var(--bg);
        }

        .section-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 3.5rem;
        }

        .section-eyebrow {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: 0.14em;
            margin-bottom: 0.75rem;
        }

        .section-title {
            font-family: var(--font-serif);
            font-size: clamp(1.6rem, 3vw, 2.25rem);
            font-weight: 700;
            color: var(--navy);
            line-height: 1.25;
            margin-bottom: 0.75rem;
        }

        .section-sub {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.8;
        }

        .features-grid {
            max-width: 960px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        .feature-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 2rem 1.75rem;
            transition: all 0.25s;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--navy-mid), var(--blue));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            border-color: var(--blue-pale);
            box-shadow: 0 8px 24px rgba(13,27,46,0.1);
            transform: translateY(-3px);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 44px; height: 44px;
            background: var(--blue-pale);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.25rem;
            color: var(--blue);
        }

        .feature-icon svg {
            width: 20px; height: 20px;
        }

        .feature-title {
            font-family: var(--font-serif);
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--navy);
            margin-bottom: 0.6rem;
        }

        .feature-desc {
            font-size: 0.845rem;
            color: var(--text-muted);
            line-height: 1.7;
        }

        /* ── BOOKS ── */
        .books-section {
            padding: 5rem 2.5rem;
            background: var(--white);
            border-top: 1px solid var(--border);
        }

        .books-grid {
            max-width: 960px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
        }

        .book-card {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.22s;
            text-decoration: none;
        }

        .book-card:hover {
            border-color: var(--blue-pale);
            box-shadow: 0 6px 18px rgba(13,27,46,0.1);
            transform: translateY(-3px);
        }

        .book-cover {
            width: 100%;
            aspect-ratio: 3/4;
            background: linear-gradient(135deg, var(--navy-mid), var(--navy));
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .book-cover-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: rgba(255,255,255,0.4);
            padding: 1rem;
            text-align: center;
        }

        .book-cover-placeholder svg {
            width: 32px; height: 32px;
            opacity: 0.5;
        }

        .book-cover-placeholder span {
            font-family: var(--font-serif);
            font-size: 0.75rem;
            color: rgba(255,255,255,0.6);
            line-height: 1.3;
        }

        .book-badge {
            position: absolute;
            top: 0.6rem; right: 0.6rem;
            padding: 0.2rem 0.5rem;
            background: rgba(184,146,42,0.9);
            color: var(--navy-deep);
            font-size: 0.62rem;
            font-weight: 700;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .book-info {
            padding: 0.875rem 1rem;
        }

        .book-title {
            font-family: var(--font-serif);
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--navy);
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .book-author {
            font-size: 0.75rem;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .book-category {
            display: inline-block;
            margin-top: 0.5rem;
            padding: 0.15rem 0.5rem;
            background: var(--blue-pale);
            color: var(--blue);
            font-size: 0.65rem;
            font-weight: 600;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .books-footer {
            text-align: center;
            margin-top: 2.5rem;
        }

        .btn-browse {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 2rem;
            background: var(--navy-mid);
            color: #fff;
            font-size: 0.855rem;
            font-weight: 500;
            font-family: var(--font-sans);
            border-radius: 4px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-browse:hover {
            background: var(--navy-light);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(13,27,46,0.25);
        }

        /* ── CTA BANNER ── */
        .cta-banner {
            background: linear-gradient(135deg, var(--navy-deep) 0%, var(--navy-mid) 100%);
            padding: 5rem 2.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 0% 100%, rgba(184,146,42,0.1) 0%, transparent 50%),
                radial-gradient(circle at 100% 0%, rgba(37,99,176,0.15) 0%, transparent 50%);
            pointer-events: none;
        }

        .cta-content {
            position: relative;
            z-index: 1;
            max-width: 600px;
            margin: 0 auto;
        }

        .cta-title {
            font-family: var(--font-serif);
            font-size: clamp(1.75rem, 3.5vw, 2.5rem);
            font-weight: 700;
            color: #fff;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .cta-sub {
            font-size: 0.95rem;
            color: rgba(168,188,212,0.8);
            margin-bottom: 2rem;
            font-weight: 300;
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

        .footer-brand strong {
            color: var(--gold-light);
        }

        .footer-copy {
            font-size: 0.75rem;
            color: rgba(168,188,212,0.4);
        }

        /* ── ANIMATIONS ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            nav { padding: 0 1.25rem; }
            .features-grid { grid-template-columns: 1fr; }
            .books-grid { grid-template-columns: repeat(2, 1fr); }
            .stats-inner { grid-template-columns: 1fr; gap: 1.5rem; }
            .features, .books-section, .cta-banner { padding: 3.5rem 1.25rem; }
            footer { flex-direction: column; text-align: center; }
        }

        @media (max-width: 480px) {
            .books-grid { grid-template-columns: repeat(2, 1fr); }
            .hero-actions { flex-direction: column; align-items: center; }
        }
    </style>
</head>
<body>

{{-- ── NAVIGATION ── --}}
<nav>
    <a href="{{ url('/') }}" class="nav-brand">
        {{-- Replace the div below with your logo once ready: --}}
        {{-- <img src="{{ asset('images/logo.png') }}" alt="BES Logo" style="width:36px;height:36px;border-radius:50%;object-fit:cover;"> --}}
        <div class="nav-logo-placeholder">B</div>
        <span class="nav-brand-text">BES <span>Digital Library</span></span>
    </a>
    <a href="{{ route('login') }}" class="nav-login">
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
        </svg>
        Sign In
    </a>
</nav>

{{-- ── HERO ── --}}
<section class="hero">
    <div class="hero-content">
        <div class="hero-badge">BES Digital Library</div>
        <h1 class="hero-title">
            Knowledge at Your<br><em>Fingertips</em>
        </h1>
        <p class="hero-subtitle">
            Discover, borrow, and read from our growing collection of books, references, and digital resources — all in one place.
        </p>
        <div class="hero-actions">
            <a href="{{ route('login') }}" class="btn-hero-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                Access the Library
            </a>
            <a href="#books" class="btn-hero-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
                Browse Books
            </a>
        </div>
        <div class="hero-divider"></div>
    </div>
</section>

{{-- ── STATS STRIP ── --}}
<div class="stats-strip">
    <div class="stats-inner">
        <div class="stat-item">
            <div class="stat-item-value">{{ \App\Models\Book::count() }}</div>
            <div class="stat-item-label">Books in Collection</div>
        </div>
        <div class="stat-item">
            <div class="stat-item-value">{{ \App\Models\Book::where('type', 'digital')->count() }}</div>
            <div class="stat-item-label">Digital Books</div>
        </div>
        <div class="stat-item">
            <div class="stat-item-value">{{ \App\Models\Book::where('status', 'available')->count() }}</div>
            <div class="stat-item-label">Available Now</div>
        </div>
    </div>
</div>

{{-- ── FEATURES ── --}}
<section class="features">
    <div class="section-header">
        <p class="section-eyebrow">What We Offer</p>
        <h2 class="section-title">Everything You Need in One Library</h2>
        <p class="section-sub">From physical books to digital resources, we make learning accessible for every student and faculty member.</p>
    </div>

    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
            </div>
            <h3 class="feature-title">Physical Collection</h3>
            <p class="feature-desc">Browse and borrow from our curated collection of physical books. Easy checkout with your school ID or RFID card.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                </svg>
            </div>
            <h3 class="feature-title">Digital Reading</h3>
            <p class="feature-desc">Access digital books and PDFs directly from your browser. No downloads required — read anywhere, anytime.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
            </div>
            <h3 class="feature-title">Borrow Tracking</h3>
            <p class="feature-desc">Track your borrowed books, due dates, and reading history all from your personal dashboard.</p>
        </div>
    </div>
</section>

{{-- ── FEATURED BOOKS ── --}}
<section class="books-section" id="books">
    <div class="section-header">
        <p class="section-eyebrow">Our Collection</p>
        <h2 class="section-title">Featured Books</h2>
        <p class="section-sub">A glimpse into our growing library collection available for students and faculty.</p>
    </div>

    <div class="books-grid">
        @php
            $featuredBooks = \App\Models\Book::with('digitalBook')
                ->where('status', 'available')
                ->latest()
                ->take(8)
                ->get();
        @endphp

        @forelse($featuredBooks as $book)
        <div class="book-card">
            <div class="book-cover">
                @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}">
                @else
                    <div class="book-cover-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                        </svg>
                        <span>{{ Str::limit($book->title, 30) }}</span>
                    </div>
                @endif
                @if($book->type === 'digital' || $book->digitalBook)
                    <span class="book-badge">Digital</span>
                @endif
            </div>
            <div class="book-info">
                <div class="book-title">{{ $book->title }}</div>
                <div class="book-author">{{ $book->author }}</div>
                @if($book->category)
                    <span class="book-category">{{ $book->category }}</span>
                @endif
            </div>
        </div>
        @empty
        <div style="grid-column: 1/-1; text-align:center; padding: 3rem; color: var(--text-muted);">
            <p style="font-family: var(--font-serif); font-size:1.1rem; color:var(--navy); margin-bottom:0.5rem;">No books available yet</p>
            <p style="font-size:0.85rem;">The collection will appear here once books are added.</p>
        </div>
        @endforelse
    </div>

    <div class="books-footer">
        <a href="{{ route('login') }}" class="btn-browse">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            Browse Full Collection
        </a>
    </div>
</section>

{{-- ── CTA ── --}}
<section class="cta-banner">
    <div class="cta-content">
        <h2 class="cta-title">Ready to Start Reading?</h2>
        <p class="cta-sub">Sign in with your school account to borrow books and access our full digital collection.</p>
        <a href="{{ route('login') }}" class="btn-hero-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
            </svg>
            Sign In to the Library
        </a>
    </div>
</section>

{{-- ── FOOTER ── --}}
<footer>
    <div class="footer-brand">
        <strong>BES Digital Library</strong> — Empowering learners through knowledge
    </div>
    <div class="footer-copy">
        &copy; {{ date('Y') }} BES Digital Library. All rights reserved.
    </div>
</footer>

</body>
</html>