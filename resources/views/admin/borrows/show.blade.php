@extends('layouts.app')

@section('page-title', 'Manage Borrow')

@section('content')
<div class="max-w-3xl">

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-600 px-4 py-3 rounded mb-4 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Borrow Info -->
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-500">Borrower</p>
                <p class="font-semibold text-gray-800">
                    {{ $borrow->user->name ?? 'Deleted User' }}
                    @if($borrow->user && $borrow->user->trashed())
                        <span class="ml-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-600">Deleted</span>
                    @endif
                </p>
                <p class="text-xs text-gray-500">{{ ucfirst($borrow->user->role) }} • {{ $borrow->user->student_id }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Book</p>
                <p class="font-semibold text-gray-800">{{ $borrow->book->title }}</p>
                <p class="text-xs text-gray-500">{{ $borrow->book->accession_no }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Status</p>
                <span class="px-2 py-1 rounded-full text-xs font-semibold
                    {{ $borrow->status === 'reserved' ? 'bg-blue-100 text-blue-700' :
                       ($borrow->status === 'approved' ? 'bg-yellow-100 text-yellow-700' :
                       ($borrow->status === 'claimed' ? 'bg-orange-100 text-orange-700' :
                       ($borrow->status === 'returned' ? 'bg-green-100 text-green-700' :
                       'bg-gray-100 text-gray-700'))) }}">
                    {{ ucfirst($borrow->status) }}
                </span>
            </div>
            <div>
                <p class="text-xs text-gray-500">Reserved At</p>
                <p class="font-semibold text-gray-800">{{ $borrow->reserved_at?->format('M d, Y h:i A') }}</p>
            </div>
            @if($borrow->due_date)
            <div>
                <p class="text-xs text-gray-500">Due Date</p>
                <p class="font-semibold {{ now()->isAfter($borrow->due_date) ? 'text-red-500' : 'text-gray-800' }}">
                    {{ $borrow->due_date->format('M d, Y') }}
                    @if(now()->isAfter($borrow->due_date))
                        ({{ now()->diffInDays($borrow->due_date) }} days overdue)
                    @endif
                </p>
            </div>
            @endif
            @if($borrow->condition)
            <div>
                <p class="text-xs text-gray-500">Condition</p>
                <p class="font-semibold text-gray-800">{{ ucfirst($borrow->condition) }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Actions</h3>

        @if($borrow->status === 'reserved')
        <div class="flex gap-3">
            <form method="POST" action="{{ route('admin.borrows.approve', $borrow) }}">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700">
                    ✅ Approve Reservation
                </button>
            </form>
            <form method="POST" action="{{ route('admin.borrows.cancel', $borrow) }}">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-red-600">
                    ❌ Cancel Reservation
                </button>
            </form>
        </div>
        @endif

        @if($borrow->status === 'approved')
        <form method="POST" action="{{ route('admin.borrows.claim', $borrow) }}">
            @csrf
            <div class="flex gap-3 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Scan RFID Tag</label>
                    <input type="text" name="rfid_tag" autofocus
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                        placeholder="Scan or enter RFID tag..." required />
                </div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                    📡 Mark as Claimed
                </button>
            </div>
        </form>
        @endif

        @if($borrow->status === 'claimed')
        <form method="POST" action="{{ route('admin.borrows.return', $borrow) }}">
            @csrf
            <div class="flex gap-3 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Book Condition</label>
                    <select name="condition"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                        <option value="">Select Condition</option>
                        <option value="good">Good</option>
                        <option value="damaged">Damaged</option>
                        <option value="lost">Lost</option>
                    </select>
                </div>
                <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-orange-600">
                    📥 Process Return
                </button>
            </div>
        </form>
        @endif
    </div>

    <!-- Penalties -->
    @if($borrow->penalties && $borrow->penalties->count() > 0)
    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Penalties</h3>
        @foreach($borrow->penalties as $penalty)
        <div class="flex justify-between items-center py-3 border-b last:border-0">
            <div>
                <p class="font-medium text-gray-800">{{ ucfirst($penalty->type) }} Fine</p>
                <p class="text-xs text-gray-500">{{ $penalty->created_at->format('M d, Y') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <p class="font-semibold text-red-500">₱{{ number_format($penalty->amount, 2) }}</p>
                <span class="px-2 py-1 rounded-full text-xs font-semibold
                    {{ $penalty->is_paid ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $penalty->is_paid ? 'Paid' : 'Unpaid' }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection