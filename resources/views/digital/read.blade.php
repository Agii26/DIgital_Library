<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $book->title }} - Reading</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-900 min-h-screen flex flex-col">

    <!-- Header -->
    <div class="bg-gray-800 text-white px-6 py-3 flex justify-between items-center">
        <div>
            <h1 class="font-semibold">{{ $book->title }}</h1>
            <p class="text-xs text-gray-400">{{ $book->author }}</p>
        </div>
        <div class="flex items-center gap-6">
            <!-- Countdown Timer -->
            <div class="text-center">
                <p class="text-xs text-gray-400">Time Remaining</p>
                <p id="timer" class="text-2xl font-bold text-green-400 font-mono">--:--</p>
            </div>
            <a href="{{ route('digital.index') }}"
                class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-500">
                ← Back
            </a>
        </div>
    </div>

    <!-- PDF Viewer -->
    <div class="flex-1">
        <iframe
            src="{{ asset('storage/' . $book->digitalBook->file_path) }}#toolbar=0"
            class="w-full h-full"
            style="height: calc(100vh - 65px);"
            id="pdf-frame">
        </iframe>
    </div>

    <!-- Session Expired Modal -->
    <div id="expired-modal" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 max-w-md text-center">
            <p class="text-5xl mb-4">⏰</p>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Reading Time Expired!</h2>
            <p class="text-gray-500 mb-6">Your reading session has ended. You can start a new session anytime.</p>
            <div class="flex gap-3 justify-center">
                <a href="{{ route('digital.read', $book) }}"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                    Start New Session
                </a>
                <a href="{{ route('digital.index') }}"
                    class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-300">
                    Back to Books
                </a>
            </div>
        </div>
    </div>

    <script>
        let remainingSeconds = {{ $remainingSeconds }};
        const sessionId = {{ $activeSession->id }};
        const timerEl = document.getElementById('timer');
        const modal = document.getElementById('expired-modal');
        const pdfFrame = document.getElementById('pdf-frame');

        function updateTimer() {
            if (remainingSeconds <= 0) {
                clearInterval(interval);
                timerEl.textContent = '00:00';
                timerEl.classList.remove('text-green-400');
                timerEl.classList.add('text-red-400');

                // Expire session on server
                fetch(`/digital-sessions/${sessionId}/expire`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                });

                // Disable PDF
                pdfFrame.style.pointerEvents = 'none';
                pdfFrame.style.opacity = '0.3';

                // Show modal
                modal.classList.remove('hidden');
                return;
            }

            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = remainingSeconds % 60;
            timerEl.textContent = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');

            // Turn yellow when 5 minutes left
            if (remainingSeconds <= 300) {
                timerEl.classList.remove('text-green-400');
                timerEl.classList.add('text-yellow-400');
            }

            // Turn red when 1 minute left
            if (remainingSeconds <= 60) {
                timerEl.classList.remove('text-yellow-400');
                timerEl.classList.add('text-red-400');
            }

            remainingSeconds--;
        }

        updateTimer();
        const interval = setInterval(updateTimer, 1000);
    </script>

</body>
</html>