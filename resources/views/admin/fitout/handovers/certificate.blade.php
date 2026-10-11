<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        Handover Certificate -
        {{ $handover->handover_number }}
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            background: #f3f4f6;
            font-family: Arial, Helvetica, sans-serif;
            color: #222;
        }

        .certificate {
            max-width: 1000px;
            margin: auto;
            background: #fff;
            padding: 45px;
            border: 1px solid #ddd;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0 0 8px;
            font-size: 26px;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 8px 0 0;
            color: #666;
        }

        .section {
            margin-top: 25px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            background: #f1f1f1;
            padding: 10px;
            border-left: 4px solid #333;
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            border: 1px solid #ddd;
            padding: 9px 10px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f7f7f7;
        }

        .label {
            width: 25%;
            font-weight: bold;
            background: #fafafa;
        }

        .status {
            display: inline-block;
            padding: 5px 12px;
            border: 1px solid #333;
            font-weight: bold;
        }

        .declaration {
            margin-top: 25px;
            padding: 15px;
            border: 1px solid #ccc;
            line-height: 1.6;
        }

        .signature-table {
            margin-top: 45px;
        }

        .signature-box {
            height: 100px;
            vertical-align: bottom;
            padding-top: 70px;
        }

        .footer {
            margin-top: 35px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 11px;
            color: #666;
            text-align: center;
        }

        .print-actions {
            max-width: 1000px;
            margin: 0 auto 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .print-actions button,
        .print-actions a {
            padding: 9px 16px;
            border: 1px solid #333;
            background: #fff;
            text-decoration: none;
            color: #222;
            cursor: pointer;
        }

        @media print {

            body {
                background: #fff;
                padding: 0;
            }

            .certificate {
                border: none;
                padding: 20px;
                max-width: none;
            }

            .print-actions {
                display: none;
            }

        }

    </style>

</head>

<body>


<div class="print-actions">

    <a
        href="{{ route(
            'admin.fitout.handovers.show',
            $handover->id
        ) }}"
    >
        Back
    </a>

    <button onclick="window.print()">
        Print / Save PDF
    </button>

</div>


<div class="certificate">


    {{-- ================================================= --}}
    {{-- HEADER --}}
    {{-- ================================================= --}}

    <div class="header">

        <h1>
            MALL MANAGEMENT
        </h1>

        <h2>
            FIT-OUT HANDOVER CERTIFICATE
        </h2>

        <p>
            Handover No:
            <strong>
                {{ $handover->handover_number }}
            </strong>
        </p>

    </div>


    {{-- ================================================= --}}
    {{-- HANDOVER INFORMATION --}}
    {{-- ================================================= --}}

    <div class="section">

        <div class="section-title">
            Handover Information
        </div>

        <table>

            <tr>

                <td class="label">
                    Handover Number
                </td>

                <td>
                    {{ $handover->handover_number }}
                </td>

                <td class="label">
                    Handover Date
                </td>

                <td>

                    {{
                        $handover->handover_date
                            ? $handover->handover_date
                                ->format('d M Y')
                            : '-'
                    }}

                </td>

            </tr>


            <tr>

                <td class="label">
                    Handover Type
                </td>

                <td>
                    {{ $handover->handover_type }}
                </td>

                <td class="label">
                    Status
                </td>

                <td>

                    <span class="status">
                        {{ $handover->status }}
                    </span>

                </td>

            </tr>

        </table>

    </div>


    {{-- ================================================= --}}
    {{-- UNIT / TENANT --}}
    {{-- ================================================= --}}

    <div class="section">

        <div class="section-title">
            Unit & Tenant Details
        </div>

        <table>

            <tr>

                <td class="label">
                    Fit-Out Request
                </td>

                <td>

                    {{
                        $handover
                            ->fitoutRequest
                            ->request_no
                        ?? '-'
                    }}

                </td>

                <td class="label">
                    Unit
                </td>

                <td>

                    {{
                        $handover
                            ->unit
                            ->unit_no
                        ?? '-'
                    }}

                </td>

            </tr>


            <tr>

                <td class="label">
                    Tenant
                </td>

                <td>

                    {{
                        $handover->tenant->company_name
                        ??
                        $handover->tenant->company_name
                        ??
                        $handover->tenant->company_name
                        ??
                        '-'
                    }}

                </td>

                <td class="label">
                    Contractor
                </td>

                <td>

                    {{
                        $handover->contractor->contractor_name
                        ??
                        $handover->contractor->contractor_name
                        ??
                        $handover->contractor->contractor_name
                        ??
                        '-'
                    }}

                </td>

            </tr>

        </table>

    </div>


    {{-- ================================================= --}}
    {{-- INSPECTION --}}
    {{-- ================================================= --}}

    <div class="section">

        <div class="section-title">
            Final Inspection
        </div>

        <table>

            <tr>

                <td class="label">
                    Inspection Number
                </td>

                <td>

                    {{
                        $handover
                            ->finalInspection
                            ->inspection_number
                        ?? '-'
                    }}

                </td>

                <td class="label">
                    Inspection Type
                </td>

                <td>

                    {{
                        $handover
                            ->finalInspection
                            ->inspection_type
                        ?? '-'
                    }}

                </td>

            </tr>


            <tr>

                <td class="label">
                    Inspection Date
                </td>

                <td>

                    {{
                        $handover
                            ->finalInspection
                            ?->inspection_date
                            ?->format('d M Y')
                        ?? '-'
                    }}

                </td>

                <td class="label">
                    Result
                </td>

                <td>

                    {{
                        $handover
                            ->finalInspection
                            ->result
                        ?? '-'
                    }}

                </td>

            </tr>

        </table>

    </div>


    {{-- ================================================= --}}
    {{-- UNIT CONDITION --}}
    {{-- ================================================= --}}

    <div class="section">

        <div class="section-title">
            Unit Condition & Access
        </div>

        <table>

            <tr>

                <td class="label">
                    Unit Condition
                </td>

                <td>
                    {{ $handover->unit_condition ?? '-' }}
                </td>

                <td class="label">
                    Key Count
                </td>

                <td>
                    {{ $handover->key_count ?? 0 }}
                </td>

            </tr>


            <tr>

                <td class="label">
                    Access Card Count
                </td>

                <td>
                    {{ $handover->access_card_count ?? 0 }}
                </td>

                <td class="label">
                    Handover Status
                </td>

                <td>
                    {{ $handover->status }}
                </td>

            </tr>

        </table>

    </div>


    {{-- ================================================= --}}
    {{-- METERS --}}
    {{-- ================================================= --}}

    <div class="section">

        <div class="section-title">
            Utility Meter Details
        </div>

        <table>

            <tr>

                <th>
                    Utility
                </th>

                <th>
                    Meter Number
                </th>

                <th>
                    Reading
                </th>

            </tr>


            <tr>

                <td>
                    Electricity
                </td>

                <td>
                    {{ $handover->electricity_meter_no ?? '-' }}
                </td>

                <td>
                    {{ $handover->electricity_meter_reading ?? '-' }}
                </td>

            </tr>


            <tr>

                <td>
                    Water
                </td>

                <td>
                    {{ $handover->water_meter_no ?? '-' }}
                </td>

                <td>
                    {{ $handover->water_meter_reading ?? '-' }}
                </td>

            </tr>

        </table>

    </div>


    {{-- ================================================= --}}
    {{-- REMARKS --}}
    {{-- ================================================= --}}

    @if($handover->remarks)

        <div class="section">

            <div class="section-title">
                Remarks
            </div>

            <div class="declaration">

                {!! nl2br(e($handover->remarks)) !!}

            </div>

        </div>

    @endif


    {{-- ================================================= --}}
    {{-- DECLARATION --}}
    {{-- ================================================= --}}

    <div class="section">

        <div class="section-title">
            Handover Declaration
        </div>

        <div class="declaration">

            The above-mentioned premises has been inspected and
            handed over in accordance with the fit-out handover
            process. The relevant parties acknowledge the condition
            of the premises, meter readings, keys and access cards
            recorded in this certificate.

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- SIGNATURES --}}
    {{-- ================================================= --}}

    <table class="signature-table">

        <tr>

            <th>
                Tenant
            </th>

            <th>
                Contractor
            </th>

            <th>
                Mall Management
            </th>

        </tr>


        <tr>

            <td class="signature-box">

                <strong>
                    {{
                        $handover
                            ->tenantAcceptedBy
                            ->name
                        ?? '-'
                    }}
                </strong>

                <br>

                @if($handover->tenant_accepted_at)

                    Accepted:
                    {{
                        $handover
                            ->tenant_accepted_at
                            ->format('d M Y H:i')
                    }}

                @endif

            </td>


            <td class="signature-box">

                <strong>
                    {{
                        $handover
                            ->contractorAcceptedBy
                            ->name
                        ?? '-'
                    }}
                </strong>

                <br>

                @if($handover->contractor_accepted_at)

                    Accepted:
                    {{
                        $handover
                            ->contractor_accepted_at
                            ->format('d M Y H:i')
                    }}

                @endif

            </td>


            <td class="signature-box">

                <strong>
                    {{
                        $handover
                            ->mallApprovedBy
                            ->name
                        ?? '-'
                    }}
                </strong>

                <br>

                @if($handover->mall_approved_at)

                    Approved:
                    {{
                        $handover
                            ->mall_approved_at
                            ->format('d M Y H:i')
                    }}

                @endif

            </td>

        </tr>

    </table>


    {{-- ================================================= --}}
    {{-- FOOTER --}}
    {{-- ================================================= --}}

    <div class="footer">

        Handover Number:
        {{ $handover->handover_number }}

        &nbsp; | &nbsp;

        Generated:
        {{ now()->format('d M Y H:i') }}

    </div>

</div>

</body>

</html>