<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — BES Digital Library</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background: var(--sidebar-bg-deep); margin: 0; }

        .login-page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* ── LEFT: Full image panel ── */
        .login-left {
            position: relative;
            overflow: hidden;
            background: var(--sidebar-bg-deep);
        }

        .login-left-img {
            position: absolute; inset: 0;
            width: 100%; height: 100%;
            object-fit: cover;
            object-position: center;
            opacity: 0.35;
        }

        /* Dark gradient overlay — stronger at edges, lighter in center */
        .login-left-overlay {
            position: absolute; inset: 0;
            background:
                linear-gradient(to right, rgba(9,21,37,0.6) 0%, rgba(9,21,37,0.2) 100%),
                linear-gradient(to bottom, rgba(9,21,37,0.4) 0%, rgba(9,21,37,0.4) 100%);
        }

        .login-left-content {
            position: relative; z-index: 1;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem;
        }

        .login-left-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            align-self: flex-start;
        }

        .login-left-badge-line {
            width: 28px; height: 2px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light));
            border-radius: 2px;
            flex-shrink: 0;
        }

        .login-left-badge-text {
            font-size: 0.68rem; font-weight: 600;
            color: var(--gold-light);
            text-transform: uppercase; letter-spacing: 0.14em;
        }

        .login-left-center {
            text-align: center;
        }

        .login-left-school {
            font-family: var(--font-serif);
            font-size: clamp(1.5rem, 2.5vw, 2.25rem);
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 0.75rem;
            text-shadow: 0 2px 12px rgba(0,0,0,0.5);
        }

        .login-left-sub {
            font-size: 0.8rem;
            color: rgba(168,188,212,0.85);
            font-style: italic;
            text-shadow: 0 1px 6px rgba(0,0,0,0.4);
        }

        .login-left-footer {
            font-size: 0.68rem;
            color: rgba(168,188,212,0.45);
            letter-spacing: 0.08em;
            text-align: center;
        }

        /* ── RIGHT: Clean form panel ── */
        .login-right {
            background: #f4f6f9;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3.5rem 3rem;
            border-left: 1px solid rgba(184,146,42,0.15);
        }

        .login-form-wrap {
            width: 100%;
            max-width: 360px;
        }

        /* Header */
        .login-form-eyebrow {
            font-size: 0.68rem; font-weight: 600;
            color: var(--gold);
            text-transform: uppercase; letter-spacing: 0.14em;
            margin-bottom: 0.5rem;
        }

        .login-form-title {
            font-family: var(--font-serif);
            font-size: 1.875rem; font-weight: 700;
            color: var(--text-head);
            line-height: 1.2; margin-bottom: 0.5rem;
        }

        .login-form-sub {
            font-size: 0.835rem;
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 2rem;
        }

        /* Card */
        .login-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-top: 3px solid var(--gold);
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow-md);
        }

        .login-card .form-group { margin-bottom: 1.25rem; }
        .login-card .form-group:last-of-type { margin-bottom: 1.5rem; }

        .login-submit {
            width: 100%;
            padding: 0.7rem;
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.03em;
        }

        .login-forgot {
            display: block;
            text-align: center;
            margin-top: 1rem;
            font-size: 0.775rem;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.18s;
        }

        .login-forgot:hover { color: var(--blue-bright); }

        /* Divider */
        .login-divider {
            display: flex; align-items: center; gap: 0.75rem;
            margin: 1.25rem 0;
        }

        .login-divider-line { flex: 1; height: 1px; background: var(--border); }

        .login-divider-text {
            font-size: 0.7rem;
            color: var(--text-dim);
            white-space: nowrap;
        }

        /* Back to home */
        .login-back {
            display: block;
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-muted);
            text-decoration: none;
            padding: 0.55rem;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            background: var(--surface);
            transition: all 0.18s;
        }

        .login-back:hover {
            color: var(--text-head);
            border-color: var(--border-strong);
            background: var(--surface-2);
        }

        /* Footer note */
        .login-footer-note {
            text-align: center;
            font-size: 0.72rem;
            color: var(--text-dim);
            margin-top: 1.25rem;
            line-height: 1.6;
        }

        /* ── MOBILE ── */
        @media (max-width: 768px) {
            .login-page { grid-template-columns: 1fr; }
            .login-left { display: none; }
            .login-right {
                background: var(--sidebar-bg);
                border-left: none;
                padding: 2rem 1.5rem;
            }
            .login-card {
                background: rgba(255,255,255,0.05);
                border-color: var(--sidebar-border);
                border-top-color: var(--gold);
            }
            .login-form-title { color: #fff; }
            .login-form-sub { color: var(--sidebar-text); }
            .login-form-eyebrow { color: var(--gold-light); }
            .form-label { color: var(--sidebar-text); }
            .form-control {
                background: var(--sidebar-bg-deep);
                border-color: var(--sidebar-border);
                color: #fff;
            }
            .login-forgot { color: var(--sidebar-text-dim); }
            .login-back {
                background: rgba(255,255,255,0.05);
                border-color: var(--sidebar-border);
                color: var(--sidebar-text);
            }
            .login-footer-note { color: var(--sidebar-text-dim); }
            .login-divider-line { background: var(--sidebar-border); }
        }
    </style>
</head>
<body>

<div class="login-page">

    {{-- ── LEFT: Logo image panel ── --}}
    <div class="login-left">
        <img src="{{ asset('images/BES_logo.jfif') }}" alt="BES Logo" class="login-left-img">
        <div class="login-left-overlay"></div>
        <div class="login-left-content">
            <div class="login-left-badge">
                <div class="login-left-badge-line"></div>
                <span class="login-left-badge-text">BES Digital Library</span>
            </div>
            <div class="login-left-center">
                <div class="login-left-school">Bulacan Ecumenical School</div>
                <p class="login-left-sub">Home of God-fearing Achievers</p>
            </div>
            <div class="login-left-footer">Library Management System &mdash; {{ date('Y') }}</div>
        </div>
    </div>

    {{-- ── RIGHT: Form panel ── --}}
    <div class="login-right">
        <div class="login-form-wrap">

            <p class="login-form-eyebrow">Welcome back</p>
            <h1 class="login-form-title">Sign in to your<br>account</h1>
            <p class="login-form-sub">Enter your credentials to access the library system.</p>

            @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:1.25rem;">{{ session('success') }}</div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger" style="margin-bottom:1.25rem;">{{ session('error') }}</div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom:1.25rem;">
                @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
                @endforeach
            </div>
            @endif

            <div class="login-card">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email') }}"
                            placeholder="you@example.com"
                            autocomplete="email"
                            required
                            autofocus
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary login-submit">
                        Sign In
                    </button>

                    <a href="{{ route('password.request') }}" class="login-forgot">
                        Forgot your password?
                    </a>
                </form>
            </div>

            <div class="login-divider">
                <div class="login-divider-line"></div>
                <span class="login-divider-text">or</span>
                <div class="login-divider-line"></div>
            </div>

            <a href="{{ url('/') }}" class="login-back">Back to Home</a>

            <p class="login-footer-note">
                Contact your administrator if you cannot access your account.
            </p>

        </div>
    </div>

</div>

</body>
</html>