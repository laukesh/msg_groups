<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">

    <title>
        Invoice {{ $invoice->invoice_no }}
    </title>

    <style>

        body {
            font-family: Arial, sans-serif;
            color: #222;
            background: #fff;
            margin: 0;
            padding: 30px;
        }

        .invoice {
            max-width: 900px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .title {
            font-size: 28px;
            font-weight: bold;
        }

        .invoice-info {
            text-align: right;
        }

        .section {
            margin-bottom: 25px;
        }

        .label {
            color: #777;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .value {
            font-weight: 600;
        }

        .tenant-box {
            border: 1px solid #ddd;
            padding: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f5f5f5;
            text-align: left;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            width: 350px;
            margin-left: auto;
            margin-top: 20px;
        }

        .summary td {
            border: none;
            padding: 6px;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
            border-top: 2px solid #222 !important;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            background: #eee;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            color: #777;
            font-size: 12px;
        }

        .print-button {
            margin-bottom: 20px;
            padding: 8px 15px;
            border: 0;
            background: #0d6efd;
            color: white;
            cursor: pointer;
            border-radius: 4px;
        }

        @media print {

            body {
                padding: 0;
            }

            .print-button {
                display: none;
            }

        }

    </style>

</head>

<body>

<div class="invoice">

    <button
        class="print-button"
        onclick="window.print()"
    >
        Print Invoice
    </button>


    {{-- HEADER --}}

    <div class="header">

        <div>

            <div class="title">
                INVOICE
            </div>

            <div>
                {{ $invoice->invoice_no }}
            </div>

        </div>


        <div class="invoice-info">

            <div>

                <strong>
                    Invoice Date:
                </strong>

                {{ $invoice->invoice_date
                    ? \Carbon\Carbon::parse(
                        $invoice->invoice_date
                    )->format('d M Y')
                    : '—'
                }}

            </div>

            <div>

                <strong>
                    Due Date:
                </strong>

                {{ $invoice->due_date
                    ? \Carbon\Carbon::parse(
                        $invoice->due_date
                    )->format('d M Y')
                    : '—'
                }}

            </div>

            <div>

                <strong>
                    Status:
                </strong>

                <span class="status">
                    {{ $invoice->invoice_status }}
                </span>

            </div>

        </div>

    </div>


    {{-- TENANT --}}

    <div class="section">

        <div class="label">
            BILL TO
        </div>

        <div class="tenant-box">

            @if($invoice->tenant)

                <div class="value">
                    {{ $invoice->tenant->company_name }}
                </div>

                <div>
                    Tenant Code:
                    {{ $invoice->tenant->tenant_code }}
                </div>

                @if($invoice->tenant->email)

                    <div>
                        Email:
                        {{ $invoice->tenant->email }}
                    </div>

                @endif

                @if($invoice->tenant->phone)

                    <div>
                        Phone:
                        {{ $invoice->tenant->phone }}
                    </div>

                @endif

                @if($invoice->tenant->gst_number)

                    <div>
                        GST:
                        {{ $invoice->tenant->gst_number }}
                    </div>

                @endif

            @else

                <div>
                    Tenant information unavailable.
                </div>

            @endif

        </div>

    </div>


    {{-- ITEMS --}}

    <div class="section">

        <table>

            <thead>

                <tr>

                    <th>
                        #
                    </th>

                    <th>
                        Description
                    </th>

                    <th>
                        Charge Type
                    </th>

                    <th class="text-right">
                        Taxable Amount
                    </th>

                    <th class="text-right">
                        Tax
                    </th>

                    <th class="text-right">
                        Total
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach($invoice->items as $index => $item)

                    <tr>

                        <td>
                            {{ $index + 1 }}
                        </td>

                        <td>
                            {{ $item->item_description }}
                        </td>

                        <td>

                            {{ $item->chargeType->charge_name
                                ?? '—'
                            }}

                        </td>

                        <td class="text-right">

                            ${{ number_format(
                                (float) $item->taxable_amount,
                                2
                            ) }}

                        </td>

                        <td class="text-right">

                            ${{ number_format(
                                (float) $item->tax_amount,
                                2
                            ) }}

                        </td>

                        <td class="text-right">

                            ${{ number_format(
                                (float) $item->total_amount,
                                2
                            ) }}

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>


    {{-- SUMMARY --}}

    <table class="summary">

        <tr>

            <td>
                Total Invoice Amount
            </td>

            <td class="text-right">

                ${{ number_format(
                    (float) $invoice->total_amount,
                    2
                ) }}

            </td>

        </tr>


        <tr>

            <td>
                Paid Amount
            </td>

            <td class="text-right">

                ${{ number_format(
                    (float) $invoice->paid_amount,
                    2
                ) }}

            </td>

        </tr>


        <tr class="total">

            <td>
                Balance Amount
            </td>

            <td class="text-right">

                ${{ number_format(
                    (float) $invoice->balance_amount,
                    2
                ) }}

            </td>

        </tr>

    </table>


    {{-- FOOTER --}}

    <div class="footer">

        This is a system generated invoice.

    </div>

</div>

</body>
</html>