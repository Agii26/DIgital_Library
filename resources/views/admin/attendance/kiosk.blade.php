<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Attendance Kiosk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:        #0d1b2e;
            --navy-deep:   #091525;
            --navy-mid:    #1a3a6b;
            --navy-card:   #112240;
            --navy-item:   #162d4a;
            --gold:        #b8922a;
            --gold-light:  #d4a93a;
            --gold-dim:    rgba(184,146,42,0.18);
            --green:       #1f7a4a;
            --green-bg:    rgba(31,122,74,0.18);
            --green-text:  #4ade80;
            --red:         #9b1c1c;
            --red-bg:      rgba(155,28,28,0.18);
            --red-text:    #f87171;
            --blue-text:   #60a5fa;
            --blue-bg:     rgba(37,99,176,0.18);
            --muted:       rgba(255,255,255,0.45);
            --dim:         rgba(255,255,255,0.22);
            --border:      rgba(255,255,255,0.08);
            --font-serif:  'Playfair Display', serif;
            --font-body:   'Inter', sans-serif;
            --radius:      10px;
            --radius-lg:   16px;
        }

        body {
            background-color: var(--navy-deep);
            font-family: var(--font-body);
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Header ── */
        .kiosk-header {
            background: var(--navy);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }
        .kiosk-header-brand h1 {
            font-family: var(--font-serif);
            font-size: 1.35rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            letter-spacing: 0.01em;
        }
        .kiosk-header-brand h1 svg { color: var(--gold); }
        .kiosk-header-brand p {
            font-size: 0.78rem;
            color: var(--muted);
            margin-top: 2px;
        }
        .kiosk-clock {
            text-align: center;
        }
        #clock {
            font-family: 'Courier New', monospace;
            font-size: 2rem;
            font-weight: 700;
            color: var(--green-text);
            letter-spacing: 0.04em;
            line-height: 1;
        }
        #date {
            font-size: 0.75rem;
            color: var(--muted);
            margin-top: 3px;
        }
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(255,255,255,0.07);
            border: 1px solid var(--border);
            color: var(--muted);
            font-size: 0.8rem;
            font-family: var(--font-body);
            padding: 0.45rem 1rem;
            border-radius: var(--radius);
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
        }
        .btn-back:hover { background: rgba(255,255,255,0.13); color: #fff; }

        /* ── Gold divider bar ── */
        .gold-bar {
            height: 2px;
            background: linear-gradient(90deg, var(--gold) 0%, transparent 100%);
            flex-shrink: 0;
        }

        /* ── Main layout ── */
        .kiosk-body {
            flex: 1;
            display: flex;
            gap: 1.5rem;
            padding: 1.5rem 2rem;
            overflow: hidden;
        }
        .kiosk-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            min-width: 0;
        }
        .kiosk-right {
            width: 300px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            background: var(--navy);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        /* ── Stat cards ── */
        .kiosk-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }
        .kiosk-stat {
            background: var(--navy);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.25rem 1rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .kiosk-stat::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
        }
        .kiosk-stat.green::before { background: var(--green-text); }
        .kiosk-stat.blue::before  { background: var(--blue-text); }
        .kiosk-stat.red::before   { background: var(--red-text); }
        .kiosk-stat-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            margin-bottom: 0.5rem;
        }
        .kiosk-stat-value {
            font-family: var(--font-serif);
            font-size: 2.8rem;
            font-weight: 700;
            line-height: 1;
        }
        .kiosk-stat.green .kiosk-stat-value { color: var(--green-text); }
        .kiosk-stat.blue  .kiosk-stat-value { color: var(--blue-text); }
        .kiosk-stat.red   .kiosk-stat-value { color: var(--red-text); }

        /* ── RFID Input ── */
        .kiosk-scan-wrap {
            background: var(--navy);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            text-align: center;
        }
        .kiosk-scan-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--muted);
            margin-bottom: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .kiosk-scan-label svg { color: var(--gold); }
        #rfid-input {
            width: 100%;
            background: var(--navy-item);
            border: 2px solid var(--border);
            border-radius: var(--radius);
            color: #fff;
            font-size: 1.25rem;
            font-family: var(--font-body);
            text-align: center;
            padding: 1rem 1.5rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        #rfid-input::placeholder { color: var(--dim); }
        #rfid-input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px var(--gold-dim);
        }
        .scan-pulse {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.75rem;
            font-size: 0.75rem;
            color: var(--dim);
        }
        .pulse-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--green-text);
            animation: pulse 1.8s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.3; transform: scale(0.7); }
        }

        /* ── Result card ── */
        #result-card {
            background: var(--navy);
            border: 2px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.75rem 1.5rem;
            text-align: center;
            transition: border-color 0.3s, box-shadow 0.3s;
            animation: slideIn 0.3s ease;
        }
        #result-card.hidden { display: none; }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        #result-card.state-in  { border-color: var(--green-text); box-shadow: 0 0 30px rgba(74,222,128,0.12); }
        #result-card.state-out { border-color: var(--red-text);   box-shadow: 0 0 30px rgba(248,113,113,0.12); }
        #result-card.state-err { border-color: var(--gold);       box-shadow: 0 0 30px var(--gold-dim); }

        .result-icon-wrap {
            width: 64px; height: 64px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.9rem;
        }
        .result-icon-wrap.green { background: var(--green-bg); color: var(--green-text); }
        .result-icon-wrap.red   { background: var(--red-bg);   color: var(--red-text); }
        .result-icon-wrap.gold  { background: var(--gold-dim); color: var(--gold-light); }

        #result-name {
            font-family: var(--font-serif);
            font-size: 1.6rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.25rem;
        }
        #result-role       { font-size: 0.85rem; color: var(--muted); }
        #result-department { font-size: 0.8rem;  color: var(--dim); margin-top: 2px; }
        #result-id         { font-size: 0.75rem; color: var(--dim); margin-top: 2px; }

        .result-badge-wrap { margin: 0.9rem 0 0.5rem; }
        #result-badge {
            display: inline-block;
            padding: 0.45rem 1.75rem;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }
        #result-badge.badge-in  { background: var(--green-bg);  color: var(--green-text); border: 1px solid rgba(74,222,128,0.3); }
        #result-badge.badge-out { background: var(--red-bg);    color: var(--red-text);   border: 1px solid rgba(248,113,113,0.3); }
        #result-badge.badge-err { background: var(--gold-dim);  color: var(--gold-light); border: 1px solid rgba(212,169,58,0.3); }

        #result-time    { font-size: 0.78rem; color: var(--muted); margin-top: 0.4rem; }
        #result-message { font-size: 0.8rem;  color: var(--red-text); margin-top: 0.5rem; }

        /* ── Recent logs panel ── */
        .kiosk-logs-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            font-family: var(--font-serif);
            font-size: 0.95rem;
            font-weight: 600;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-shrink: 0;
        }
        .kiosk-logs-header svg { color: var(--gold); }
        #recent-logs {
            flex: 1;
            overflow-y: auto;
            padding: 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        #recent-logs::-webkit-scrollbar { width: 4px; }
        #recent-logs::-webkit-scrollbar-track { background: transparent; }
        #recent-logs::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 2px; }

        .log-item {
            background: var(--navy-item);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 0.65rem 0.85rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.5rem;
            animation: slideIn 0.25s ease;
        }
        .log-item-name { font-size: 0.82rem; font-weight: 500; color: #fff; }
        .log-item-time { font-size: 0.7rem;  color: var(--dim); margin-top: 2px; }
        .log-item-badge {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            padding: 0.2rem 0.55rem;
            border-radius: 999px;
            flex-shrink: 0;
        }
        .log-item-badge.in  { background: var(--green-bg); color: var(--green-text); }
        .log-item-badge.out { background: var(--red-bg);   color: var(--red-text); }
    </style>
</head>
<body>

    {{-- Header --}}
    <header class="kiosk-header">
        <div class="kiosk-header-brand">
            <h1>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
                Library Attendance
            </h1>
            <p>Tap your RFID card to log attendance</p>
        </div>
        <div class="kiosk-clock">
            <div id="clock"></div>
            <div id="date"></div>
        </div>
        <a href="{{ route('admin.attendance.index') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Logs
        </a>
    </header>
    <div class="gold-bar"></div>

    {{-- Main body --}}
    <div class="kiosk-body">

        {{-- Left: scan area --}}
        <div class="kiosk-left">

            {{-- Stat cards --}}
            <div class="kiosk-stats">
                <div class="kiosk-stat green">
                    <div class="kiosk-stat-label">Today's Time In</div>
                    <div id="today-in" class="kiosk-stat-value">{{ $todayIn }}</div>
                </div>
                <div class="kiosk-stat blue">
                    <div class="kiosk-stat-label">Currently Inside</div>
                    <div id="currently-in" class="kiosk-stat-value">{{ $currentlyIn }}</div>
                </div>
                <div class="kiosk-stat red">
                    <div class="kiosk-stat-label">Today's Time Out</div>
                    <div id="today-out" class="kiosk-stat-value">{{ $todayOut }}</div>
                </div>
            </div>

            {{-- RFID input --}}
            <div class="kiosk-scan-wrap">
                <div class="kiosk-scan-label">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z" />
                    </svg>
                    RFID Input &mdash; Auto Focus
                </div>
                <input
                    type="text"
                    id="rfid-input"
                    autofocus
                    placeholder="Waiting for RFID scan..."
                />
                <div class="scan-pulse">
                    <span class="pulse-dot"></span>
                    Scanner active &mdash; ready to read
                </div>
            </div>

            {{-- Result card --}}
            <div id="result-card" class="hidden">
                <div id="result-icon-wrap" class="result-icon-wrap green">
                    <svg id="result-svg" xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"></svg>
                </div>
                <div id="result-name"></div>
                <div id="result-role"></div>
                <div id="result-department"></div>
                <div id="result-id"></div>
                <div class="result-badge-wrap">
                    <span id="result-badge"></span>
                </div>
                <div id="result-time"></div>
                <div id="result-message" class="hidden"></div>
            </div>

        </div>

        {{-- Right: recent logs --}}
        <div class="kiosk-right">
            <div class="kiosk-logs-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                Recent Logs Today
            </div>
            <div id="recent-logs">
                @foreach($recentLogs as $log)
                <div class="log-item">
                    <div>
                        <div class="log-item-name">{{ $log->user->name }}</div>
                        <div class="log-item-time">{{ $log->scanned_at->format('h:i:s A') }}</div>
                    </div>
                    <span class="log-item-badge {{ $log->type === 'time_in' ? 'in' : 'out' }}">
                        {{ $log->type === 'time_in' ? 'IN' : 'OUT' }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <script>
        // ── Clock ──
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').textContent =
                now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('date').textContent =
                now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        }
        updateClock();
        setInterval(updateClock, 1000);

        // ── RFID ──
        const rfidInput  = document.getElementById('rfid-input');
        const resultCard = document.getElementById('result-card');
        let scanTimeout, hideTimeout;

        document.addEventListener('click', () => rfidInput.focus());
        rfidInput.addEventListener('blur', () => rfidInput.focus());

        rfidInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const rfid = rfidInput.value.trim();
                if (rfid) { processRfid(rfid); rfidInput.value = ''; }
            }
        });

        rfidInput.addEventListener('input', function() {
            clearTimeout(scanTimeout);
            scanTimeout = setTimeout(() => {
                const rfid = rfidInput.value.trim();
                if (rfid.length >= 4) { processRfid(rfid); rfidInput.value = ''; }
            }, 300);
        });

        const ICON_CHECK = `<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>`;
        const ICON_OUT   = `<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>`;
        const ICON_ERR   = `<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>`;

        function processRfid(rfid) {
            clearTimeout(hideTimeout);

            fetch('{{ route("admin.attendance.kiosk.scan") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ rfid_tag: rfid })
            })
            .then(res => res.json())
            .then(data => {
                resultCard.classList.remove('hidden', 'state-in', 'state-out', 'state-err');

                const iconWrap = document.getElementById('result-icon-wrap');
                const svg      = document.getElementById('result-svg');
                const badge    = document.getElementById('result-badge');
                const msg      = document.getElementById('result-message');

                if (data.success) {
                    const isIn = data.type === 'time_in';

                    resultCard.classList.add(isIn ? 'state-in' : 'state-out');
                    iconWrap.className = 'result-icon-wrap ' + (isIn ? 'green' : 'red');
                    svg.innerHTML      = isIn ? ICON_CHECK : ICON_OUT;

                    document.getElementById('result-name').textContent       = data.name;
                    document.getElementById('result-role').textContent       = data.role;
                    document.getElementById('result-department').textContent = data.department;
                    document.getElementById('result-id').textContent         = 'ID: ' + data.student_id;
                    document.getElementById('result-time').textContent       = 'Recorded at ' + data.time;

                    badge.textContent  = isIn ? 'Time In' : 'Time Out';
                    badge.className    = 'badge-' + (isIn ? 'in' : 'out');
                    msg.classList.add('hidden');

                    document.getElementById('today-in').textContent     = data.today_in;
                    document.getElementById('today-out').textContent    = data.today_out;
                    document.getElementById('currently-in').textContent = data.currently_in;

                    addRecentLog(data.name, data.time, data.type);

                } else {
                    resultCard.classList.add('state-err');
                    iconWrap.className = 'result-icon-wrap gold';
                    svg.innerHTML      = ICON_ERR;

                    document.getElementById('result-name').textContent       = 'Scan Failed';
                    document.getElementById('result-role').textContent       = '';
                    document.getElementById('result-department').textContent = '';
                    document.getElementById('result-id').textContent         = '';
                    document.getElementById('result-time').textContent       = '';

                    badge.textContent = 'Error';
                    badge.className   = 'badge-err';
                    msg.textContent   = data.message;
                    msg.classList.remove('hidden');
                }

                hideTimeout = setTimeout(() => resultCard.classList.add('hidden'), 4000);
            });
        }

        function addRecentLog(name, time, type) {
            const container = document.getElementById('recent-logs');
            const div = document.createElement('div');
            div.className = 'log-item';
            div.innerHTML = `
                <div>
                    <div class="log-item-name">${name}</div>
                    <div class="log-item-time">${time}</div>
                </div>
                <span class="log-item-badge ${type === 'time_in' ? 'in' : 'out'}">
                    ${type === 'time_in' ? 'IN' : 'OUT'}
                </span>
            `;
            container.insertBefore(div, container.firstChild);
        }
    </script>

</body>
</html>