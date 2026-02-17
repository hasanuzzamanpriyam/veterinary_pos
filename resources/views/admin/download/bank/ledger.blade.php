@extends('layouts.print') <!-- Extending the print layout -->

@section('page-title')
    Ledger - {{ $bank->name }} - {{ $bank->branch }} - {{ $bank->account_no }} - {{ $bank->ac_mode }}
@endsection

@push('style')
    <style>
        @page {
            margin: 50px 30px;
        }

        body {
            font-size: 9px;
        }

        .supplier-area {
            margin-top: 20px;
        }

        .header {
            margin-bottom: 20px;
            width: 100%;
        }

        .header td {
            font-size: 12px;
            padding: 5px 2px;
        }

        .header td>* {
            margin: 0;
            line-height: 1.2;
        }

        .header td.left {
            text-align: left;
        }

        .header td.middle {
            text-align: center;
            vertical-align: top;
        }

        .header td.right {
            text-align: right;
        }

        .header td.left h2,
        .header td.middle h2,
        .header td.right h2 {
            font-size: 20px;
        }

        .header td.left p,
        .header td.right p {
            font-size: 16px;
        }

        .print-button-area {
            text-align: center;
        }

        .printable {
            width: 100%;
        }

        .printable thead input[type="checkbox"],
        .printable thead label {
            cursor: pointer;
        }

        .printable label {
            background: unset;
            color: unset;
            text-align: center !important;
            margin: 0;
        }

        .printable td,
        .printable th {
            padding: 2px 2px;
            border-right: 1px solid #8d8d8d;
            border-bottom: 1px solid #8d8d8d;
        }

        .printable {
            border-top: 1px solid #8d8d8d;
            border-left: 1px solid #8d8d8d;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .mb-0 {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
            line-height: 1;
        }

        .d-block {
            display: block;
        }

        .text-nowrap {
            white-space: nowrap;
        }

        .footer {
            margin-top: 100px;
        }

        @media print {
            @page {
                size: auto;
                /* Set the page size to landscape */
            }

            body {
                width: 100%;
            }

            .supplier-area {
                margin-top: 0;
            }
        }
    </style>
@endpush

@section('main-content')
    <div class="supplier-area">
        <div class="header">
            <table class="header mb-0 w-100" valign="top" cellspacing="0">
                <tr class="top-header">
                    <td colspan="3" class="text-center">
                        <p style="font-size: 16px">বিসমিল্লাহির রাহমানির রাহিম</p>
                    </td>
                </tr>
                <tr>
                    <td class="left" width="33%">
                        <h2 style="font-size: 24px">Firoz Enterprise</h2>
                        <p>Parila Bazar, Paba, Rajshahi</p>
                        <p>Mobile: 01712 203045</p>
                    </td>
                    <td class="middle" width="33%">
                        <h2>Bank Ledger</h2>
                        @if ($start_date && $end_date)
                            <p style="font-size: 16px; font-weight: 700">{{ $start_date }} To {{ $end_date }}</p>
                        @endif
                        @if (!empty($search_query))
                            <p>Search Result: <strong>{{ $search_query }}</strong></p>
                        @endif
                    </td>
                    <td class="right" width="33%">
                        <h2>{{ $bank->name }}</h2>
                        <p>{{ $bank->branch }}</p>
                        <p>A/C: {{ $bank->account_no }} - {{ $bank->ac_mode }}</p>
                    </td>
                </tr>
            </table>

        </div>
        @if (count($bank_statements) > 0)
            <table class="printable table table-bordered mb-0 w-100" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="all">SL</th>
                        <th class="all">Date</th>
                        <th class="text-left">Description</th>
                        <th class="all">Deposit</th>
                        <th class="all">Withdraw</th>
                        <th class="all">Balance</th>
                        {{-- <th class="all">Action</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_deposit = 0;
                        $total_withdraw = 0;
                        $transactions = [];
                        $balance = 0;
                    @endphp
                    @foreach ($bank_statements as $transaction)
                        @php
                            $deposit = in_array($transaction->type, ['deposit', 'opening', 'others'])
                                ? $transaction->amount
                                : 0;
                            $total_deposit += $deposit;
                            $withdraw = $transaction->type == 'withdraw' ? $transaction->amount : 0;
                            $total_withdraw += $withdraw;
                            $balance += $transaction->type == 'prev' ? $transaction->amount : $deposit - $withdraw;

                            $currentPage = method_exists($bank_statements, 'currentPage')
                                ? $bank_statements->currentPage()
                                : 1;
                            $perPage = method_exists($bank_statements, 'perPage')
                                ? $bank_statements->perPage()
                                : $bank_statements->count(); // Fallback to total count
                            $iteration = ($currentPage - 1) * $perPage + $loop->iteration;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $iteration }}</td>
                            @if ($transaction->type == 'prev')
                                <td>-</td>
                                <td class="text-left">{{ $transaction->remarks }}</td>
                                <td class="text-right">-</td>
                                <td class="text-right">-</td>
                            @else
                                <td>{{ date('d-m-Y', strtotime($transaction->date)) }}</td>
                                <td class="text-left">
                                    {{ ucfirst($transaction->type) }}{{ $transaction->payment_method ? ' - ' . ucfirst($transaction->payment_method) : '' }}{{ $transaction->remarks ? ' - ' . $transaction->remarks : '' }}
                                </td>
                                <td class="text-right">
                                    {{ in_array($transaction->type, ['deposit', 'opening', 'others']) && $transaction->amount > 0 ? number_format($transaction->amount) . '/=' : '' }}
                                </td>
                                <td class="text-right">
                                    {{ $transaction->type == 'withdraw' && $transaction->amount > 0 ? number_format($transaction->amount) . '/=' : '' }}
                                </td>
                            @endif
                            <td class="text-right">{{ formatAmount($balance ?? 0) }}/=</td>
                            {{-- <td>
                             @if ($transaction->id)
                                 <div class="btn-group">
                                     <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                         data-toggle="dropdown">
                                         <i class="fa fa-list"></i> <span class="caret"></span></button>
                                     <ul class="dropdown-menu" role="menu">
                                         <li>
                                             <a href="{{route('transaction.delete', $transaction->id)}}" class="btn btn-danger" id="delete"><i class="fa fa-trash" ></i></a>
                                         </li>
                                     </ul>
                                 </div>
                             @endif
                         </td> --}}
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right"><b>{{ formatAmount($total_deposit) }}/=</b></td>
                        <td class="text-right"><b>{{ formatAmount($total_withdraw) }}/=</b></td>
                        <td class="text-right"><b>{{ formatAmount($balance) }}/=</b></td>
                    </tr>
                </tfoot>
            </table>
        @endif
    </div>
    <div class="footer">
        <table width="100%"
            style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
            <tr>
                <td width="25%" style="text-align: center">
                    <div>
                        <hr>
                        <p>Authorized Signature</p>
                    </div>
                </td>
                <td width="25%"></td>
                <td width="25%"></td>
                <td width="25%" style="text-align: center;">
                    <div>
                        <hr>
                        <p>Supplier Signature</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
@endsection
