@extends('layouts.app')

@section('page-title', 'Penalties Management')

@section('content')

{{-- Page Header --}}
<div class="page-title-wrap">
    <div>
        <h1 class="page-title">Penalties Management</h1>
        <p class="page-subtitle">Track and collect overdue, damage, and loss fines</p>
    </div>
</div>

{{-- Summary Stat --}}
<div style="margin-bottom:1.5rem;">
    <div class="stat-card" style="max-width:280px;">
        <div class="stat-card-accent red"></div>
        <div class="stat-icon red">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-label">Total Unpaid Penalties</div>
        <div class="stat-value" style="color:var(--danger);font-size:1.75rem;">&#8369;{{ number_format($totalUnpaid, 2) }}</div>
        <div class="stat-sub">Outstanding balance</div>
    </div>
</div>

{{-- Search & Filters --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-body" style="padding:1.125rem 1.5rem;">
        <form method="GET" action="{{ route('admin.penalties.index') }}" style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap;">
            <div style="position:relative;flex:1;min-width:200px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);width:14px;height:14px;color:var(--text-dim);pointer-events:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by user name..."
                    class="form-control" style="padding-left:2.25rem;">
            </div>
            <select name="type" class="form-control" style="width:auto;min-width:140px;">
                <option value="">All Types</option>
                <option value="overdue"  {{ request('type') === 'overdue'  ? 'selected' : '' }}>Overdue</option>
                <option value="damaged"  {{ request('type') === 'damaged'  ? 'selected' : '' }}>Damaged</option>
                <option value="lost"     {{ request('type') === 'lost'     ? 'selected' : '' }}>Lost</option>
            </select>
            <select name="status" class="form-control" style="width:auto;min-width:140px;">
                <option value="">All Status</option>
                <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="paid"   {{ request('status') === 'paid'   ? 'selected' : '' }}>Paid</option>
            </select>
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="{{ route('admin.penalties.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>
</div>

{{-- Penalties Table --}}
<div class="card">
    <div class="table-wrapper" style="border:none;border-radius:var(--radius-lg);box-shadow:none;">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Book</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penalties as $penalty)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.625rem;">
                            <div class="avatar avatar-sm">{{ strtoupper(substr($penalty->user->name ?? 'D', 0, 1)) }}</div>
                            <div>
                                <p style="font-size:0.845rem;font-weight:500;color:var(--text-head);">
                                    {{ $penalty->user->name ?? 'Deleted User' }}
                                    @if($penalty->user && $penalty->user->trashed())
                                        <span class="badge badge-danger" style="font-size:0.6rem;margin-left:0.3rem;">Deleted</span>
                                    @endif
                                </p>
                                <p style="font-size:0.72rem;color:var(--text-muted);margin-top:0.1rem;">
                                    {{ $penalty->user ? ucfirst($penalty->user->role) : '—' }}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:0.845rem;color:var(--text-muted);max-width:180px;">
                        <span style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block;">
                            {{ $penalty->physicalBorrow->book->title ?? '—' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge
                            {{ $penalty->type === 'overdue'  ? 'badge-warning' :
                               ($penalty->type === 'damaged' ? 'badge-gold' :
                               'badge-danger') }}">
                            {{ ucfirst($penalty->type) }}
                        </span>
                    </td>
                    <td style="font-size:0.875rem;font-weight:600;color:var(--danger);">
                        &#8369;{{ number_format($penalty->amount, 2) }}
                    </td>
                    <td>
                        <span class="badge {{ $penalty->is_paid ? 'badge-success' : 'badge-danger' }}">
                            {{ $penalty->is_paid ? 'Paid' : 'Unpaid' }}
                        </span>
                    </td>
                    <td style="font-size:0.835rem;color:var(--text-muted);white-space:nowrap;">
                        {{ $penalty->created_at->format('M d, Y') }}
                    </td>
                    <td>
                        <div style="display:flex;gap:0.375rem;align-items:center;flex-wrap:wrap;">
                            @if(!$penalty->is_paid)
                            <form method="POST" action="{{ route('admin.penalties.mark-paid', $penalty) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Mark Paid
                                </button>
                            </form>
                            @else
                            <span style="font-size:0.75rem;color:var(--text-dim);">
                                Paid {{ $penalty->paid_at?->format('M d, Y') }}
                            </span>
                            @endif

                            @if($penalty->is_paid)
                            <a href="{{ route('admin.penalties.receipt', $penalty) }}" target="_blank" class="btn btn-sm btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                Receipt
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <div class="empty-state-icon" style="background:var(--success-pale);color:var(--success);">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="empty-state-title">No penalties found</p>
                            <p class="empty-state-text">Try adjusting your search or filters.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($penalties->hasPages())
    <div class="card-footer" style="display:flex;align-items:center;justify-content:space-between;">
        <p style="font-size:0.8rem;color:var(--text-muted);">
            Showing {{ $penalties->firstItem() }}–{{ $penalties->lastItem() }} of {{ $penalties->total() }} records
        </p>
        <div class="pagination">
            {{ $penalties->links() }}
        </div>
    </div>
    @endif
</div>

@endsection