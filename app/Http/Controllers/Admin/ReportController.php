<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\PhysicalBorrow;
use App\Models\Penalty;
use App\Models\User;
use App\Models\AttendanceLog;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year  = $request->year ?? now()->year;

        // ── Overview stats ──
        $totalBooks     = Book::count();
        $totalUsers     = User::whereIn('role', ['faculty', 'student'])->count();
        $totalBorrows   = PhysicalBorrow::count();
        $totalPenalties = Penalty::sum('amount');
        $totalUnpaid    = Penalty::where('is_paid', false)->sum('amount');
        $totalCollected = Penalty::where('is_paid', true)->sum('amount');

        // ── Monthly borrows ──
        $monthlyBorrows = PhysicalBorrow::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $monthlyBorrowsData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyBorrowsData[] = $monthlyBorrows[$i] ?? 0;
        }

        // ── Most borrowed books ──
        $mostBorrowed = PhysicalBorrow::selectRaw('book_id, COUNT(*) as borrow_count')
            ->with('book')
            ->groupBy('book_id')
            ->orderByDesc('borrow_count')
            ->limit(5)
            ->get();

        // ── Most active borrowers ──
        $mostActive = PhysicalBorrow::selectRaw('user_id, COUNT(*) as borrow_count')
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('borrow_count')
            ->limit(5)
            ->get();

        // ── Monthly attendance ──
        $monthlyAttendance = AttendanceLog::selectRaw('MONTH(scanned_at) as month, COUNT(*) as count')
            ->whereYear('scanned_at', $year)
            ->where('type', 'time_in')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $monthlyAttendanceData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyAttendanceData[] = $monthlyAttendance[$i] ?? 0;
        }

        // ── Penalty breakdown ──
        $penaltyBreakdown = Penalty::selectRaw('type, SUM(amount) as total')
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();

        // ── Books by status (from book_copies) ──
        $copyStatuses = BookCopy::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $booksByStatus = [
            'available' => $copyStatuses['available'] ?? 0,
            'borrowed'  => $copyStatuses['borrowed']  ?? 0,
            'reserved'  => $copyStatuses['reserved']  ?? 0,
            'damaged'   => $copyStatuses['damaged']   ?? 0,
            'lost'      => $copyStatuses['lost']      ?? 0,
        ];

        // ── Detailed report table data ──
        $reportBooks = Book::orderBy('title')->get();

        $reportUsers = User::whereIn('role', ['faculty', 'student'])
            ->orderBy('name')
            ->get();

        $reportBorrows = PhysicalBorrow::with(['user', 'book'])
            ->orderByDesc('created_at')
            ->get();

        // physicalBorrow.book is the real relationship;
        // we also load 'borrow.book' via the alias defined in Penalty model
        $reportPenalties = Penalty::with(['user', 'physicalBorrow.book'])
            ->orderByDesc('created_at')
            ->get();

        $reportUnpaid = Penalty::with(['user', 'physicalBorrow.book'])
            ->where('is_paid', false)
            ->orderByDesc('created_at')
            ->get();

        $reportCollected = Penalty::with(['user', 'physicalBorrow.book'])
            ->where('is_paid', true)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.reports.index', compact(
            'totalBooks', 'totalUsers', 'totalBorrows',
            'totalPenalties', 'totalUnpaid', 'totalCollected',
            'monthlyBorrowsData', 'mostBorrowed', 'mostActive',
            'monthlyAttendanceData', 'penaltyBreakdown', 'booksByStatus',
            'month', 'year',
            // drill-down table
            'reportBooks', 'reportUsers', 'reportBorrows',
            'reportPenalties', 'reportUnpaid', 'reportCollected'
        ));
    }

    public function export(Request $request)
    {
        $year = $request->year ?? now()->year;

        $borrows = PhysicalBorrow::with(['user', 'book.copies'])
            ->whereYear('created_at', $year)
            ->get();

        $penalties = Penalty::with(['user', 'physicalBorrow.book'])
            ->whereYear('created_at', $year)
            ->get();

        $attendance = AttendanceLog::with('user')
            ->whereYear('scanned_at', $year)
            ->get();

        $filename    = 'library_report_' . $year . '.xlsx';
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // ── Sheet 1: Borrows ──
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Borrows');
        $sheet1->fromArray(
            ['Borrower', 'Role', 'Book', 'Copies (Accession Nos.)', 'Status', 'Reserved', 'Due Date', 'Returned', 'Condition'],
            null, 'A1'
        );
        $row = 2;
        foreach ($borrows as $borrow) {
            $accessionNos = $borrow->book
                ? $borrow->book->copies->pluck('accession_no')->join(', ')
                : '-';

            $sheet1->fromArray([
                $borrow->user->name          ?? '-',
                ucfirst($borrow->user->role  ?? '-'),
                $borrow->book->title         ?? '-',
                $accessionNos,
                ucfirst($borrow->status),
                $borrow->reserved_at?->format('M d, Y') ?? '-',
                $borrow->due_date?->format('M d, Y')    ?? '-',
                $borrow->returned_at?->format('M d, Y') ?? '-',
                $borrow->condition ? ucfirst($borrow->condition) : '-',
            ], null, 'A' . $row);
            $row++;
        }

        // ── Sheet 2: Penalties ──
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Penalties');
        $sheet2->fromArray(
            ['User', 'Role', 'Book', 'Type', 'Amount', 'Status', 'Date'],
            null, 'A1'
        );
        $row = 2;
        foreach ($penalties as $penalty) {
            $sheet2->fromArray([
                $penalty->user->name                  ?? '-',
                ucfirst($penalty->user->role          ?? '-'),
                $penalty->physicalBorrow->book->title ?? '-',
                ucfirst($penalty->type),
                '₱' . number_format($penalty->amount, 2),
                $penalty->is_paid ? 'Paid' : 'Unpaid',
                $penalty->created_at->format('M d, Y'),
            ], null, 'A' . $row);
            $row++;
        }

        // ── Sheet 3: Attendance ──
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Attendance');
        $sheet3->fromArray(
            ['Name', 'ID No.', 'Role', 'Department', 'Type', 'Time'],
            null, 'A1'
        );
        $row = 2;
        foreach ($attendance as $log) {
            $sheet3->fromArray([
                $log->user->name,
                $log->user->student_id ?? '-',
                ucfirst($log->user->role),
                $log->user->department ?? '-',
                $log->type === 'time_in' ? 'Time In' : 'Time Out',
                $log->scanned_at->format('M d, Y h:i A'),
            ], null, 'A' . $row);
            $row++;
        }

        $writer   = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'report');
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
}