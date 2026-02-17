@extends('layouts.print')

@section('page-title')
Ledger - {{ $supplier->company_name }} from {{ $start_date }} to {{ $end_date }}
@endsection

@push('style')
<style>
    @page { margin: 50px 30px; }
    body { font-size: 9px; }
    .printable td, .printable th {
        padding: 2px;
        border: 1px solid #8d8d8d;
    }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .text-left { text-align: left; }
    .mb-0 { margin: 0; line-height: 1; }
</style>
@endpush

@section('main-content')
<div class="supplier-area">

    {{-- HEADER --}}
    <table width="100%" cellspacing="0">
        <tr>
            <td width="33%">
                <h2>Firoz Enterprise</h2>
                <p>Parila Bazar, Paba, Rajshahi</p>
            </td>
            <td width="33%" class="text-center">
                <h2>Supplier Ledger</h2>
                <p>{{ $start_date }} to {{ $end_date }}</p>
            </td>
            <td width="33%" class="text-right">
                <h2>{{ $supplier->company_name }}</h2>
                <p>{{ $supplier->mobile }}</p>
            </td>
        </tr>
    </table>

    {{-- TABLE --}}
    <table class="printable" width="100%" cellspacing="0">
        <thead>
            <tr class="text-center">
                <th>Date</th>
                <th>Invoice</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Weight</th>
                <th>Purchase</th>
                <th>Payment</th>
                <th>Balance</th>
            </tr>
        </thead>

        <tbody>
        @php
            $balance = 0;
            $totalQty = [];
            $totalWeight = 0;
        @endphp

        @foreach($supplier_ledger_info as $report)

            {{-- ================= PURCHASE ================= --}}
            @if($report->type === 'purchase')
                @php
                    // ✅ CORRECT FILTER
                    $filtered_products = $products[$report->id] ?? collect();

                    $totalPrice = $report->total_price - $report->price_discount;
                    $payment    = $report->payment + $report->vat + $report->carring + $report->other_charge;
                    $balance   += ($totalPrice - $payment);

                    $rowWeight = 0;
                @endphp

                <tr>
                    <td class="text-center">{{ date('d-m-Y', strtotime($report->date)) }}</td>
                    <td class="text-center">{{ $report->id }}</td>

                    {{-- DESCRIPTION --}}
                    <td>
                        @foreach($filtered_products as $product)
                            @php
                                $qty  = $product->quantity - $product->discount_qty;
                                $type = $product->product->type;

                                $totalQty[$type] = ($totalQty[$type] ?? 0) + $qty;
                                $rowWeight += $product->product->size->name * $qty;
                            @endphp

                            <p class="mb-0">
                                {{ $product->product_code }} -
                                {{ $product->product_name }}
                                ({{ optional($product->product->size)->description }}) -
                                {{ $qty }}
                                {{ trans_choice('labels.' . strtolower($type), $qty) }}
                                @ {{ $product->unit_price }}/=
                                {{ $product->total_price }}/=
                            </p>
                        @endforeach
                    </td>

                    {{-- QTY --}}
                    <td class="text-center">
                        @foreach($totalQty as $k => $v)
                            <div>{{ $v }} {{ trans_choice('labels.' . strtolower($k), $v) }}</div>
                        @endforeach
                    </td>

                    {{-- WEIGHT --}}
                    <td class="text-center">
                        {{ number_format($rowWeight / 1000, 3) }} MT
                    </td>

                    <td class="text-right">{{ number_format($totalPrice) }}/=</td>
                    <td class="text-right">{{ number_format($payment) }}/=</td>
                    <td class="text-right">{{ number_format($balance) }}/=</td>
                </tr>

                @php $totalWeight += $rowWeight; @endphp
            @endif


            {{-- ================= PAYMENT ================= --}}
            @if($report->type === 'payment')
                @php $balance -= $report->payment; @endphp
                <tr>
                    <td class="text-center">{{ date('d-m-Y', strtotime($report->date)) }}</td>
                    <td class="text-center">{{ $report->id }}</td>
                    <td>{{ $report->payment_by }}</td>
                    <td></td><td></td><td></td>
                    <td class="text-right">{{ number_format($report->payment) }}/=</td>
                    <td class="text-right">{{ number_format($balance) }}/=</td>
                </tr>
            @endif

        @endforeach
        </tbody>

        {{-- FOOTER --}}
        <tfoot>
            <tr>
                <th colspan="3">Total</th>
                <th>
                    @foreach($totalQty as $k => $v)
                        <div>{{ $v }} {{ trans_choice('labels.' . strtolower($k), $v) }}</div>
                    @endforeach
                </th>
                <th>{{ number_format($totalWeight / 1000, 3) }} MT</th>
                <th colspan="2"></th>
                <th>{{ number_format($balance) }}/=</th>
            </tr>
        </tfoot>
    </table>

</div>
@endsection
