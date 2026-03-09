<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Attendance Kiosk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-900 min-h-screen flex flex-col">

    <!-- Header -->
    <div class="bg-gray-800 text-white px-8 py-4 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">📚 Library Attendance</h1>
            <p class="text-sm text-gray-400">Tap your RFID card to log attendance</p>
        </div>
        <div class="text-right">
            <p id="clock" class="text-3xl font-mono font-bold text-green-400"></p>
            <p id="date" class="text-sm text-gray-400"></p>
        </div>
        <a href="{{ route('admin.attendance.index') }}"
            class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-500">
            ← Back to Logs
        </a>
    </div>

    <div class="flex flex-1 gap-6 p-8">

        <!-- Left: Scan Area -->
        <div class="flex-1 flex flex-col gap-6">

            <!-- Summary Cards -->
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-gray-800 rounded-2xl p-5 text-center">
                    <p class="text-gray-400 text-sm">Today's Time In</p>
                    <h3 id="today-in" class="text-4xl font-bold text-green-400 mt-1">{{ $todayIn }}</h3>
                </div>
                <div class="bg-gray-800 rounded-2xl p-5 text-center">
                    <p class="text-gray-400 text-sm">Currently Inside</p>
                    <h3 id="currently-in" class="text-4xl font-bold text-blue-400 mt-1">{{ $currentlyIn }}</h3>
                </div>
                <div class="bg-gray-800 rounded-2xl p-5 text-center">
                    <p class="text-gray-400 text-sm">Today's Time Out</p>
                    <h3 id="today-out" class="text-4xl font-bold text-red-400 mt-1">{{ $todayOut }}</h3>
                </div>
            </div>

            <!-- RFID Input -->
            <div class="bg-gray-800 rounded-2xl p-6">
                <p class="text-gray-400 text-sm mb-3 text-center">RFID Input (auto-focus)</p>
                <input type="text" id="rfid-input" autofocus
                    placeholder="Waiting for RFID scan..."
                    class="w-full bg-gray-700 text-white text-center text-xl rounded-xl px-6 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-500" />
            </div>

            <!-- Result Card -->
            <div id="result-card" class="hidden bg-gray-800 rounded-2xl p-6 text-center transition-all">
                <p id="result-icon" class="text-6xl mb-3"></p>
                <h2 id="result-name" class="text-2xl font-bold text-white"></h2>
                <p id="result-role" class="text-gray-400 mt-1"></p>
                <p id="result-department" class="text-gray-400 text-sm"></p>
                <p id="result-id" class="text-gray-500 text-sm"></p>
                <div id="result-badge" class="mt-3 inline-block px-6 py-2 rounded-full text-lg font-bold"></div>
                <p id="result-time" class="text-gray-400 text-sm mt-2"></p>
                <p id="result-message" class="text-red-400 text-sm mt-2 hidden"></p>
            </div>
        </div>

        <!-- Right: Recent Logs -->
        <div class="w-80 bg-gray-800 rounded-2xl p-5 flex flex-col">
            <h3 class="text-white font-semibold mb-4">📋 Recent Logs Today</h3>
            <div id="recent-logs" class="flex flex-col gap-2 overflow-y-auto flex-1">
                @foreach($recentLogs as $log)
                <div class="bg-gray-700 rounded-xl px-4 py-3 flex justify-between items-center">
                    <div>
                        <p class="text-white text-sm font-medium">{{ $log->user->name }}</p>
                        <p class="text-gray-400 text-xs">{{ $log->scanned_at->format('h:i:s A') }}</p>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        {{ $log->type === 'time_in' ? 'bg-green-900 text-green-400' : 'bg-red-900 text-red-400' }}">
                        {{ $log->type === 'time_in' ? 'IN' : 'OUT' }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <script>
        // Clock
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('date').textContent = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        }
        updateClock();
        setInterval(updateClock, 1000);

        // RFID Input
        const rfidInput = document.getElementById('rfid-input');
        const resultCard = document.getElementById('result-card');
        let scanTimeout;
        let hideTimeout;

        // Keep input focused always
        document.addEventListener('click', () => rfidInput.focus());
        rfidInput.addEventListener('blur', () => rfidInput.focus());

        rfidInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const rfid = rfidInput.value.trim();
                if (rfid) {
                    processRfid(rfid);
                    rfidInput.value = '';
                }
            }
        });

        // Also auto-submit after short pause (for RFID readers that don't send Enter)
        rfidInput.addEventListener('input', function() {
            clearTimeout(scanTimeout);
            scanTimeout = setTimeout(() => {
                const rfid = rfidInput.value.trim();
                if (rfid.length >= 4) {
                    processRfid(rfid);
                    rfidInput.value = '';
                }
            }, 300);
        });

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
                resultCard.classList.remove('hidden');

                if (data.success) {
                    const isIn = data.type === 'time_in';

                    document.getElementById('result-icon').textContent = isIn ? '✅' : '👋';
                    document.getElementById('result-name').textContent = data.name;
                    document.getElementById('result-role').textContent = data.role;
                    document.getElementById('result-department').textContent = data.department;
                    document.getElementById('result-id').textContent = 'ID: ' + data.student_id;
                    document.getElementById('result-time').textContent = 'Time: ' + data.time;
                    document.getElementById('result-message').classList.add('hidden');

                    const badge = document.getElementById('result-badge');
                    badge.textContent = isIn ? '✅ TIME IN' : '🚪 TIME OUT';
                    badge.className = 'mt-3 inline-block px-6 py-2 rounded-full text-lg font-bold ' +
                        (isIn ? 'bg-green-500 text-white' : 'bg-red-500 text-white');

                    resultCard.className = 'bg-gray-800 rounded-2xl p-6 text-center border-2 ' +
                        (isIn ? 'border-green-500' : 'border-red-500');

                    // Update summary cards
                    document.getElementById('today-in').textContent = data.today_in;
                    document.getElementById('today-out').textContent = data.today_out;
                    document.getElementById('currently-in').textContent = data.currently_in;

                    // Add to recent logs
                    addRecentLog(data.name, data.time, data.type);

                } else {
                    document.getElementById('result-icon').textContent = '❌';
                    document.getElementById('result-name').textContent = 'Scan Failed';
                    document.getElementById('result-role').textContent = '';
                    document.getElementById('result-department').textContent = '';
                    document.getElementById('result-id').textContent = '';
                    document.getElementById('result-time').textContent = '';
                    const msg = document.getElementById('result-message');
                    msg.textContent = data.message;
                    msg.classList.remove('hidden');
                    document.getElementById('result-badge').textContent = '';
                    resultCard.className = 'bg-gray-800 rounded-2xl p-6 text-center border-2 border-yellow-500';
                }

                // Auto hide after 4 seconds
                hideTimeout = setTimeout(() => {
                    resultCard.classList.add('hidden');
                }, 4000);
            });
        }

        function addRecentLog(name, time, type) {
            const logsContainer = document.getElementById('recent-logs');
            const div = document.createElement('div');
            div.className = 'bg-gray-700 rounded-xl px-4 py-3 flex justify-between items-center';
            div.innerHTML = `
                <div>
                    <p class="text-white text-sm font-medium">${name}</p>
                    <p class="text-gray-400 text-xs">${time}</p>
                </div>
                <span class="px-2 py-1 rounded-full text-xs font-semibold ${type === 'time_in' ? 'bg-green-900 text-green-400' : 'bg-red-900 text-red-400'}">
                    ${type === 'time_in' ? 'IN' : 'OUT'}
                </span>
            `;
            logsContainer.insertBefore(div, logsContainer.firstChild);
        }
    </script>

</body>
</html>