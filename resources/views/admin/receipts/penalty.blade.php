<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penalty Receipt #{{ str_pad($penalty->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            background: #f4f6f9;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 2rem 1rem 4rem;
            color: #2c3e55;
        }

        .receipt-wrap {
            width: 100%;
            max-width: 420px;
        }

        /* ── Actions bar (no-print) ── */
        .actions-bar {
            display: flex;
            gap: 0.625rem;
            justify-content: flex-end;
            margin-bottom: 1rem;
        }

        .btn-print {
            background: #1a3a6b;
            color: #fff;
            border: none;
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-family: 'Inter', sans-serif;
            transition: background 0.15s;
        }

        .btn-print:hover { background: #1e4d8c; }

        .btn-close {
            background: #fff;
            color: #2c3e55;
            border: 1px solid #d8dde6;
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: background 0.15s;
        }

        .btn-close:hover { background: #f4f6f9; }

        /* ── Receipt card ── */
        .receipt {
            background: #fff;
            border: 1px solid #d8dde6;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }

        /* Header */
        .receipt-header {
            background: linear-gradient(135deg, #0d1b2e 0%, #1e4d8c 100%);
            padding: 1.75rem 1.75rem 1.5rem;
            text-align: center;
            position: relative;
        }

        .receipt-header::after {
            content: '';
            position: absolute;
            bottom: -1px; left: 0; right: 0;
            height: 12px;
            background: #fff;
            border-radius: 50% 50% 0 0 / 100% 100% 0 0;
        }

        .header-icon {
            width: 44px; height: 44px;
            background: rgba(255,255,255,0.12);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
        }

        .header-icon svg { width: 22px; height: 22px; color: #fff; stroke: #fff; }

        .header-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.02em;
            margin-bottom: 0.2rem;
        }

        .header-sub {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.55);
            text-transform: uppercase;
            letter-spacing: 0.12em;
        }

        /* Body */
        .receipt-body {
            padding: 1.5rem 1.75rem;
        }

        /* Receipt number */
        .receipt-no {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f4f6f9;
            border: 1px solid #e2e6ef;
            border-radius: 6px;
            padding: 0.625rem 0.875rem;
            margin-bottom: 1.25rem;
        }

        .receipt-no-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #8a96a8;
        }

        .receipt-no-value {
            font-size: 0.8rem;
            font-weight: 700;
            color: #1a3a6b;
            font-family: monospace;
            letter-spacing: 0.05em;
        }

        /* Section */
        .section {
            margin-bottom: 1.25rem;
        }

        .section-title {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #b8922a;
            font-weight: 600;
            margin-bottom: 0.625rem;
            padding-bottom: 0.375rem;
            border-bottom: 1px solid #eef0f4;
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 1rem;
            padding: 0.25rem 0;
        }

        .row-label {
            font-size: 0.78rem;
            color: #8a96a8;
            flex-shrink: 0;
        }

        .row-value {
            font-size: 0.8rem;
            color: #2c3e55;
            font-weight: 500;
            text-align: right;
        }

        /* Amount row */
        .amount-row {
            background: #f9f0f0;
            border: 1px solid #f0d8d8;
            border-radius: 6px;
            padding: 0.625rem 0.875rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.5rem;
        }

        .amount-label {
            font-size: 0.78rem;
            color: #c0392b;
            font-weight: 600;
        }

        .amount-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: #c0392b;
            font-family: 'Playfair Display', serif;
        }

        /* PAID stamp */
        .paid-stamp {
            text-align: center;
            margin: 1.25rem 0;
        }

        .paid-inner {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 2.5px solid #27ae60;
            color: #27ae60;
            font-size: 1rem;
            font-weight: 700;
            padding: 0.4rem 1.5rem;
            border-radius: 4px;
            letter-spacing: 0.15em;
            transform: rotate(-2deg);
            font-family: 'Inter', sans-serif;
        }

        .paid-inner svg { width: 16px; height: 16px; stroke: #27ae60; }

        /* Footer */
        .receipt-footer {
            background: #f4f6f9;
            border-top: 1px dashed #d8dde6;
            padding: 1rem 1.75rem;
            text-align: center;
        }

        .receipt-footer p {
            font-size: 0.72rem;
            color: #8a96a8;
            line-height: 1.7;
        }

        .receipt-footer .generated {
            font-size: 0.68rem;
            color: #aab2be;
            margin-top: 0.375rem;
        }

        /* Print */
        @media print {
            body { background: #fff; padding: 0; }
            .actions-bar { display: none; }
            .receipt { box-shadow: none; border: none; border-radius: 0; }
        }
    </style>
</head>
<body>

<div class="receipt-wrap">

    {{-- Action Buttons --}}
    <div class="actions-bar no-print">
        <button class="btn-print" onclick="window.print()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:14px;height:14px;stroke:#fff;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print Receipt
        </button>
        <button class="btn-close" onclick="window.close()">Close</button>
    </div>

    <div class="receipt">

        {{-- Header --}}
        <div class="receipt-header">
            <div class="header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div class="header-title">Digital Library</div>
            <div class="header-sub">Penalty Receipt</div>
        </div>

        <div class="receipt-body">

            {{-- Receipt No. + Date --}}
            <div class="receipt-no">
                <span class="receipt-no-label">Receipt No.</span>
                <span class="receipt-no-value">#{{ str_pad($penalty->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div style="display:flex;justify-content:flex-end;margin-bottom:1.25rem;">
                <span style="font-size:0.72rem;color:#8a96a8;">{{ now()->format('F d, Y h:i A') }}</span>
            </div>

            {{-- Borrower --}}
            <div class="section">
                <div class="section-title">Borrower Details</div>
                <div class="row">
                    <span class="row-label">Name</span>
                    <span class="row-value">{{ $penalty->user->name ?? 'Deleted User' }}</span>
                </div>
                <div class="row">
                    <span class="row-label">ID No.</span>
                    <span class="row-value">{{ $penalty->user->student_id ?? '—' }}</span>
                </div>
                <div class="row">
                    <span class="row-label">Role</span>
                    <span class="row-value">{{ $penalty->user ? ucfirst($penalty->user->role) : '—' }}</span>
                </div>
                <div class="row">
                    <span class="row-label">Department</span>
                    <span class="row-value">{{ $penalty->user->department ?? '—' }}</span>
                </div>
            </div>

            {{-- Book --}}
            <div class="section">
                <div class="section-title">Book Details</div>
                <div class="row">
                    <span class="row-label">Title</span>
                    <span class="row-value" style="max-width:220px;">{{ $penalty->physicalBorrow->book->title }}</span>
                </div>
                <div class="row">
                    <span class="row-label">Accession No.</span>
                    <span class="row-value" style="font-family:monospace;">{{ $penalty->physicalBorrow->book->accession_no }}</span>
                </div>
                <div class="row">
                    <span class="row-label">Borrowed</span>
                    <span class="row-value">{{ $penalty->physicalBorrow->claimed_at?->format('M d, Y') ?? '—' }}</span>
                </div>
                <div class="row">
                    <span class="row-label">Returned</span>
                    <span class="row-value">{{ $penalty->physicalBorrow->returned_at?->format('M d, Y') ?? '—' }}</span>
                </div>
            </div>

            {{-- Penalty --}}
            <div class="section">
                <div class="section-title">Penalty Details</div>
                <div class="row">
                    <span class="row-label">Type</span>
                    <span class="row-value">{{ ucfirst($penalty->type) }} Fine</span>
                </div>
                <div class="row">
                    <span class="row-label">Paid On</span>
                    <span class="row-value">{{ $penalty->paid_at?->format('M d, Y h:i A') ?? now()->format('M d, Y h:i A') }}</span>
                </div>
                <div class="amount-row">
                    <span class="amount-label">Total Amount Paid</span>
                    <span class="amount-value">&#8369;{{ number_format($penalty->amount, 2) }}</span>
                </div>
            </div>

            {{-- PAID Stamp --}}
            <div class="paid-stamp">
                <div class="paid-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    PAID
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="receipt-footer">
            <p>This serves as your official receipt.</p>
            <p>Keep this for your records. Thank you!</p>
            <p class="generated">Generated on {{ now()->format('F d, Y h:i A') }}</p>
        </div>

    </div>
</div>

</body>
</html>