<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payroll Calculator</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600&family=Inter:wght@400;600&family=IBM+Plex+Mono:wght@400;600&display=swap" rel="stylesheet">

    <style>

        :root{
            --ink:#101b2d;
            --ink-soft:#4b5768;
            --paper:#f3f1ea;
            --card:#ffffff;
            --line:#e3e0d5;
            --earn:#1f6d4a;
            --earn-bg:#eaf4ee;
            --deduct:#a1401f;
            --deduct-bg:#fbeee7;
            --accent:#b08a2e;
            --radius:12px;
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        html,body{
            height:100%;
        }

        body{
            background:var(--paper);
            font-family:'Inter',Arial,Helvetica,sans-serif;
            color:var(--ink);
            padding:18px 20px;
            display:flex;
            flex-direction:column;
        }

        .container{
            max-width:1180px;
            width:100%;
            margin:0 auto;
            flex:1;
            display:flex;
            flex-direction:column;
        }

        h1.page-title{
            font-family:'Fraunces',serif;
            font-weight:600;
            font-size:24px;
            margin-bottom:14px;
        }

        .layout{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:18px;
            flex:1;
        }

        @media (max-width:860px){
            .layout{
                grid-template-columns:1fr;
            }
        }

        .card{
            background:var(--card);
            border:1px solid var(--line);
            border-radius:var(--radius);
            padding:20px 22px;
            box-shadow:0 1px 2px rgba(16,27,45,.04);
        }

        .card h2{
            font-family:'Fraunces',serif;
            font-weight:600;
            font-size:17px;
            margin-bottom:14px;
        }

        .grid{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:12px;
        }

        .form-group{
            display:flex;
            flex-direction:column;
        }

        .form-group.full{
            grid-column:1 / -1;
        }

        label{
            margin-bottom:5px;
            font-weight:600;
            font-size:13px;
            color:var(--ink-soft);
        }

        label .req{
            color:var(--deduct);
        }

        input{
            padding:9px 11px;
            border:2px solid var(--line);
            border-radius:8px;
            font-size:14px;
            font-family:'IBM Plex Mono',monospace;
            color:var(--ink);
            background:#fbfaf7;
        }

        input:focus{
            outline:none;
            border-color:var(--ink);
            background:#fff;
            box-shadow:0 0 0 3px rgba(16,27,45,.08);
        }

        button{
            width:100%;
            margin-top:16px;
            padding:11px 18px;
            border:none;
            border-radius:8px;
            background:var(--ink);
            color:#fff;
            font-size:14px;
            font-weight:600;
            cursor:pointer;
        }

        button:hover{
            background:#1c2c46;
        }

        button:focus-visible{
            outline:3px solid var(--accent);
            outline-offset:2px;
        }

        /* ---- payslip ---- */

        .perforation{
            border:none;
            border-top:2px dashed var(--line);
            margin:12px 0;
        }

        table{
            width:100%;
            border-collapse:collapse;
            font-family:'IBM Plex Mono',monospace;
            font-size:13px;
        }

        table .section-label td{
            padding:6px 4px 4px;
            font-family:'Inter',sans-serif;
            font-size:11px;
            font-weight:600;
            letter-spacing:.1em;
            text-transform:uppercase;
            border-bottom:2px solid var(--ink);
        }

        table .section-label.earn td{ color:var(--earn); }
        table .section-label.deduct td{ color:var(--deduct); }

        table td{
            padding:5px 4px;
            border-bottom:1px solid var(--line);
        }

        table td:last-child{
            text-align:right;
        }

        tr.subtotal td{
            font-weight:600;
            background:var(--earn-bg);
        }

        tr.subtotal.deduct td{
            background:var(--deduct-bg);
        }

        .deduction-amt{
            color:var(--deduct);
        }

        .earning-amt{
            color:var(--earn);
        }

        tr.net td{
            padding-top:10px;
            padding-bottom:10px;
            border-top:3px double var(--ink);
            border-bottom:none;
        }

        tr.net td:first-child{
            font-size:13px;
            font-weight:600;
            letter-spacing:.04em;
            text-transform:uppercase;
            font-family:'Inter',sans-serif;
            color:var(--ink-soft);
            vertical-align:bottom;
        }

        tr.net td:last-child{
            font-family:'Fraunces',serif;
            font-size:22px;
            font-weight:600;
        }

        .empty-state{
            padding:40px 20px;
            text-align:center;
            color:var(--ink-soft);
        }

        .empty-state h3{
            font-family:'Fraunces',serif;
            font-size:16px;
            color:var(--ink);
            margin-bottom:6px;
        }

        .empty-state p{
            font-size:13px;
            max-width:260px;
            margin:0 auto;
        }

        footer{
            margin-top:14px;
            padding-top:12px;
            border-top:1px dashed var(--line);
            text-align:center;
            font-family:'IBM Plex Mono',monospace;
            font-size:11px;
            letter-spacing:.06em;
            text-transform:uppercase;
            color:var(--ink-soft);
        }

    </style>
</head>

<body>

<div class="container">

    <h1 class="page-title">Payroll Calculator</h1>

    <div class="layout">

        <div class="card form-card">
            <h2>Pay period details</h2>

            <form action="/payroll" method="POST">

                @csrf

                <div class="grid">

                    <div class="form-group">
                        <label>Basic Salary <span class="req">*</span></label>
                        <input type="number"
                               step="0.01"
                               name="basic_salary"
                               required
                               value="{{ old('basic_salary') }}">
                    </div>

                    <div class="form-group">
                        <label>Allowances</label>
                        <input type="number"
                               step="0.01"
                               name="allowances"
                               value="{{ old('allowances',0) }}">
                    </div>

                    <div class="form-group">
                        <label>Overtime Hours</label>
                        <input type="number"
                               step="0.01"
                               name="overtime_hours"
                               value="{{ old('overtime_hours',0) }}">
                    </div>

                    <div class="form-group">
                        <label>Overtime Rate</label>
                        <input type="number"
                               step="0.01"
                               name="overtime_rate"
                               value="{{ old('overtime_rate',1.5) }}">
                    </div>

                    <div class="form-group">
                        <label>Other Earnings</label>
                        <input type="number"
                               step="0.01"
                               name="other_earnings"
                               value="{{ old('other_earnings',0) }}">
                    </div>

                    <div class="form-group">
                        <label>Insurance Salary <span class="req">*</span></label>
                        <input type="number"
                               step="0.01"
                               name="insurance_salary"
                               required
                               value="{{ old('insurance_salary') }}">
                    </div>

                    <div class="form-group full">
                        <label>Other Deductions</label>
                        <input type="number"
                               step="0.01"
                               name="other_deductions"
                               value="{{ old('other_deductions',0) }}">
                    </div>

                </div>

                <button type="submit">Calculate Payroll</button>

            </form>
        </div>


        <div class="card result-card">

            <h2>Pay slip</h2>

            @if(isset($result) && $result)

                {{--
                    Net salary is computed here directly from gross and total
                    deductions, rather than trusting a possibly-missing
                    $result['netSalary'] key from the controller. If the
                    controller does supply a correct netSalary, it's used;
                    otherwise it falls back to the calculated value so the
                    figure is never blank.
                --}}
                @php
                    $grossSalary = $result['grossSalary']
                        ?? (($result['basicSalary'] ?? 0)
                            + ($result['allowances'] ?? 0)
                            + ($result['overtime'] ?? 0)
                            + ($result['otherEarnings'] ?? 0));

                    $totalDeductions =
                        ($result['employeeInsurance'] ?? 0)
                        + ($result['tax'] ?? 0)
                        + ($result['martyrs_Fund'] ?? 0)
                        + ($result['otherDeductions'] ?? 0);

                    $netSalary = $result['netSalary'] ?? ($grossSalary - $totalDeductions);
                @endphp

                <hr class="perforation">

                <table>

                    <tr class="section-label earn">
                        <td colspan="2">Earnings</td>
                    </tr>

                    <tr>
                        <td>Basic Salary</td>
                        <td>{{ number_format($result['basicSalary'] ?? 0,2) }}</td>
                    </tr>

                    <tr>
                        <td>Allowances</td>
                        <td>{{ number_format($result['allowances'] ?? 0,2) }}</td>
                    </tr>

                    <tr>
                        <td>Overtime</td>
                        <td>{{ number_format($result['overtime'] ?? 0,2) }}</td>
                    </tr>

                    <tr>
                        <td>Other Earnings</td>
                        <td>{{ number_format($result['otherEarnings'] ?? 0,2) }}</td>
                    </tr>

                    <tr class="subtotal">
                        <td>Gross Salary</td>
                        <td class="earning-amt">{{ number_format($grossSalary,2) }}</td>
                    </tr>

                    <tr class="section-label deduct">
                        <td colspan="2">Deductions</td>
                    </tr>

                    <tr>
                        <td>Employee Insurance</td>
                        <td class="deduction-amt">({{ number_format($result['employeeInsurance'] ?? 0,2) }})</td>
                    </tr>

                    <tr>
                        <td>Income Tax</td>
                        <td class="deduction-amt">({{ number_format($result['tax'] ?? 0,2) }})</td>
                    </tr>

                    <tr>
                        <td>Martyrs Fund</td>
                        <td class="deduction-amt">({{ number_format($result['martyrs_Fund'] ?? 0,2) }})</td>
                    </tr>

                    <tr>
                        <td>Other Deductions</td>
                        <td class="deduction-amt">({{ number_format($result['otherDeductions'] ?? 0,2) }})</td>
                    </tr>

                    <tr class="subtotal deduct">
                        <td>Total Deductions</td>
                        <td class="deduction-amt">({{ number_format($totalDeductions,2) }})</td>
                    </tr>

                    <tr class="net">
                        <td>Net Salary</td>
                        <td>{{ number_format($netSalary,2) }} <span style="font-size:13px;">EGP</span></td>
                    </tr>

                </table>

            @else

                <div class="empty-state">
                    <h3>Nothing calculated yet</h3>
                    <p>Fill in the pay period details and select <strong>Calculate Payroll</strong> to see the breakdown here.</p>
                </div>

            @endif

        </div>

    </div>

    <footer>Powered by Abdelrahman Haroun</footer>

</div>

</body>
</html>
