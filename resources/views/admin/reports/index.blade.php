@extends('layouts.app')

@section('page-title', 'Reports & Analytics')

@section('content')

<div class="flex justify-end mb-4">
    <a href="{{ route('admin.reports.export', ['year' => now()->year]) }}"
        class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700">
        📥 Export to Excel
    </a>
</div>
<!-- Overview Stats -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow p-4 border-l-4 border-blue-500">
        <p class="text-xs text-gray-500">Total Books</p>
        <h3 class="text-2xl font-bold text-blue-600">{{ $totalBooks }}</h3>
    </div>
    <div class="bg-white rounded-2xl shadow p-4 border-l-4 border-green-500">
        <p class="text-xs text-gray-500">Total Users</p>
        <h3 class="text-2xl font-bold text-green-600">{{ $totalUsers }}</h3>
    </div>
    <div class="bg-white rounded-2xl shadow p-4 border-l-4 border-yellow-500">
        <p class="text-xs text-gray-500">Total Borrows</p>
        <h3 class="text-2xl font-bold text-yellow-600">{{ $totalBorrows }}</h3>
    </div>
    <div class="bg-white rounded-2xl shadow p-4 border-l-4 border-red-500">
        <p class="text-xs text-gray-500">Total Penalties</p>
        <h3 class="text-2xl font-bold text-red-600">₱{{ number_format($totalPenalties, 2) }}</h3>
    </div>
    <div class="bg-white rounded-2xl shadow p-4 border-l-4 border-orange-500">
        <p class="text-xs text-gray-500">Unpaid</p>
        <h3 class="text-2xl font-bold text-orange-600">₱{{ number_format($totalUnpaid, 2) }}</h3>
    </div>
    <div class="bg-white rounded-2xl shadow p-4 border-l-4 border-purple-500">
        <p class="text-xs text-gray-500">Collected</p>
        <h3 class="text-2xl font-bold text-purple-600">₱{{ number_format($totalCollected, 2) }}</h3>
    </div>
    
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- Monthly Borrows Chart -->
    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">📊 Monthly Borrows ({{ $year }})</h3>
        <canvas id="borrowsChart"></canvas>
    </div>

    <!-- Monthly Attendance Chart -->
    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">📅 Monthly Attendance ({{ $year }})</h3>
        <canvas id="attendanceChart"></canvas>
    </div>
</div>

<!-- Books by Status + Penalty Breakdown -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">📚 Books by Status</h3>
        <canvas id="booksStatusChart"></canvas>
    </div>
    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">💰 Penalty Breakdown</h3>
        <canvas id="penaltyChart"></canvas>
    </div>
</div>

<!-- Most Borrowed + Most Active -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">🏆 Most Borrowed Books</h3>
        @forelse($mostBorrowed as $borrow)
        <div class="flex justify-between items-center py-2 border-b last:border-0">
            <div>
                <p class="font-medium text-gray-800 text-sm">{{ $borrow->book->title }}</p>
                <p class="text-xs text-gray-500">{{ $borrow->book->accession_no }}</p>
            </div>
            <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">
                {{ $borrow->borrow_count }}x
            </span>
        </div>
        @empty
        <p class="text-gray-400 text-sm">No data yet.</p>
        @endforelse
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">👤 Most Active Borrowers</h3>
        @forelse($mostActive as $borrow)
        <div class="flex justify-between items-center py-2 border-b last:border-0">
            <div>
                <p class="font-medium text-gray-800 text-sm">{{ $borrow->user->name }}</p>
                <p class="text-xs text-gray-500">{{ ucfirst($borrow->user->role) }} • {{ $borrow->user->department ?? '-' }}</p>
            </div>
            <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">
                {{ $borrow->borrow_count }}x
            </span>
        </div>
        @empty
        <p class="text-gray-400 text-sm">No data yet.</p>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    // Monthly Borrows
    new Chart(document.getElementById('borrowsChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Borrows',
                data: @json($monthlyBorrowsData),
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    // Monthly Attendance
    new Chart(document.getElementById('attendanceChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Visits',
                data: @json($monthlyAttendanceData),
                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    // Books by Status
    new Chart(document.getElementById('booksStatusChart'), {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($booksByStatus)),
            datasets: [{
                data: @json(array_values($booksByStatus)),
                backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#ef4444', '#6b7280'],
            }]
        },
        options: { responsive: true }
    });

    // Penalty Breakdown
    new Chart(document.getElementById('penaltyChart'), {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($penaltyBreakdown)),
            datasets: [{
                data: @json(array_values($penaltyBreakdown)),
                backgroundColor: ['#f59e0b', '#ef4444', '#6b7280'],
            }]
        },
        options: { responsive: true }
    });
</script>
@endsection