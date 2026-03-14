<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password — Digital Library</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background: var(--sidebar-bg); }

        .login-page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .login-left {
            background: var(--sidebar-bg-deep);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: -80px; left: -80px;
            width: 320px; height: 320px;
            border-radius: 50%;
            background: rgba(30,77,140,0.15);
            pointer-events: none;
        }

        .login-left::after {
            content: '';
            position: absolute;
            bottom: -60px; right: -60px;
            width: 240px; height: 240px;
            border-radius: 50%;
            background: rgba(184,146,42,0.06);
            pointer-events: none;
        }

        .login-brand-line {
            width: 40px; height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light));
            border-radius: 2px;
            margin-bottom: 1.5rem;
        }

        .login-brand-name {
            font-family: var(--font-serif);
            font-size: 2.25rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.2;
            margin-bottom: 0.75rem;
        }

        .login-brand-tagline {
            font-size: 0.875rem;
            color: var(--sidebar-text);
            line-height: 1.6;
            max-width: 300px;
        }

        .login-brand-divider {
            width: 1px; height: 40px;
            background: var(--sidebar-border);
            margin: 2rem 0;
        }

        .login-brand-meta {
            font-size: 0.75rem;
            color: var(--sidebar-text-dim);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .login-right {
            background: #f8f9fb;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 4rem 3rem;
        }

        .login-form-wrap {
            width: 100%;
            max-width: 380px;
        }

        .login-form-title {
            font-family: var(--font-serif);
            font-size: 1.6rem;
            font-weight: 600;
            color: var(--text-head);
            margin-bottom: 0.35rem;
        }

        .login-form-sub {
            font-size: 0.835rem;
            color: var(--text-muted);
            margin-bottom: 2rem;
        }

        .login-box {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow-md);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.8rem;
            color: var(--text-muted);
            text-decoration: none;
            margin-top: 1.25rem;
            transition: color 0.18s;
        }

        .back-link:hover { color: var(--blue-bright); }
        .back-link svg { width: 14px; height: 14px; }

        @media (max-width: 768px) {
            .login-page { grid-template-columns: 1fr; }
            .login-left { display: none; }
            .login-right { background: var(--sidebar-bg); }
            .login-box { background: var(--sidebar-active); border-color: var(--sidebar-border); }
            .login-form-title { color: #fff; }
            .login-form-sub { color: var(--sidebar-text); }
            .form-label { color: var(--sidebar-text); }
            .form-control { background: var(--sidebar-bg-deep); border-color: var(--sidebar-border); color: #fff; }
            .back-link { color: var(--sidebar-text-dim); }
        }
    </style>
</head>
<body>

<div class="login-page">

    {{-- Left Branding Panel --}}
    <div class="login-left">
        <div style="position:relative;z-index:1;">
            <div class="login-brand-line"></div>
            <div class="login-brand-name">Digital<br>Library</div>
            <p class="login-brand-tagline">
                A centralized system for managing library resources, borrowing records, attendance, and patron accounts.
            </p>
            <div class="login-brand-divider"></div>
            <div class="login-brand-meta">Management System</div>
        </div>
    </div>

    {{-- Right Form Panel --}}
    <div class="login-right">
        <div class="login-form-wrap">

            <h1 class="login-form-title">Forgot Password</h1>
            <p class="login-form-sub">Enter your email and we'll send you a reset link.</p>

            @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:1.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:16px;height:16px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom:1.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:16px;height:16px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
            </div>
            @endif

            <div class="login-box">
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="form-group" style="margin-bottom:1.5rem;">
                        <label class="form-label" for="email">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email') }}"
                            placeholder="Enter your email"
                            autocomplete="email"
                            required
                            autofocus
                        >
                    </div>
                    <button type="submit" class="btn btn-primary w-full" style="padding:0.625rem;">
                        Send Reset Link
                    </button>
                </form>
            </div>

            <a href="{{ route('login') }}" class="back-link">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Sign In
            </a>

        </div>
    </div>

</div>

</body>
</html>