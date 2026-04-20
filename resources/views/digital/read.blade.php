<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $book->title }} — Reading</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: #091525;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Inter', sans-serif;
        }

        /* ── Reader Header ── */
        .reader-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0 1.5rem;
            height: 60px;
            background: var(--sidebar-bg, #0d1b2e);
            border-bottom: 1px solid rgba(255,255,255,0.07);
            flex-shrink: 0;
            z-index: 10;
        }

        .reader-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
            padding: 0.35rem 0.75rem;
            border-radius: var(--radius, 6px);
            border: 1px solid rgba(255,255,255,0.1);
            transition: color 0.15s, background 0.15s;
            flex-shrink: 0;
        }
        .reader-back:hover {
            color: #fff;
            background: rgba(255,255,255,0.07);
        }

        .reader-book-info {
            flex: 1;
            min-width: 0;
        }
        .reader-book-title {
            font-family: var(--font-serif, 'Playfair Display', serif);
            font-size: 0.95rem;
            font-weight: 600;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .reader-book-author {
            font-size: 0.72rem;
            color: rgba(255,255,255,0.4);
            margin-top: 1px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ── Timer ── */
        .reader-timer-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-shrink: 0;
        }
        .reader-timer-label {
            font-size: 0.62rem;
            font-weight: 600;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            margin-bottom: 1px;
        }
        #timer {
            font-family: 'Courier New', monospace;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            color: #4ade80;
            line-height: 1;
            transition: color 0.4s;
        }
        #timer.warn  { color: var(--gold-light, #d4a93a); }
        #timer.urgent { color: #f87171; }

        /* ── PDF Frame ── */
        .reader-body {
            flex: 1;
            display: flex;
            overflow: hidden;
            position: relative; /* add this here */
        }
        #pdf-frame {
            width: 100%;
            border: none;
            display: block;
            transition: opacity 0.4s;
        }

        /* ── Expired Modal ── */
        .ra-modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(5, 10, 20, 0.85);
            z-index: 500;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .ra-modal-backdrop.open {
            display: flex;
        }
        .ra-modal {
            background: var(--surface, #ffffff);
            border-radius: var(--radius-lg, 12px);
            padding: 2.5rem 2rem;
            max-width: 420px;
            width: 100%;
            text-align: center;
            box-shadow: 0 24px 64px rgba(0,0,0,0.5);
            animation: ra-modal-in 0.25s ease;
        }
        @keyframes ra-modal-in {
            from { opacity: 0; transform: translateY(16px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .ra-modal-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #fef2f2;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
        }
        .ra-modal-title {
            font-family: var(--font-serif, 'Playfair Display', serif);
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-head, #111827);
            margin-bottom: 0.5rem;
        }
        .ra-modal-text {
            font-size: 0.875rem;
            color: var(--text-muted, #6b7280);
            line-height: 1.6;
            margin-bottom: 1.75rem;
        }
        .ra-modal-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* ── Mobile ── */
        @media (max-width: 540px) {
            .reader-header {
                padding: 0 1rem;
                height: 56px;
                gap: 0.65rem;
            }
            .reader-book-title { font-size: 0.82rem; }
            #timer { font-size: 1.2rem; }
            .reader-back span { display: none; }
            .reader-back { padding: 0.35rem 0.5rem; }
        }
    </style>
</head>
<body>

    {{-- ── Reader Header ── --}}
    <header class="reader-header">
        <a href="{{ route('digital.index') }}" class="reader-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            <span>Back to Books</span>
        </a>

        <div class="reader-book-info">
            <div class="reader-book-title">{{ $book->title }}</div>
            <div class="reader-book-author">{{ $book->author }}</div>
        </div>

        <div class="reader-timer-wrap">
            <div class="reader-timer-label">Time Remaining</div>
            <div id="timer">--:--</div>
        </div>
    </header>

    {{-- ── PDF Viewer ── --}}
    <div class="reader-body">
        <div id="pdf-overlay" style="
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 5;
    pointer-events: all;
"></div>
        <iframe
            src="{{ asset('storage/' . $book->digitalBook->file_path) }}#toolbar=0"
            id="pdf-frame"
            style="height:calc(100vh - 60px);"
        ></iframe>
    </div>

    {{-- ── Session Expired Modal ── --}}
    <div id="expired-modal" class="ra-modal-backdrop">
        <div class="ra-modal">
            <div class="ra-modal-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="#ef4444" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ra-modal-title">Reading Time Expired</div>
            <div class="ra-modal-text">Your reading session has ended. You may start a new session to continue reading.</div>
            <div class="ra-modal-actions">
                <a href="{{ route('digital.read', $book) }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:5px;vertical-align:-2px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                    </svg>
                    New Session
                </a>
                <a href="{{ route('digital.index') }}" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:5px;vertical-align:-2px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    Back to Books
                </a>
            </div>
        </div>
    </div>

    <script>
        let remainingSeconds = {{ $remainingSeconds }};
        const sessionId      = {{ $activeSession->id }};
        const timerEl        = document.getElementById('timer');
        const modal          = document.getElementById('expired-modal');
        const pdfFrame       = document.getElementById('pdf-frame');

        // Disable right-click on the entire page
document.addEventListener('contextmenu', e => e.preventDefault());

// Disable keyboard shortcuts for saving
document.addEventListener('keydown', e => {
    // Ctrl+S / Cmd+S (Save)
    if ((e.ctrlKey || e.metaKey) && e.key === 's') e.preventDefault();
    // Ctrl+P / Cmd+P (Print)
    if ((e.ctrlKey || e.metaKey) && e.key === 'p') e.preventDefault();
    // Ctrl+Shift+S (Save As in some browsers)
    if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 's') e.preventDefault();
});

        function updateTimer() {
            if (remainingSeconds <= 0) {
                clearInterval(interval);
                timerEl.textContent = '00:00';
                timerEl.className = 'urgent';

                fetch(`/digital-sessions/${sessionId}/expire`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                });

                pdfFrame.style.pointerEvents = 'none';
                pdfFrame.style.opacity = '0.2';
                modal.classList.add('open');
                return;
            }

            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = remainingSeconds % 60;
            timerEl.textContent = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');

            timerEl.className = remainingSeconds <= 60 ? 'urgent' : remainingSeconds <= 300 ? 'warn' : '';

            remainingSeconds--;
        }

        updateTimer();
        const interval = setInterval(updateTimer, 1000);
    </script>

</body>
</html>