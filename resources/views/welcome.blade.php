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
            background: rgba(9, 21, 37, 0.96);
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
            flex-shrink: 0;
        }

        .nav-logo-placeholder {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            display: flex; align-items: center; justify-content: center;
            font-family: var(--font-serif);
            font-size: 1rem; font-weight: 700;
            color: var(--navy-deep);
            flex-shrink: 0;
        }

        .nav-brand-text {
            font-family: var(--font-serif);
            font-size: 1.1rem; font-weight: 700;
            color: #fff; letter-spacing: 0.02em;
        }

        .nav-brand-text span { color: var(--gold-light); }

        .nav-links {
            display: flex; align-items: center; gap: 0.125rem;
        }

        .nav-links a {
            padding: 0.45rem 0.875rem;
            text-decoration: none;
            color: rgba(255,255,255,0.65);
            font-size: 0.815rem; font-weight: 500;
            border-radius: 4px;
            transition: all 0.18s;
            white-space: nowrap;
        }

        .nav-links a:hover { color: #fff; background: rgba(255,255,255,0.07); }
        .nav-links a.active { color: var(--gold-light); }

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
            flex-shrink: 0;
        }

        .nav-login:hover { background: var(--gold); color: var(--navy-deep); }

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

        .hero::before {
            content: '';
            position: absolute; inset: 0;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(37,99,176,0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(184,146,42,0.1) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(26,58,107,0.3) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero::after {
            content: '';
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
        }

        .hero-content {
            position: relative; z-index: 1;
            max-width: 780px;
        }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.35rem 1rem;
            background: rgba(184,146,42,0.12);
            border: 1px solid rgba(184,146,42,0.3);
            border-radius: 999px;
            font-size: 0.72rem; font-weight: 600;
            color: var(--gold-light);
            letter-spacing: 0.12em; text-transform: uppercase;
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
            font-weight: 700; color: #ffffff;
            line-height: 1.15; margin-bottom: 1.25rem;
            animation: fadeUp 0.6s ease 0.1s both;
        }

        .hero-title em { font-style: italic; color: var(--gold-light); }

        .hero-subtitle {
            font-size: 1.05rem;
            color: rgba(168,188,212,0.85);
            max-width: 560px; margin: 0 auto 2.5rem;
            font-weight: 300; line-height: 1.8;
            animation: fadeUp 0.6s ease 0.2s both;
        }

        .hero-actions {
            display: flex; gap: 1rem; justify-content: center;
            flex-wrap: wrap;
            animation: fadeUp 0.6s ease 0.3s both;
        }

        .btn-hero-primary {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.875rem 2rem;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: var(--navy-deep);
            font-size: 0.875rem; font-weight: 600;
            font-family: var(--font-sans);
            border-radius: 4px; text-decoration: none;
            transition: all 0.2s; letter-spacing: 0.03em;
        }

        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(184,146,42,0.4);
            color: var(--navy-deep);
        }

        .btn-hero-secondary {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.875rem 2rem;
            background: transparent; color: rgba(255,255,255,0.8);
            font-size: 0.875rem; font-weight: 500;
            font-family: var(--font-sans);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 4px; text-decoration: none; transition: all 0.2s;
        }

        .btn-hero-secondary:hover {
            background: rgba(255,255,255,0.08);
            color: #fff; border-color: rgba(255,255,255,0.4);
        }

        .hero-divider {
            width: 48px; height: 2px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light));
            border-radius: 2px; margin: 3rem auto 0;
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
            max-width: 900px; margin: 0 auto;
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 1rem; text-align: center;
        }

        .stat-item { padding: 0.5rem; }

        .stat-item-value {
            font-family: var(--font-serif);
            font-size: 2rem; font-weight: 700;
            color: var(--gold-light); line-height: 1; margin-bottom: 0.35rem;
        }

        .stat-item-label {
            font-size: 0.72rem; font-weight: 600;
            color: rgba(168,188,212,0.6);
            text-transform: uppercase; letter-spacing: 0.1em;
        }

        /* ── SHARED SECTION STYLES ── */
        .section-header {
            text-align: center; max-width: 600px;
            margin: 0 auto 3.5rem;
        }

        .section-eyebrow {
            font-size: 0.72rem; font-weight: 600;
            color: var(--gold); text-transform: uppercase;
            letter-spacing: 0.14em; margin-bottom: 0.75rem;
        }

        .section-title {
            font-family: var(--font-serif);
            font-size: clamp(1.6rem, 3vw, 2.25rem);
            font-weight: 700; color: var(--navy);
            line-height: 1.25; margin-bottom: 0.75rem;
        }

        .section-sub { font-size: 0.9rem; color: var(--text-muted); line-height: 1.8; }

        /* ── FEATURES ── */
        .features { padding: 5rem 2.5rem; background: var(--bg); }

        .features-grid {
            max-width: 960px; margin: 0 auto;
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;
        }

        .feature-card {
            background: var(--white); border: 1px solid var(--border);
            border-radius: 10px; padding: 2rem 1.75rem;
            transition: all 0.25s; position: relative; overflow: hidden;
        }

        .feature-card::before {
            content: ''; position: absolute;
            top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--navy-mid), var(--blue));
            transform: scaleX(0); transform-origin: left;
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            border-color: var(--blue-pale);
            box-shadow: 0 8px 24px rgba(13,27,46,0.1);
            transform: translateY(-3px);
        }

        .feature-card:hover::before { transform: scaleX(1); }

        .feature-icon {
            width: 44px; height: 44px; background: var(--blue-pale);
            border-radius: 8px; display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.25rem; color: var(--blue);
        }

        .feature-icon svg { width: 20px; height: 20px; }

        .feature-title {
            font-family: var(--font-serif); font-size: 1.05rem; font-weight: 600;
            color: var(--navy); margin-bottom: 0.6rem;
        }

        .feature-desc { font-size: 0.845rem; color: var(--text-muted); line-height: 1.7; }

        /* ── BOOKS ── */
        .books-section {
            padding: 5rem 2.5rem; background: var(--white);
            border-top: 1px solid var(--border);
        }

        .books-grid {
            max-width: 960px; margin: 0 auto;
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem;
        }

        .book-card {
            background: var(--bg); border: 1px solid var(--border);
            border-radius: 8px; overflow: hidden; transition: all 0.22s; text-decoration: none;
        }

        .book-card:hover {
            border-color: var(--blue-pale);
            box-shadow: 0 6px 18px rgba(13,27,46,0.1);
            transform: translateY(-3px);
        }

        .book-cover {
            width: 100%; aspect-ratio: 3/4;
            background: linear-gradient(135deg, var(--navy-mid), var(--navy));
            display: flex; align-items: center; justify-content: center;
            position: relative; overflow: hidden;
        }

        .book-cover img { width: 100%; height: 100%; object-fit: cover; }

        .book-cover-placeholder {
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 0.5rem; color: rgba(255,255,255,0.4);
            padding: 1rem; text-align: center;
        }

        .book-cover-placeholder svg { width: 32px; height: 32px; opacity: 0.5; }
        .book-cover-placeholder span { font-family: var(--font-serif); font-size: 0.75rem; color: rgba(255,255,255,0.6); line-height: 1.3; }

        .book-badge {
            position: absolute; top: 0.6rem; right: 0.6rem;
            padding: 0.2rem 0.5rem;
            background: rgba(184,146,42,0.9); color: var(--navy-deep);
            font-size: 0.62rem; font-weight: 700;
            border-radius: 3px; text-transform: uppercase; letter-spacing: 0.06em;
        }

        .book-info { padding: 0.875rem 1rem; }

        .book-title {
            font-family: var(--font-serif); font-size: 0.875rem; font-weight: 600;
            color: var(--navy); margin-bottom: 0.25rem;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .book-author {
            font-size: 0.75rem; color: var(--text-muted);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .book-category {
            display: inline-block; margin-top: 0.5rem;
            padding: 0.15rem 0.5rem; background: var(--blue-pale); color: var(--blue);
            font-size: 0.65rem; font-weight: 600;
            border-radius: 3px; text-transform: uppercase; letter-spacing: 0.06em;
        }

        .books-footer { text-align: center; margin-top: 2.5rem; }

        .btn-browse {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.75rem 2rem; background: var(--navy-mid); color: #fff;
            font-size: 0.855rem; font-weight: 500; font-family: var(--font-sans);
            border-radius: 4px; text-decoration: none; transition: all 0.2s;
        }

        .btn-browse:hover {
            background: var(--navy-light); transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(13,27,46,0.25); color: #fff;
        }

        /* ── ABOUT ── */
        .about-section {
            padding: 5rem 2.5rem; background: var(--bg);
            border-top: 1px solid var(--border);
        }

        .about-inner {
            max-width: 960px; margin: 0 auto;
        }

        .about-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .about-card {
            background: var(--white); border: 1px solid var(--border);
            border-radius: 10px; padding: 1.75rem 2rem;
            transition: all 0.22s; position: relative; overflow: hidden;
        }

        .about-card::before {
            content: ''; position: absolute;
            top: 0; left: 0; width: 3px; height: 100%;
            background: linear-gradient(180deg, var(--gold), var(--gold-light));
        }

        .about-card:hover {
            box-shadow: 0 6px 18px rgba(13,27,46,0.08);
            transform: translateY(-2px);
        }

        .about-card.full { grid-column: 1 / -1; }

        .about-card h3 {
            font-family: var(--font-serif); font-size: 1.05rem; font-weight: 600;
            color: var(--navy); margin-bottom: 0.75rem;
        }

        .about-card p {
            font-size: 0.875rem; line-height: 1.8; color: var(--text-muted);
        }

        .about-card p + p { margin-top: 0.75rem; }

        .about-card b { color: var(--navy); }

        .about-card ul {
            padding-left: 1.25rem; font-size: 0.875rem;
            line-height: 1.8; color: var(--text-muted);
        }

        .about-card ul li { margin-bottom: 0.3rem; }

        .vm-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem; }

        .vm-card {
            background: var(--navy); border-radius: 10px; padding: 1.75rem;
            position: relative; overflow: hidden;
        }

        .vm-card::before {
            content: ''; position: absolute;
            top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light));
        }

        .vm-card h3 {
            font-family: var(--font-serif); font-size: 0.7rem; font-weight: 600;
            color: var(--gold); text-transform: uppercase; letter-spacing: 0.12em;
            margin-bottom: 0.625rem;
        }

        .vm-card p { font-size: 0.9rem; color: rgba(200,220,245,0.85); line-height: 1.75; }

        /* ── SERVICES ── */
        .services-section {
            padding: 5rem 2.5rem; background: var(--white);
            border-top: 1px solid var(--border);
        }

        .services-inner { max-width: 760px; margin: 0 auto; }

        .accordion { display: flex; flex-direction: column; gap: 0.75rem; }

        .accordion-item {
            background: var(--bg); border: 1px solid var(--border);
            border-radius: 8px; overflow: hidden;
            transition: box-shadow 0.2s;
        }

        .accordion-item:hover { box-shadow: 0 4px 14px rgba(13,27,46,0.08); }

        .accordion-header {
            padding: 1.125rem 1.5rem;
            font-family: var(--font-serif);
            font-size: 0.975rem; font-weight: 600; color: var(--navy);
            cursor: pointer; display: flex; align-items: center; justify-content: space-between;
            gap: 1rem; transition: all 0.18s; user-select: none;
            border-left: 3px solid transparent;
        }

        .accordion-header:hover { background: var(--white); }

        .accordion-header.active {
            background: var(--navy); color: #fff;
            border-left-color: var(--gold);
        }

        .accordion-chevron {
            width: 16px; height: 16px; flex-shrink: 0;
            transition: transform 0.3s; color: var(--gold);
        }

        .accordion-header.active .accordion-chevron { transform: rotate(180deg); }

        .accordion-content {
            max-height: 0; overflow: hidden;
            transition: max-height 0.35s ease, padding 0.3s;
            padding: 0 1.5rem;
            border-left: 3px solid var(--border);
        }

        .accordion-content.open {
            max-height: 400px; padding: 1.25rem 1.5rem;
            border-left-color: var(--gold-border);
        }

        .accordion-content p {
            font-size: 0.875rem; line-height: 1.8; color: var(--text-muted);
        }

        .accordion-content strong { color: var(--navy); }

        /* ── CTA ── */
        .cta-banner {
            background: linear-gradient(135deg, var(--navy-deep) 0%, var(--navy-mid) 100%);
            padding: 5rem 2.5rem; text-align: center;
            position: relative; overflow: hidden;
        }

        .cta-banner::before {
            content: ''; position: absolute; inset: 0;
            background-image:
                radial-gradient(circle at 0% 100%, rgba(184,146,42,0.1) 0%, transparent 50%),
                radial-gradient(circle at 100% 0%, rgba(37,99,176,0.15) 0%, transparent 50%);
            pointer-events: none;
        }

        .cta-content {
            position: relative; z-index: 1;
            max-width: 600px; margin: 0 auto;
        }

        .cta-title {
            font-family: var(--font-serif);
            font-size: clamp(1.75rem, 3.5vw, 2.5rem);
            font-weight: 700; color: #fff; margin-bottom: 1rem; line-height: 1.2;
        }

        .cta-sub {
            font-size: 0.95rem; color: rgba(168,188,212,0.8);
            margin-bottom: 2rem; font-weight: 300;
        }

        /* ── FOOTER ── */
        footer {
            background: var(--navy-deep);
            border-top: 1px solid rgba(184,146,42,0.15);
            padding: 2rem 2.5rem;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 1rem;
        }

        .footer-brand { font-family: var(--font-serif); font-size: 0.9rem; color: rgba(168,188,212,0.7); }
        .footer-brand strong { color: var(--gold-light); }
        .footer-copy { font-size: 0.75rem; color: rgba(168,188,212,0.4); }

        /* ── ANIMATIONS ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .about-grid { grid-template-columns: 1fr; }
            .about-card.full { grid-column: 1; }
        }

        @media (max-width: 768px) {
            nav { padding: 0 1.25rem; }
            .nav-links { display: none; }
            .features-grid { grid-template-columns: 1fr; }
            .books-grid { grid-template-columns: repeat(2, 1fr); }
            .stats-inner { grid-template-columns: 1fr; gap: 1.5rem; }
            .features, .books-section, .about-section, .services-section, .cta-banner { padding: 3.5rem 1.25rem; }
            .vm-grid { grid-template-columns: 1fr; }
            .about-grid { grid-template-columns: 1fr; }
            footer { flex-direction: column; text-align: center; }
        }

        @media (max-width: 480px) {
            .books-grid { grid-template-columns: repeat(2, 1fr); }
            .hero-actions { flex-direction: column; align-items: center; }
        }

        /* ── BOOK MODAL ── */
        .book-card { cursor: pointer; }

        .modal-backdrop {
            display: none; position: fixed; inset: 0;
            background: rgba(9,21,37,0.65);
            backdrop-filter: blur(4px);
            z-index: 200;
            align-items: center; justify-content: center;
            padding: 1.5rem;
        }

        .modal-backdrop.open { display: flex; }

        .modal-box {
            background: var(--white);
            border-radius: 10px; overflow: hidden;
            width: 100%; max-width: 760px;
            box-shadow: 0 24px 64px rgba(9,21,37,0.35);
            animation: fadeUp 0.3s ease;
            max-height: 90vh; overflow-y: auto;
        }

        .modal-head {
            background: var(--navy);
            padding: 1.125rem 1.5rem;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0;
            border-bottom: 2px solid var(--gold);
        }

        .modal-head h2 {
            font-family: var(--font-serif);
            font-size: 1rem; color: #fff; font-weight: 600;
        }

        .modal-close {
            background: rgba(255,255,255,0.1); border: none;
            color: #fff; width: 28px; height: 28px;
            border-radius: 4px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; transition: background 0.18s;
        }

        .modal-close:hover { background: rgba(255,255,255,0.2); }

        .modal-body {
            padding: 1.75rem;
            display: grid;
            grid-template-columns: 180px 1fr;
            gap: 1.75rem;
        }

        .modal-cover-img {
            width: 100%; aspect-ratio: 3/4;
            object-fit: cover; border-radius: 6px;
            box-shadow: 0 4px 14px rgba(13,27,46,0.15);
        }

        .modal-cover-ph {
            width: 100%; aspect-ratio: 3/4;
            background: linear-gradient(135deg, var(--navy-mid), var(--navy));
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
        }

        .modal-cover-ph svg { width: 36px; height: 36px; color: rgba(255,255,255,0.25); }

        .modal-details h3 {
            font-family: var(--font-serif);
            font-size: 1.3rem; font-weight: 700;
            color: var(--navy); margin-bottom: 1rem; line-height: 1.3;
        }

        .modal-info { display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 1.25rem; }

        .modal-info-row { display: flex; gap: 0.5rem; font-size: 0.845rem; }
        .modal-info-label { font-weight: 600; color: var(--navy); min-width: 80px; flex-shrink: 0; }
        .modal-info-value { color: var(--text-muted); }

        .modal-desc {
            background: var(--bg);
            border-left: 3px solid var(--gold-border);
            border-radius: 0 6px 6px 0;
            padding: 0.875rem 1rem;
            font-size: 0.875rem; line-height: 1.75;
            color: var(--text-muted); margin-bottom: 1.25rem;
        }

        .modal-login-btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.65rem 1.5rem;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: var(--navy-deep);
            border-radius: 4px; text-decoration: none;
            font-size: 0.855rem; font-weight: 600;
            font-family: var(--font-sans);
            transition: all 0.2s; width: 100%; justify-content: center;
        }

        .modal-login-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(184,146,42,0.35);
            color: var(--navy-deep);
        }

        @media (max-width: 600px) {
            .modal-body { grid-template-columns: 1fr; }
            .modal-cover-img, .modal-cover-ph { max-width: 160px; margin: 0 auto; }
        }
    </style>
</head>
<body>

{{-- ── NAVIGATION ── --}}
<nav>
    <a href="{{ url('/') }}" class="nav-brand">
        {{-- <img src="{{ asset('images/logo.png') }}" alt="BES Logo" style="width:36px;height:36px;border-radius:50%;object-fit:cover;"> --}}
        <div class="nav-logo-placeholder">B</div>
        <span class="nav-brand-text">BES <span>Digital Library</span></span>
    </a>

    <div class="nav-links">
        <a href="#hero">Home</a>
        <a href="#books">Resources</a>
        <a href="#about">About</a>
        <a href="#services">Services</a>
    </div>

    <a href="{{ route('login') }}" class="nav-login">
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
        </svg>
        Sign In
    </a>
</nav>

{{-- ── HERO ── --}}
<section class="hero" id="hero">
    <div class="hero-content">
        <div class="hero-badge">BES Digital Library</div>
        <h1 class="hero-title">
            Knowledge within<br><em>Reach</em>
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
            <div class="stat-item-value">{{ \App\Models\BookCopy::where('status', 'available')->count() }}</div>
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
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            </div>
            <h3 class="feature-title">Physical Collection</h3>
            <p class="feature-desc">Browse and borrow from our curated collection of physical books. Easy checkout with your school ID or RFID card.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            </div>
            <h3 class="feature-title">Digital Reading</h3>
            <p class="feature-desc">Access digital books and PDFs directly from your browser. No downloads required — read anywhere, anytime.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
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
                ->whereHas('copies', fn($q) => $q->where('status', 'available'))
                ->latest()->take(8)->get();
        @endphp
        @forelse($featuredBooks as $book)
        <div class="book-card" onclick="openBookModal(
            '{{ addslashes($book->title) }}',
            '{{ addslashes($book->author) }}',
            '{{ addslashes($book->category ?? '') }}',
            '{{ $book->type }}',
            '{{ $book->cover_image ? asset('storage/'.$book->cover_image) : '' }}',
            '{{ addslashes($book->description ?? '') }}'
        )">
            <div class="book-cover">
                @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}">
                @else
                    <div class="book-cover-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
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
        <div style="grid-column:1/-1;text-align:center;padding:3rem;color:var(--text-muted);">
            <p style="font-family:var(--font-serif);font-size:1.1rem;color:var(--navy);margin-bottom:0.5rem;">No books available yet</p>
            <p style="font-size:0.85rem;">The collection will appear here once books are added.</p>
        </div>
        @endforelse
    </div>
    <div class="books-footer">
        <a href="{{ route('login') }}" class="btn-browse">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            Browse Full Collection
        </a>
    </div>
</section>

{{-- ── ABOUT ── --}}
<section class="about-section" id="about">
    <div class="about-inner">
        <div class="section-header">
            <p class="section-eyebrow">Who We Are</p>
            <h2 class="section-title">About the BES Library</h2>
            <p class="section-sub">Dedicated to providing resources and learning spaces that support the academic and personal growth of every student and educator.</p>
        </div>

        <div class="about-grid">
            <div class="about-card full">
                <h3>Philosophical Mandate</h3>
                <p>BULACAN ECUMENICAL SCHOOL focuses its educational mandate on the <b>HOLISTIC TRANSFORMATION of STUDENTS</b>. This program is integrated with the educational system prescribed by the Department of Education. We believe that every human being, created by God with unique potentials, deserves the chance to harness and develop these potentials to serve their God-given purpose through a system of <b>Transformative Education</b>.</p>
                <p>These potentials manifest through <b>Multiple Intelligences</b> and must be nurtured across the <b>Cognitive, Affective, Physical, Social, and Spiritual domains</b> in alignment with Christian values.</p>
            </div>
        </div>

        <div class="vm-grid">
            <div class="vm-card">
                <h3>Vision</h3>
                <p>Home of God-fearing achievers, Agent of values transformation.</p>
            </div>
            <div class="vm-card">
                <h3>Mission</h3>
                <p>BES is committed to provide Quality Education upholding Christian Values.</p>
            </div>
        </div>

        <div class="about-grid">
            <div class="about-card">
                <h3>Core Values</h3>
                <ul>
                    <li>Spiritual Discipline</li>
                    <li>Valuing People</li>
                    <li>Stewardship</li>
                    <li>Integrity</li>
                </ul>
            </div>
            <div class="about-card">
                <h3>Goals</h3>
                <p>Maintain an institution that supports the development of character, positive values, faith in God, and facilitates every opportunity to enhance knowledge, skills, and the use of technology for social and global readiness.</p>
            </div>
        </div>
    </div>
</section>

{{-- ── SERVICES ── --}}
<section class="services-section" id="services">
    <div class="section-header">
        <p class="section-eyebrow">What We Offer</p>
        <h2 class="section-title">Library Services</h2>
        <p class="section-sub">Essential services to help students, faculty, and staff make the most of library resources.</p>
    </div>

    <div class="services-inner">
        <div class="accordion">

            <div class="accordion-item">
                <div class="accordion-header">
                    Library Hours &amp; Schedule
                    <svg class="accordion-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div class="accordion-content">
                    <p>Our library is open from <strong>8:00 AM to 5:00 PM</strong>, Monday through Friday. Extended hours may apply during examinations.</p>
                </div>
            </div>

            <div class="accordion-item">
                <div class="accordion-header">
                    How to Borrow / Return Books
                    <svg class="accordion-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div class="accordion-content">
                    <p>Students can borrow up to <strong>3 books</strong> for <strong>7 days</strong>. Please return or renew books on time to avoid penalties.</p>
                </div>
            </div>

            <div class="accordion-item">
                <div class="accordion-header">
                    How to Have an Account
                    <svg class="accordion-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div class="accordion-content">
                    <p>All enrolled BES students automatically have a library account. Just present your student ID to borrow materials. Faculty accounts are created by the administrator.</p>
                </div>
            </div>

            <div class="accordion-item">
                <div class="accordion-header">
                    Digital Library Access
                    <svg class="accordion-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div class="accordion-content">
                    <p>Access our digital collection anytime at <strong>bes-library.com</strong>. Sign in with the credentials provided by your administrator.</p>
                </div>
            </div>

            <div class="accordion-item">
                <div class="accordion-header">
                    Ask a Librarian
                    <svg class="accordion-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div class="accordion-content">
                    <p>Need help finding resources? Visit us in person during library hours or email us at <strong><a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="1e6c7f73776c7b645e7c7b6d3372777c6c7f6c67307d7173">[email&#160;protected]</a></strong>.</p>
                </div>
            </div>

        </div>
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

<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>{{-- ── BOOK MODAL ── --}}
<div class="modal-backdrop" id="bookModal">
    <div class="modal-box">
        <div class="modal-head">
            <h2>Book Details</h2>
            <button class="modal-close" onclick="closeBookModal()">&times;</button>
        </div>
        <div class="modal-body" id="modalBody"></div>
    </div>
</div>

<script>
    function openBookModal(title, author, category, type, coverUrl, description) {
        const coverHtml = coverUrl
            ? `<img src="${coverUrl}" alt="${title}" class="modal-cover-img">`
            : `<div class="modal-cover-ph"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></div>`;

        document.getElementById('modalBody').innerHTML = `
            <div>${coverHtml}</div>
            <div class="modal-details">
                <h3>${title}</h3>
                <div class="modal-info">
                    <div class="modal-info-row">
                        <span class="modal-info-label">Author</span>
                        <span class="modal-info-value">${author || '—'}</span>
                    </div>
                    <div class="modal-info-row">
                        <span class="modal-info-label">Category</span>
                        <span class="modal-info-value">${category || '—'}</span>
                    </div>
                    <div class="modal-info-row">
                        <span class="modal-info-label">Type</span>
                        <span class="modal-info-value">${type.charAt(0).toUpperCase() + type.slice(1)}</span>
                    </div>
                </div>
                ${description ? `<div class="modal-desc">${description}</div>` : ''}
                <a href="{{ route('login') }}" class="modal-login-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Sign in to Borrow
                </a>
            </div>
        `;

        document.getElementById('bookModal').classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeBookModal() {
        document.getElementById('bookModal').classList.remove('open');
        document.body.style.overflow = '';
    }

    document.getElementById('bookModal').addEventListener('click', function(e) {
        if (e.target === this) closeBookModal();
    });

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeBookModal(); });

    document.querySelectorAll('.accordion-header').forEach(header => {
        header.addEventListener('click', () => {
            const content = header.nextElementSibling;
            const isOpen = content.classList.contains('open');
            document.querySelectorAll('.accordion-content').forEach(c => c.classList.remove('open'));
            document.querySelectorAll('.accordion-header').forEach(h => h.classList.remove('active'));
            if (!isOpen) {
                content.classList.add('open');
                header.classList.add('active');
            }
        });
    });

    // Active nav link on scroll
    const sections = document.querySelectorAll('section[id], div[id]');
    const navLinks = document.querySelectorAll('.nav-links a');

    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            if (window.scrollY >= section.offsetTop - 80) {
                current = section.getAttribute('id');
            }
        });
        navLinks .forEach(link => {
            link.classList.toggle('active', link.getAttribute('href') === `#${current}`);
        });
    });
</script>
</body>
</html>