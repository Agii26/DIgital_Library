<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Penalty;
use Illuminate\Support\Facades\Auth;

class PenaltyController extends Controller
{
    public function index()
    {
        $penalties = Penalty::with('physicalBorrow.book')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        $totalUnpaid = Penalty::where('user_id', Auth::id())
            ->where('is_paid', false)
            ->sum('amount');

        return view('student.penalties.index', compact('penalties', 'totalUnpaid'));
    }
}