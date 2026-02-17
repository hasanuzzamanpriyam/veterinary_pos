@extends('layouts.print') <!-- Extending the print layout -->

@section('page-title')
    Cash Maintenance Report
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
                    <td class="left" width="50%">
                        <h2 style="font-size: 24px">Firoz Enterprise</h2>
                        <p>Parila Bazar, Paba, Rajshahi</p>
                        <p>Mobile: 01712 203045</p>
                    </td>

                    <td class="right" width="50%">
                        <h2>Cash Maintenance Report</h2>
                        @if ($start_date && $end_date)
                            <p style="font-size: 16px; font-weight: 700">{{ $start_date }} To {{ $end_date }}</p>
                        @endif
                    </td>
                </tr>
            </table>

        </div>
        @if (count($cash_data) > 0)
            <table class="printable table table-bordered mb-0 w-100" cellspacing="0" width="100%">
                <thead>
                    <tr>
                    <th class="all">SL</th>
                    <th class="all" style="width: 90px">Date</th>
                    <th class="all" style="width: 120px">Dokan Cash (prev)</th>
                    <th class="all">Collection</th>
                    <th class="all">Payment</th>
                    <th class="all">Expense</th>
                    <th class="all">Home Cash</th>
                    <th class="all">Short Cash</th>
                    <th class="all">Dokan Cash</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $prev_balance = 0;
                        $summary = [];
                    @endphp
                    @foreach ($cash_data as $data)
                        @php
                            $summary['collection'] = $summary['collection'] ?? 0;
                            $summary['collection'] += $data->collection;
                            $summary['payment'] = $summary['payment'] ?? 0;
                            $summary['payment'] += $data->payment;
                            $summary['expense'] = $summary['expense'] ?? 0;
                            $summary['expense'] += $data->expense;
                            $summary['home_cash'] = $summary['home_cash'] ?? 0;
                            $summary['home_cash'] += $data->home_cash;
                            $summary['short_cash'] = $summary['short_cash'] ?? 0;
                            $summary['short_cash'] += $data->short_cash;
                            if ($loop->last){
                                $summary['dokan_cash'] = $summary['dokan_cash'] ?? 0;
                                $summary['dokan_cash'] = $data->dokan_cash;
                            }

                            $currentPage = method_exists($cash_data, 'currentPage') ? $cash_data->currentPage() : 1;
                            $perPage = method_exists($cash_data, 'perPage') ? $cash_data->perPage() : $cash_data->count(); // Fallback to total count
                            $iteration = ($currentPage - 1) * $perPage + $loop->iteration;
                        @endphp
                    <tr>
                        <td class="text-center">{{$iteration}}</td>
                        <td class="text-center">{{date('d-m-Y',strtotime($data->date))}}</td>
                        <td class="text-right">{{$data->prev_balance ? number_format($data->prev_balance) . '/=' : ''}}</td>
                        <td class="text-right">{{$data->collection ? number_format($data->collection) . '/=' : ''}}</td>
                        <td class="text-right">{{$data->payment ? number_format($data->payment) . '/=' : ''}}</td>
                        <td class="text-right">{{$data->expense ? number_format($data->expense) . '/=' : ''}}</td>
                        <td class="text-right">{{$data->home_cash ? number_format($data->home_cash) . '/=' : ''}}</td>
                        <td class="text-right">{{$data->short_cash ? number_format($data->short_cash) . '/=' : ''}}</td>
                        <td class="text-right">{{$data->dokan_cash ? number_format($data->dokan_cash) . '/=' : ''}}</td>
                    </tr>
                    @endforeach
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-right">{{isset($summary['collection']) ? number_format($summary['collection']) : 0 }}/=</th>
                            <th class="text-right">{{isset($summary['payment']) ? number_format($summary['payment']) : 0 }}/=</th>
                            <th class="text-right">{{isset($summary['expense']) ? number_format($summary['expense']) : 0 }}/=</th>
                            <th class="text-right">{{isset($summary['home_cash']) ? number_format($summary['home_cash']) : 0 }}/=</th>
                            <th class="text-right">{{isset($summary['short_cash']) ? number_format($summary['short_cash']) : 0 }}/=</th>
                            <th class="text-right">{{isset($summary['dokan_cash']) ? number_format($summary['dokan_cash']) : 0 }}/=</th>
                        </tr>
                    </tfoot>
                </tbody>
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
                        <p>Authorized</p>
                    </div>
                </td>
                <td width="25%"></td>
                <td width="25%"></td>
                <td width="25%" style="text-align: center;">
                    <div>
                        <hr>
                        <p>Manager</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
@endsection
