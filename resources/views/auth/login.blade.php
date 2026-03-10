<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Digital Library</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background: var(--sidebar-bg); }

        .login-page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* Left panel — dark navy branding */
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
            width: 1px;
            height: 40px;
            background: var(--sidebar-border);
            margin: 2rem 0;
        }

        .login-brand-meta {
            font-size: 0.75rem;
            color: var(--sidebar-text-dim);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        /* Right panel — white form */
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

        .login-footer-note {
            text-align: center;
            font-size: 0.75rem;
            color: var(--text-dim);
            margin-top: 1.5rem;
        }

        @media (max-width: 768px) {
            .login-page { grid-template-columns: 1fr; }
            .login-left { display: none; }
            .login-right { background: var(--sidebar-bg); }
            .login-box { background: var(--sidebar-active); border-color: var(--sidebar-border); }
            .login-form-title { color: #fff; }
            .login-form-sub { color: var(--sidebar-text); }
            .form-label { color: var(--sidebar-text); }
            .form-control { background: var(--sidebar-bg-deep); border-color: var(--sidebar-border); color: #fff; }
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

            <h1 class="login-form-title">Welcome back</h1>
            <p class="login-form-sub">Sign in to access the library system.</p>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
                @endforeach
            </div>
            @endif

            <div class="login-box">
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
                            placeholder="Enter your email"
                            autocomplete="email"
                            required
                            autofocus
                        >
                    </div>

                    <div class="form-group" style="margin-bottom:1.5rem;">
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

                    <button type="submit" class="btn btn-primary w-full" style="padding:0.625rem;">
                        Sign In
                    </button>
                </form>
            </div>

            <p class="login-footer-note">
                Contact your administrator if you are unable to access your account.
            </p>

        </div>
    </div>

</div>

</body>
</html>