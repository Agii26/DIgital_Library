<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Password — Digital Library</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background: var(--sidebar-bg); }

        .set-password-page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* Left panel — dark navy */
        .left-panel {
            background: var(--sidebar-bg-deep);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3.5rem 4rem;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: -80px; left: -80px;
            width: 320px; height: 320px;
            border-radius: 50%;
            background: rgba(30,77,140,0.15);
            pointer-events: none;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -60px; right: -60px;
            width: 240px; height: 240px;
            border-radius: 50%;
            background: rgba(184,146,42,0.06);
            pointer-events: none;
        }

        .left-brand-line {
            width: 40px; height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light));
            border-radius: 2px;
            margin-bottom: 1rem;
        }

        .left-brand-name {
            font-family: var(--font-serif);
            font-size: 1.2rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.25rem;
        }

        .left-brand-sub {
            font-size: 0.68rem;
            color: var(--sidebar-text-dim);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .left-headline {
            position: relative;
            z-index: 1;
        }

        .left-headline h2 {
            font-family: var(--font-serif);
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.25;
            margin-bottom: 0.75rem;
        }

        .left-headline p {
            font-size: 0.855rem;
            color: var(--sidebar-text);
            line-height: 1.7;
            max-width: 300px;
            margin-bottom: 0;
        }

        .steps {
            display: flex;
            flex-direction: column;
            gap: 0.625rem;
            margin-top: 2rem;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: var(--radius);
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
        }

        .step.done { border-color: rgba(106,154,90,0.3); background: rgba(106,154,90,0.08); }
        .step.current { border-color: rgba(184,146,42,0.35); background: rgba(184,146,42,0.08); }
        .step.pending { opacity: 0.4; }

        .step-icon {
            width: 22px; height: 22px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-size: 0.7rem;
        }

        .step.done .step-icon { background: rgba(106,154,90,0.2); color: #8aba78; }
        .step.current .step-icon { background: rgba(184,146,42,0.2); color: var(--gold-light); }
        .step.pending .step-icon { background: rgba(255,255,255,0.08); color: var(--sidebar-text-dim); }

        .step-icon svg { width: 12px; height: 12px; }

        .step-text {
            font-size: 0.8rem;
            color: var(--sidebar-text);
        }

        .step.done .step-text { color: #8aba78; }
        .step.current .step-text { color: var(--gold-light); font-weight: 500; }

        .left-footer {
            font-size: 0.72rem;
            color: var(--sidebar-text-dim);
            position: relative;
            z-index: 1;
        }

        /* Right panel */
        .right-panel {
            background: #f8f9fb;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 4rem 3rem;
        }

        .form-wrap {
            width: 100%;
            max-width: 400px;
        }

        .form-heading {
            margin-bottom: 1.75rem;
        }

        .form-heading h1 {
            font-family: var(--font-serif);
            font-size: 1.6rem;
            font-weight: 600;
            color: var(--text-head);
            margin-bottom: 0.35rem;
        }

        .form-heading p {
            font-size: 0.835rem;
            color: var(--text-muted);
            margin-bottom: 0;
        }

        .form-box {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow-md);
        }

        .password-toggle {
            position: relative;
        }

        .password-toggle input { padding-right: 2.5rem; }

        .toggle-btn {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-dim);
            padding: 0.25rem;
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }

        .toggle-btn:hover { color: var(--text-muted); }
        .toggle-btn svg { width: 15px; height: 15px; }

        /* Strength bar */
        .strength-bar {
            display: flex;
            gap: 3px;
            margin-top: 0.5rem;
        }

        .strength-segment {
            height: 3px;
            flex: 1;
            border-radius: 2px;
            background: var(--border);
            transition: background 0.2s;
        }

        .strength-label {
            font-size: 0.72rem;
            color: var(--text-dim);
            margin-top: 0.3rem;
        }

        .form-footer {
            text-align: center;
            font-size: 0.75rem;
            color: var(--text-dim);
            margin-top: 1.25rem;
        }

        @media (max-width: 768px) {
            .set-password-page { grid-template-columns: 1fr; }
            .left-panel { display: none; }
            .right-panel { background: var(--sidebar-bg); }
            .form-box {
                background: var(--sidebar-active);
                border-color: var(--sidebar-border);
            }
            .form-heading h1 { color: #fff; }
            .form-heading p { color: var(--sidebar-text); }
            .form-label { color: var(--sidebar-text) !important; }
            .form-control {
                background: var(--sidebar-bg-deep);
                border-color: var(--sidebar-border);
                color: #fff;
            }
        }
    </style>
</head>
<body>

<div class="set-password-page">

    {{-- Left Panel --}}
    <div class="left-panel">
        <div style="position:relative;z-index:1;">
            <div class="left-brand-line"></div>
            <div class="left-brand-name">Digital Library</div>
            <div class="left-brand-sub">Management System</div>
        </div>

        <div class="left-headline">
            <h2>Almost there.<br>Set your password.</h2>
            <p>You have been invited to the Digital Library System. Create a secure password to activate your account.</p>

            <div class="steps">
                <div class="step done">
                    <div class="step-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="step-text">Account created by administrator</span>
                </div>
                <div class="step current">
                    <div class="step-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                    <span class="step-text">Set your password</span>
                </div>
                <div class="step pending">
                    <div class="step-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <span class="step-text">Access the library system</span>
                </div>
            </div>
        </div>

        <div class="left-footer">
            &copy; {{ now()->year }} Digital Library System
        </div>
    </div>

    {{-- Right Panel --}}
    <div class="right-panel">
        <div class="form-wrap">

            <div class="form-heading">
                <h1>Set your password</h1>
                <p>Create a secure password to activate your account.</p>
            </div>

            @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom:1.25rem;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:16px;height:16px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <div class="form-box">
                <form method="POST" action="{{ route('set-password.store') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    <input type="hidden" name="token" value="{{ request()->token }}">

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" value="{{ $user->email }}"
                            class="form-control"
                            style="background:var(--surface-2);color:var(--text-muted);cursor:not-allowed;"
                            disabled>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">New Password <span class="required">*</span></label>
                        <div class="password-toggle">
                            <input type="password" id="password" name="password"
                                class="form-control"
                                placeholder="Minimum 8 characters"
                                required>
                            <button type="button" class="toggle-btn" onclick="togglePassword('password', this)">
                                <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                        <div id="strength-wrap" style="display:none;margin-top:0.5rem;">
                            <div class="strength-bar">
                                <div class="strength-segment" id="s1"></div>
                                <div class="strength-segment" id="s2"></div>
                                <div class="strength-segment" id="s3"></div>
                                <div class="strength-segment" id="s4"></div>
                            </div>
                            <p class="strength-label" id="strength-label"></p>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:1.5rem;">
                        <label class="form-label" for="password_confirmation">Confirm Password <span class="required">*</span></label>
                        <div class="password-toggle">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control"
                                placeholder="Repeat your password"
                                required>
                            <button type="button" class="toggle-btn" onclick="togglePassword('password_confirmation', this)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-full" style="padding:0.625rem;">
                        Activate Account
                    </button>
                </form>
            </div>

            <p class="form-footer">
                Link expired? Contact your library administrator.
            </p>

        </div>
    </div>

</div>

<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.style.color = input.type === 'text' ? 'var(--blue-bright)' : 'var(--text-dim)';
}

document.getElementById('password').addEventListener('input', function () {
    const val = this.value;
    const wrap = document.getElementById('strength-wrap');
    wrap.style.display = val.length > 0 ? 'block' : 'none';

    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const colors = ['#e07060', '#e0b060', '#d4a93a', '#6a9a5a'];
    const labels = ['Weak', 'Fair', 'Good', 'Strong'];

    for (let i = 1; i <= 4; i++) {
        document.getElementById('s' + i).style.background = i <= score ? colors[score - 1] : 'var(--border)';
    }

    document.getElementById('strength-label').textContent = val.length > 0 ? (labels[score - 1] || '') : '';
    document.getElementById('strength-label').style.color = score > 0 ? colors[score - 1] : 'var(--text-dim)';
});
</script>

</body>
</html>