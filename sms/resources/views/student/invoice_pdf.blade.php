<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 14px; color: #333; }
        .header { width: 100%; border-bottom: 2px solid #444; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { max-width: 150px; float: left; }
        .company-info { text-align: right; float: right; }
        .company-info h1 { margin: 0; font-size: 20px; color: #1a1a1a; }
        .invoice-details { clear: both; margin-top: 20px; overflow: hidden; }
        .bill-to { float: left; width: 50%; }
        .invoice-data { float: right; width: 40%; text-align: right; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 30px; margin-bottom: 20px; }
        th { background-color: #f4f4f4; border-bottom: 2px solid #ddd; padding: 10px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .totals { width: 40%; float: right; margin-top: 20px; }
        .totals-row { border-bottom: 1px solid #ddd; padding: 8px 0; overflow: hidden; }
        .totals-label { float: left; font-weight: bold; }
        .totals-value { float: right; }
        .grand-total { border-top: 2px solid #333; border-bottom: 2px solid #333; padding: 10px 0; margin-top: 10px; font-size: 16px; font-weight: bold; }
        
        .status-paid { color: green; border: 2px solid green; padding: 5px 10px; display: inline-block; transform: rotate(-10deg); font-weight: bold; }
        .status-unpaid { color: red; border: 2px solid red; padding: 5px 10px; display: inline-block; transform: rotate(-10deg); font-weight: bold; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <div class="company-info">
            {{-- Display School Name --}}
            <h1>{{ $school->name ?? 'School Name' }}</h1>
            <p>{{ $school->address ?? 'Address Line 1' }}</p>
            <p>Email: {{ $school->email ?? 'admin@school.com' }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="invoice-details">
        <div class="bill-to">
            <strong>Bill To:</strong><br>
            {{ $invoice->student->name }}<br>
            Student ID: {{ $invoice->student->studentProfile->student_id ?? 'N/A' }}<br>
            Class: {{ $invoice->student->studentProfile->classLevel->name ?? 'N/A' }}
        </div>
        <div class="invoice-data">
            <strong>Invoice #:</strong> {{ $invoice->invoice_number }}<br>
            <strong>Date:</strong> {{ $invoice->created_at->format('d M, Y') }}<br>
            <strong>Term:</strong> {{ $invoice->term->name }} ({{ $invoice->academicSession->name }})<br>
            
            <br>
            @if($invoice->status == 'PAID')
                <span class="status-paid">PAID</span>
            @elseif($invoice->status == 'PARTIAL')
                <span style="color: orange; font-weight: bold;">PARTIAL</span>
            @else
                <span class="status-unpaid">UNPAID</span>
            @endif
        </div>
    </div>

    {{-- Invoice Items Table --}}
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right">Amount (₦)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="text-right">{{ number_format($item->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td>Tuition & Fees</td>
                    <td class="text-right">{{ number_format($invoice->total_amount, 2) }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Totals Section --}}
    <div class="totals">
        <div class="totals-row">
            <span class="totals-label">Subtotal:</span>
            <span class="totals-value">₦{{ number_format($invoice->total_amount, 2) }}</span>
        </div>
        <div class="totals-row">
            <span class="totals-label">Amount Paid:</span>
            <span class="totals-value">₦{{ number_format($invoice->paid_amount, 2) }}</span>
        </div>
        <div class="grand-total">
            <span class="totals-label">Balance Due:</span>
            <span class="totals-value">₦{{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</span>
        </div>
    </div>

    {{-- Payment History --}}
    @if($invoice->payments->count() > 0)
        <div style="clear: both; margin-top: 150px;">
            <h3>Payment History</h3>
            <table style="margin-top: 10px;">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Reference</th>
                        <th>Method</th>
                        <th class="text-right">Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->payments as $payment)
                        <tr>
                            <td>{{ $payment->created_at->format('d M, Y') }}</td>
                            <td>{{ $payment->reference_number }}</td>
                            <td>{{ $payment->payment_method }}</td>
                            <td class="text-right">₦{{ number_format($payment->amount, 2) }}</td>
                            <td>{{ ucfirst($payment->status) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="footer">
        Generated on {{ date('d M Y, h:i A') }} | Thank you for been part of our school.
    </div>

</body>
</html>