<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penalty;
use Illuminate\Http\Request;

class PenaltyController extends Controller
{
    public function index(Request $request)
    {
        $query = Penalty::with(['user' => function($q) {
            $q->withTrashed();
        }, 'physicalBorrow.book']);

        if ($request->status) {
            $query->where('is_paid', $request->status === 'paid');
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->withTrashed()->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $penalties = $query->latest()->paginate(15);
        $totalUnpaid = Penalty::where('is_paid', false)->sum('amount');

        return view('admin.penalties.index', compact('penalties', 'totalUnpaid'));
    }

    public function markPaid(Penalty $penalty)
    {
        $penalty->update([
            'is_paid' => true,
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Penalty marked as paid!');
    }

    public function markAllPaid(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        Penalty::where('user_id', $request->user_id)
            ->where('is_paid', false)
            ->update(['is_paid' => true, 'paid_at' => now()]);

        return back()->with('success', 'All penalties marked as paid!');
    }
    public function receipt(Penalty $penalty)
    {
        $penalty->load(['user' => function($q) {
            $q->withTrashed();
        }, 'physicalBorrow.book']);
        
        return view('admin.receipts.penalty', compact('penalty'));
    }
}