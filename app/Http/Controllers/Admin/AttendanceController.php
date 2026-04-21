<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // Minimum seconds between scans for the same user
    private const SCAN_INTERVAL = 60;

    public function index(Request $request)
    {
        $query = AttendanceLog::with('user');

        if ($request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('student_id', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->date) {
            $query->whereDate('scanned_at', $request->date);
        }

        if ($request->role) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        $logs = $query->orderBy('scanned_at', 'desc')->paginate(10);

        $todayVisits = AttendanceLog::whereDate('scanned_at', today())->where('type', 'time_in')->count();
        $weekVisits  = AttendanceLog::whereBetween('scanned_at', [now()->startOfWeek(), now()->endOfWeek()])->where('type', 'time_in')->count();
        $monthVisits = AttendanceLog::whereMonth('scanned_at', now()->month)->where('type', 'time_in')->count();
        $currentlyIn = AttendanceLog::where('type', 'time_in')
            ->whereDate('scanned_at', today())
            ->whereDoesntHave('user', function($q) {
                $q->whereHas('attendanceLogs', function($q2) {
                    $q2->where('type', 'time_out')->whereDate('scanned_at', today());
                });
            })->count();

        return view('admin.attendance.index', compact(
            'logs', 'todayVisits', 'weekVisits', 'monthVisits', 'currentlyIn'
        ));
    }

    public function scan(Request $request)
    {
        $request->validate([
            'rfid_tag' => 'required|string',
        ]);

        $user = User::where('rfid_tag', $request->rfid_tag)
            ->whereIn('role', ['faculty', 'student'])
            ->first();

        if (!$user) {
            return back()->withErrors(['rfid_tag' => 'RFID tag not recognized.']);
        }

        $lastLog = AttendanceLog::where('user_id', $user->id)
            ->whereDate('scanned_at', today())
            ->latest('scanned_at')
            ->first();

        // Enforce interval using DB-level seconds comparison to avoid Carbon cast issues
        if ($lastLog) {
            $secondsSince = $lastLog->scanned_at->diffInSeconds(now());
            if ($secondsSince < self::SCAN_INTERVAL) {
                $remaining = ceil(self::SCAN_INTERVAL - $secondsSince);
                return back()->withErrors([
                    'rfid_tag' => $user->name . ' was already scanned. Please wait ' . $remaining . ' second(s).'
                ]);
            }
        }

        $type = (!$lastLog || $lastLog->type === 'time_out') ? 'time_in' : 'time_out';

        AttendanceLog::create([
            'user_id'    => $user->id,
            'rfid_tag'   => $request->rfid_tag,
            'type'       => $type,
            'scanned_at' => now(),
        ]);

        return back()->with('success',
            $user->name . ' — ' . ($type === 'time_in' ? 'Time In' : 'Time Out') . ' recorded at ' . now()->format('h:i A')
        );
    }

    public function export(Request $request)
    {
        $query = AttendanceLog::with('user');

        if ($request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('student_id', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->date) {
            $query->whereDate('scanned_at', $request->date);
        }

        if ($request->role) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        $logs = $query->orderBy('scanned_at', 'desc')->paginate(10)->withQueryString();

        $datePart = $request->date ?? now()->format('Y-m-d');
        $rolePart = $request->role ? '_' . $request->role : '';
        $searchPart = $request->search ? '_' . str_replace(' ', '-', strtolower($request->search)) : '';
        $filename = 'attendance' . $rolePart . '_' . $datePart . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'ID No.', 'Role', 'Department', 'Type', 'Time']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->user->name,
                    $log->user->student_id ?? '-',
                    ucfirst($log->user->role),
                    $log->user->department ?? '-',
                    $log->type === 'time_in' ? 'Time In' : 'Time Out',
                    \Carbon\Carbon::parse($log->scanned_at)->format('M d, Y h:i A'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function kiosk()
    {
        $todayIn     = AttendanceLog::whereDate('scanned_at', today())->where('type', 'time_in')->count();
        $todayOut    = AttendanceLog::whereDate('scanned_at', today())->where('type', 'time_out')->count();
        $currentlyIn = $todayIn - $todayOut;
        $recentLogs  = AttendanceLog::with('user')->whereDate('scanned_at', today())->latest('scanned_at')->limit(10)->get();

        return view('admin.attendance.kiosk', compact('todayIn', 'todayOut', 'currentlyIn', 'recentLogs'));
    }

    public function kioskScan(Request $request)
    {
        $request->validate(['rfid_tag' => 'required|string']);

        $user = User::where('rfid_tag', $request->rfid_tag)->where('is_active', true)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'RFID not recognized or user is inactive.',
            ]);
        }

        $lastLog = AttendanceLog::where('user_id', $user->id)
            ->whereDate('scanned_at', today())
            ->latest('scanned_at')
            ->first();

        // Enforce interval using explicit Carbon parse to avoid cast issues
        if ($lastLog) {
            $secondsSince = $lastLog->scanned_at->diffInSeconds(now());
            if ($secondsSince < self::SCAN_INTERVAL) {
                $remaining = ceil(self::SCAN_INTERVAL - $secondsSince);
                return response()->json([
                    'success' => false,
                    'message' => 'Please wait ' . $remaining . ' second(s) before scanning again.',
                ]);
            }
        }

        $type = (!$lastLog || $lastLog->type === 'time_in') ? 'time_out' : 'time_in';
        // Correct logic: if last was time_in → time_out, otherwise time_in
        $type = ($lastLog && $lastLog->type === 'time_in') ? 'time_out' : 'time_in';

        AttendanceLog::create([
            'user_id'    => $user->id,
            'rfid_tag'   => $request->rfid_tag,
            'type'       => $type,
            'scanned_at' => now(),
        ]);

        $todayIn     = AttendanceLog::whereDate('scanned_at', today())->where('type', 'time_in')->count();
        $todayOut    = AttendanceLog::whereDate('scanned_at', today())->where('type', 'time_out')->count();
        $currentlyIn = $todayIn - $todayOut;

        return response()->json([
            'success'      => true,
            'type'         => $type,
            'name'         => $user->name,
            'role'         => ucfirst($user->role),
            'department'   => $user->department ?? '-',
            'student_id'   => $user->student_id ?? '-',
            'time'         => now()->format('h:i:s A'),
            'today_in'     => $todayIn,
            'today_out'    => $todayOut,
            'currently_in' => $currentlyIn,
        ]);
    }
}