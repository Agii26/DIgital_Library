<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penalty Receipt</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; font-size: 13px; padding: 20px; max-width: 400px; margin: 0 auto; }
        .center { text-align: center; }
        .divider { border-top: 1px dashed #000; margin: 10px 0; }
        .row { display: flex; justify-content: space-between; margin: 4px 0; }
        .label { color: #555; }
        .bold { font-weight: bold; }
        .large { font-size: 16px; }
        .paid-stamp { text-align: center; border: 3px solid green; color: green; font-size: 24px; font-weight: bold; padding: 5px; margin: 10px 0; transform: rotate(-5deg); }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="center">
        <p class="bold large">📚 DIGITAL LIBRARY</p>
        <p>PENALTY RECEIPT</p>
        <p style="font-size:11px; color:#777;">{{ now()->format('F d, Y h:i A') }}</p>
    </div>

    <div class="divider"></div>

    <div class="row">
        <span class="label">Receipt No.:</span>
        <span class="bold">#{{ str_pad($penalty->id, 6, '0', STR_PAD_LEFT) }}</span>
    </div>

    <div class="divider"></div>

    <p class="bold" style="margin-bottom:5px;">BORROWER DETAILS</p>
    <div class="row">
        <span class="label">Name:</span>
        <span>{{ $penalty->user->name ?? 'Deleted User' }}</span>
    </div>
    <div class="row">
        <span class="label">ID No.:</span>
        <span>{{ $penalty->user->student_id ?? '-' }}</span>
    </div>
    <div class="row">
        <span class="label">Role:</span>
        <span>{{ $penalty->user ? ucfirst($penalty->user->role) : '-' }}</span>
    </div>
    <div class="row">
        <span class="label">Department:</span>
        <span>{{ $penalty->user->department ?? '-' }}</span>
    </div>

    <div class="divider"></div>

    <p class="bold" style="margin-bottom:5px;">BOOK DETAILS</p>
    <div class="row">
        <span class="label">Title:</span>
        <span>{{ $penalty->physicalBorrow->book->title }}</span>
    </div>
    <div class="row">
        <span class="label">Accession No.:</span>
        <span>{{ $penalty->physicalBorrow->book->accession_no }}</span>
    </div>
    <div class="row">
        <span class="label">Borrowed:</span>
        <span>{{ $penalty->physicalBorrow->claimed_at?->format('M d, Y') ?? '-' }}</span>
    </div>
    <div class="row">
        <span class="label">Returned:</span>
        <span>{{ $penalty->physicalBorrow->returned_at?->format('M d, Y') ?? '-' }}</span>
    </div>

    <div class="divider"></div>

    <p class="bold" style="margin-bottom:5px;">PENALTY DETAILS</p>
    <div class="row">
        <span class="label">Type:</span>
        <span>{{ ucfirst($penalty->type) }} Fine</span>
    </div>
    <div class="row">
        <span class="label">Amount:</span>
        <span class="bold">₱{{ number_format($penalty->amount, 2) }}</span>
    </div>
    <div class="row">
        <span class="label">Paid On:</span>
        <span>{{ $penalty->paid_at?->format('M d, Y h:i A') ?? now()->format('M d, Y h:i A') }}</span>
    </div>

    <div class="divider"></div>

    <div class="paid-stamp">✓ PAID</div>

    <div class="divider"></div>

    <div class="center" style="font-size:11px; color:#777; margin-top:10px;">
        <p>This serves as your official receipt.</p>
        <p>Thank you!</p>
    </div>

    <div class="no-print" style="text-align:center; margin-top:20px;">
        <button onclick="window.print()" style="background:#2563eb; color:white; border:none; padding:10px 30px; border-radius:8px; cursor:pointer; font-size:14px;">
            🖨️ Print Receipt
        </button>
        <button onclick="window.close()" style="background:#e5e7eb; color:#374151; border:none; padding:10px 30px; border-radius:8px; cursor:pointer; font-size:14px; margin-left:10px;">
            Close
        </button>
    </div>

</body>
</html>