<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Payment Receipt - {{ $payment->payment_no }}
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            background: #f2f4f7;
            font-family: Arial, Helvetica, sans-serif;
            color: #212529;
        }

        .receipt-wrapper {
            max-width: 900px;
            margin: auto;
        }

        .receipt {
            background: #ffffff;
            padding: 40px;
            border: 1px solid #ddd;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #222;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-details {
            font-size: 13px;
            color: #666;
            line-height: 1.6;
        }

        .receipt-title {
            text-align: right;
        }

        .receipt-title h1 {
            margin: 0;
            font-size: 28px;
        }

        .receipt-number {
            margin-top: 8px;
            font-size: 14px;
            color: #555;
        }

        .status {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 12px;
            background: #198754;
            color: #fff;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 15px;
            font-weight: bold;
            background: #f5f5f5;
            padding: 10px;
            border-left: 4px solid #0d6efd;
            margin-bottom: 12px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 30px;
        }

        .info-item {
            font-size: 14px;
        }

        .label {
            color: #777;
            display: block;
            font-size: 12px;
            margin-bottom: 3px;
        }

        .value {
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 11px;
            font-size: 13px;
        }

        th {
            background: #f5f5f5;
            text-align: left;
        }

        .amount {
            text-align: right;
        }

        .total-row td {
            font-weight: bold;
            font-size: 15px;
        }

        .amount-words {
            margin-top: 15px;
            padding: 12px;
            background: #fafafa;
            border: 1px solid #ddd;
            font-size: 13px;
        }

        .footer {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }

        .signature {
            width: 220px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-top: 45px;
            padding-top: 8px;
            font-size: 12px;
        }

        .note {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #777;
        }

        .print-button {
            margin-bottom: 20px;
            text-align: right;
        }

        .print-button button {
            border: 0;
            background: #0d6efd;
            color: #fff;
            padding: 9px 18px;
            border-radius: 5px;
            cursor: pointer;
        }

        @media print {

            body {
                padding: 0;
                background: #fff;
            }

            .receipt-wrapper {
                max-width: none;
            }

            .receipt {
                border: none;
                padding: 20px;
            }

            .print-button {
                display: none;
            }

        }

    </style>

</head>


<body>

<div class="receipt-wrapper">

    {{-- Print button --}}
    <div class="print-button">

        <button onclick="window.print()">
            Print Receipt
        </button>

    </div>


    <div class="receipt">


        {{-- =====================================================
             HEADER
        ====================================================== --}}

        <div class="header">

            <div>

                <div class="company-name">
                    Mall Management
                </div>

                <div class="company-details">

                    Property Management System<br>

                    Revenue & Leasing Department

                </div>

            </div>


            <div class="receipt-title">

                <h1>
                    PAYMENT RECEIPT
                </h1>

                <div class="receipt-number">

                    Receipt No:
                    <strong>
                        {{ $payment->payment_no }}
                    </strong>

                </div>

                <div class="status">
                    {{ $payment->payment_status }}
                </div>

            </div>

        </div>


        {{-- =====================================================
             PAYMENT INFORMATION
        ====================================================== --}}

        <div class="section">

            <div class="section-title">
                Payment Information
            </div>

            <div class="info-grid">

                <div class="info-item">

                    <span class="label">
                        Payment Number
                    </span>

                    <span class="value">
                        {{ $payment->payment_no }}
                    </span>

                </div>


                <div class="info-item">

                    <span class="label">
                        Payment Date
                    </span>

                    <span class="value">

                        {{ $payment->payment_date
                            ? \Carbon\Carbon::parse(
                                $payment->payment_date
                            )->format('d M Y')
                            : '-' }}

                    </span>

                </div>


                <div class="info-item">

                    <span class="label">
                        Payment Mode
                    </span>

                    <span class="value">
                        {{ $payment->payment_mode }}
                    </span>

                </div>


                <div class="info-item">

                    <span class="label">
                        Transaction Reference
                    </span>

                    <span class="value">

                        {{ $payment->transaction_reference ?: '-' }}

                    </span>

                </div>

            </div>

        </div>


        {{-- =====================================================
             TENANT INFORMATION
        ====================================================== --}}

        <div class="section">

            <div class="section-title">
                Tenant Information
            </div>

            <div class="info-grid">

                <div class="info-item">

                    <span class="label">
                        Tenant
                    </span>

                    <span class="value">

                        {{ $payment->tenant?->company_name ?? '-' }}

                    </span>

                </div>


                <div class="info-item">

                    <span class="label">
                        Tenant Code
                    </span>

                    <span class="value">

                        {{ $payment->tenant?->tenant_code ?? '-' }}

                    </span>

                </div>


                <div class="info-item">

                    <span class="label">
                        GST Number
                    </span>

                    <span class="value">

                        {{ $payment->tenant?->gst_number ?? '-' }}

                    </span>

                </div>


                <div class="info-item">

                    <span class="label">
                        Contact
                    </span>

                    <span class="value">

                        {{ $payment->tenant?->phone ?? '-' }}

                    </span>

                </div>

            </div>

        </div>


        {{-- =====================================================
             INVOICE INFORMATION
        ====================================================== --}}

        <div class="section">

            <div class="section-title">
                Invoice Information
            </div>

            <div class="info-grid">

                <div class="info-item">

                    <span class="label">
                        Invoice Number
                    </span>

                    <span class="value">

                        {{ $payment->invoice?->invoice_no ?? '-' }}

                    </span>

                </div>


                <div class="info-item">

                    <span class="label">
                        Invoice Date
                    </span>

                    <span class="value">

                        {{ $payment->invoice?->invoice_date
                            ? \Carbon\Carbon::parse(
                                $payment->invoice->invoice_date
                            )->format('d M Y')
                            : '-' }}

                    </span>

                </div>

            </div>

        </div>


        {{-- =====================================================
             PAYMENT ALLOCATION
        ====================================================== --}}

        <div class="section">

            <div class="section-title">
                Payment Allocation
            </div>

            <table>

                <thead>

                    <tr>

                        <th>
                            Invoice
                        </th>

                        <th>
                            Allocation Date
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="amount">
                            Amount
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($payment->allocations as $allocation)

                        <tr>

                            <td>

                                {{ $payment->invoice?->invoice_no ?? '-' }}

                            </td>

                            <td>

                                {{ $allocation->allocation_date
                                    ? \Carbon\Carbon::parse(
                                        $allocation->allocation_date
                                    )->format('d M Y')
                                    : '-' }}

                            </td>

                            <td>

                                {{ $allocation->allocation_status }}

                            </td>

                            <td class="amount">

                                ${{ number_format(
                                    $allocation->allocated_amount,
                                    2
                                ) }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4" style="text-align:center">

                                No allocation found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>


                <tfoot>

                    <tr class="total-row">

                        <td colspan="3">
                            Payment Amount
                        </td>

                        <td class="amount">

                            ${{ number_format(
                                $payment->payment_amount,
                                2
                            ) }}

                        </td>

                    </tr>

                </tfoot>

            </table>

        </div>


        {{-- =====================================================
             AMOUNT IN WORDS
        ====================================================== --}}

        <div class="amount-words">

            <strong>
                Amount in Words:
            </strong>

             {{ $amountInWords }}

        </div>


        {{-- =====================================================
             FOOTER / SIGNATURE
        ====================================================== --}}

        <div class="footer">

            <div class="signature">

                <div class="signature-line">
                    Received By
                </div>

            </div>


            <div class="signature">

                <div class="signature-line">
                    Authorized Signatory
                </div>

            </div>

        </div>


        <div class="note">

            This is a computer-generated payment receipt.

        </div>

    </div>

</div>

</body>

</html>