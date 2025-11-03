<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }

        .invoice-header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }

        .invoice-header-left {
            display: table-cell;
            vertical-align: middle;
            width: 30%;
        }

        .invoice-header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 70%;
        }

        .logo {
            max-width: 150px;
            max-height: 80px;
            margin-bottom: 10px;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .invoice-subtitle {
            font-size: 14px;
            color: #666;
        }

        .invoice-info {
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .invoice-info-row {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .invoice-info-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .invoice-info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            text-align: right;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 13px;
            color: #333;
            margin-bottom: 8px;
        }

        .invoice-details {
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .invoice-details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .invoice-details-table th {
            background-color: #333;
            color: #fff;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .invoice-details-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            font-size: 12px;
        }

        .invoice-details-table tr:last-child td {
            border-bottom: none;
        }

        .invoice-details-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .amount-section {
            margin-top: 30px;
            text-align: right;
        }

        .amount-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .amount-label {
            display: table-cell;
            text-align: right;
            padding-right: 20px;
            font-weight: bold;
            font-size: 13px;
            width: 60%;
        }

        .amount-value {
            display: table-cell;
            text-align: right;
            font-size: 14px;
            width: 40%;
        }

        .total-amount {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            padding-top: 10px;
            border-top: 2px solid #333;
        }

        .invoice-footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .invoice-date {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }
        body{
            padding: 20px 30px;
        }
        .invoice-header{
            margin-bottom: 20px;
        }
        .invoice-header-left{
            width: 30%;
        }
        .invoice-header-right{
            width: 70%;
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <div class="invoice-header-left">
            @if(file_exists(public_path('img/logo.png')))
                <img src="{{ public_path('img/logo.png') }}" alt="Logo" class="logo">
            @else
                <div style="font-size: 18px; font-weight: bold;">LOGO</div>
            @endif
        </div>
        <div class="invoice-header-right">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-subtitle">Invoice #{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div class="invoice-date">Date: {{ $invoice->created_at->format('d F, Y') }}</div>
        </div>
    </div>

    <div class="invoice-info">
        <div class="invoice-info-row">
            <div class="invoice-info-left">
                <div class="info-section">
                    <div class="info-label">Bill To:</div>
                    <div class="info-value">{{ $invoice->customer->name }}</div>
                    @if($invoice->customer->email)
                        <div class="info-value" style="font-size: 11px; color: #666;">{{ $invoice->customer->email }}</div>
                    @endif
                </div>
            </div>
            <div class="invoice-info-right">
                <div class="info-section">
                    <div class="info-label">From:</div>
                    <div class="info-value">{{ $invoice->agent->name }}</div>
                    @if($invoice->agent->email)
                        <div class="info-value" style="font-size: 11px; color: #666;">{{ $invoice->agent->email }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($invoice->vehicle)
    <div class="invoice-info">
        <div class="info-section">
            <div class="info-label">Vehicle Information:</div>
            <div class="info-value">{{ $invoice->vehicle->title ?? 'N/A' }}</div>
        </div>
    </div>
    @endif

    <div class="invoice-details">
        <table class="invoice-details-table">
            <thead>
                <tr>
                    <th style="width: 60%;">Description</th>
                    <th style="width: 20%;">Date</th>
                    <th style="width: 20%; text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Payment for {{ $invoice->vehicle->title ?? 'Vehicle' }}</td>
                    <td>{{ $invoice->amount_date->format('d M, Y') }}</td>
                    <td style="text-align: right;">${{ number_format($invoice->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="amount-section">
        <div class="amount-row">
            <div class="amount-label">Subtotal:</div>
            <div class="amount-value">${{ number_format($invoice->amount, 2) }}</div>
        </div>
        <div class="amount-row">
            <div class="amount-label">Total Amount:</div>
            <div class="amount-value total-amount">${{ number_format($invoice->amount, 2) }}</div>
        </div>
    </div>

    <div class="invoice-footer">
        <p>Thank you for your business!</p>
        <p>This is a computer-generated invoice and does not require a signature.</p>
    </div>
</body>
</html>

