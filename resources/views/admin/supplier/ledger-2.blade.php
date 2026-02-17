@extends('layouts.admin')

@section('page-title')
Supplier Ledger 2
@endsection

@section('main-content')

@php
$type = 0;
@endphp
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Supplier Ledger 2</h2>
                <h6 class="text-dark mb-0 mr-auto">{{$supplier->company_name}}{{$supplier->address ? ' - '.$supplier->address : ''}}{{$supplier->mobile ? ' - '.$supplier->mobile : ''}}</h6>

                <a href="#" class="ml-3 cursor-pointer" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
        </div>
        <div class="x_content p-3">
            <div class="due-list-area">
                @if(!empty($supplier_ledger_info))
                    <div class="table-responsive">
                        <table id="supplier_ledger" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Date</th>
                                    <th>Invoice</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Dis. Qty</th>
                                    <th>Return Qty</th>
                                    <th>Pur. Qty</th>
                                    <th>Weight</th>
                                    <th>Pur. (TK)</th>
                                    <th>Dis. (TK)</th>
                                    <th>Return (TK)</th>
                                    <th>Total (TK)</th>
                                    <th>VAT</th>
                                    <th>Carring</th>
                                    <th>Others</th>
                                    <th>Payment</th>
                                    <th>Total Payment</th>
                                    <th>Balance</th>
                                    <th>Due Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                    $total_qty = 0;
                                    $total_purchase = 0;
                                    $total_discount = 0;
                                    $total_vat = 0;
                                    $total_carring = 0;
                                    $total_others = 0;
                                    $total_balance = 0;
                                    $total_due = 0;
                                    $total_payment_due = 0;
                                    $total_payment_advance = 0;
                                @endphp
                                @foreach ($supplier_ledger_info as $report)
                                    @if ($report->type == 'other')
                                        <tr data-type="other">
                                            <td style="white-space: nowrap;">{{date('d-m-Y', strtotime($report->date))}}</td>
                                            <td class="text-center">{{$report->id}}</td>
                                            <td class="">{{$report->payment_remarks}}</td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class="text-right">{{$report->payment > 0 ? number_format($report->payment) . '/=' : ''}}</td>
                                            <td class="text-right">{{$report->payment > 0 ? number_format($report->payment) . '/=' : ''}}</td>
                                            <td class="text-right">{{number_format($report->balance)}}/=</td>
                                            <td class="text-right">{{number_format($report->balance)}}/=</td>
                                            <td class="">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2" data-toggle="dropdown">
                                                        <i class="fa fa-list"></i> <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="{{ route('purchase.delete', ['invoice' => $report->id, 'view' => 'other']) }}" class="btn btn-danger" id="delete"><i class="fa fa-trash"></i></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>

                                    @elseif ($report->type == 'purchase')
                                        @php
                                            $filtered_products = $products->where('transaction_id', $report->id);
                                            $qty_summary = [];
                                            $qty_summary_dis = [];
                                            $qty_summary_return = [];
                                            $qty_summary_purchase = [];
                                            $qty_mt = 0;
                                            $total_tk = $report->total_price - $report->price_discount;
                                            $total_payment = $report->payment+ $report->vat + $report->carring + $report->other_charge;
                                            $balance = $total_tk - $total_payment;
                                        @endphp

                                        <tr style="background-color: #e1f3eb" data-type="purchase">
                                            {{-- Date --}}
                                            <td style="white-space: nowrap;">{{date('d-m-Y', strtotime($report->date))}}</td>
                                            {{-- Invoice --}}
                                            <td class="text-center">{{$report->id}}</td>
                                            {{-- Description --}}
                                            <td>
                                                @foreach ($products as $product)
                                                    @php
                                                        $type = $product->product->type;
                                                    @endphp
                                                    @if($product->transaction_id == $report->id)
                                                        <p class="mb-0 text-left">
                                                            {{ $product->product_code}} -
                                                            {{ $product->product_name}} {{'('}}{{ $product->product->size->description}}{{')'}} -
                                                            {{ $product->quantity - $product->discount_qty}} {{ trans_choice('labels.'.$type, ($product->quantity - $product->discount_qty))}}{{' @ '}}{{ $product->unit_price}}/=
                                                            {{ $product->total_price}}/=
                                                        </p>
                                                    @endif
                                                @endforeach
                                            </td>
                                            {{-- Quantity --}}
                                            <td class="text-center">
                                                @foreach ($filtered_products as $product)
                                                    @php
                                                        $product_info = DB::table('products')->where('id', $product->product_id)->first();
                                                        $type = $product_info->type;
                                                        $qty_summary[$type] = $qty_summary[$type] ?? 0;
                                                        $qty_summary[$type] += $product->quantity;
                                                    @endphp
                                                @endforeach
                                                @php
                                                    ksort($qty_summary);
                                                @endphp
                                                <div>
                                                    @if( isset($qty_summary) && count($qty_summary) > 0)
                                                        @foreach ($qty_summary as $key => $value)
                                                            @if( $value > 0)
                                                                {{ $value }} {{trans_choice('labels.'.strtolower($key), $value)}}
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                            {{-- Dis. Qty --}}
                                            <td class="text-center">
                                                @foreach ($filtered_products as $product)
                                                    @php
                                                        $product_info = DB::table('products')->where('id', $product->product_id)->first();
                                                        $type = $product_info->type;
                                                        $qty_summary_dis[$type] = $qty_summary_dis[$type] ?? 0;
                                                        $qty_summary_dis[$type] += $product->discount_qty;
                                                    @endphp
                                                @endforeach
                                                @php
                                                    ksort($qty_summary_dis);
                                                @endphp
                                                <div>
                                                    @if( isset($qty_summary_dis) && count($qty_summary_dis) > 0)
                                                        @foreach ($qty_summary_dis as $key => $value)
                                                            @if( $value > 0)
                                                                {{ $value }} {{trans_choice('labels.'.strtolower($key), $value)}}
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                            {{-- Return Qty --}}
                                            <td class="text-center"></td>
                                            {{-- Pur. Qty --}}
                                            <td class="text-center">
                                                @foreach ($filtered_products as $product)
                                                    @php
                                                        $product_info = DB::table('products')->where('id', $product->product_id)->first();
                                                        $type = $product_info->type;
                                                        $qty_summary_purchase[$type] = $qty_summary_purchase[$type] ?? 0;
                                                        $qty_summary_purchase[$type] += $product->quantity - $product->discount_qty;
                                                        $qty_mt += $product->product->size->name * ($product->quantity - $product->discount_qty);
                                                    @endphp
                                                @endforeach
                                                @php
                                                    ksort($qty_summary_purchase);
                                                @endphp
                                                <div>
                                                    @if( isset($qty_summary_purchase) && count($qty_summary_purchase) > 0)
                                                        @foreach ($qty_summary_purchase as $key => $value)
                                                            @if( $value > 0)
                                                                {{ $value }} {{trans_choice('labels.'.strtolower($key), $value)}}
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                            {{-- Weight --}}
                                            <td>{{$qty_mt / 1000}} MT</td>
                                            {{-- Pur. (TK) --}}
                                            <td class="text-right">{{$report->total_price > 0 ? number_format($report->total_price) . '/=' : ''}}</td>
                                            {{-- Dis. (TK) --}}
                                            <td class="text-right">{{$report->price_discount > 0 ? number_format($report->price_discount) . '/=' : ''}}</td>
                                            {{-- Return (TK) --}}
                                            <td class="text-right"></td>
                                            {{-- Total (TK) --}}
                                            <td class="text-right">{{number_format($total_tk)}}/=</td>
                                            {{-- VAT --}}
                                            <td class="text-right">{{$report->vat > 0 ? number_format($report->vat) . '/=' : ''}}</td>
                                            {{-- Carring --}}
                                            <td class="text-right">{{$report->carring > 0 ? number_format($report->carring) . '/=' : ''}}</td>
                                            {{-- Other Charge --}}
                                            <td class="text-right">{{$report->other_charge > 0 ? number_format($report->other_charge) . '/=' : ''}}</td>
                                            {{-- Payment --}}
                                            <td class="text-right">{{$report->payment > 0 ? number_format($report->payment) . '/=' : ''}}</td>
                                            {{-- Total Payment --}}
                                            <td class="text-right">{{$total_payment > 0 ? number_format($total_payment) . '/=' : '' }}</td>
                                            {{-- Balance --}}
                                            <td class="text-right">{{number_format($balance)}}/=</td>
                                            {{-- Due Amount --}}
                                            <td class="text-right">{{number_format($report->balance)}}/=</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2" data-toggle="dropdown">
                                                        <i class="fa fa-list"></i> <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="{{ route('purchase.delete', $report->id) }}" class="btn btn-danger" id="delete"><i class="fa fa-trash"></i></a></li>
                                                        <li><a href="{{ route('purchase.view', $report->id) }}" class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>

                                    @elseif ($report->type == 'return')
                                        @php
                                            $filtered_products = $products->where('transaction_id', $report->id);
                                            $qty_summary = [];
                                            $qty_summary_dis = [];
                                            $qty_summary_purchase = [];
                                            $qty_mt = 0;
                                            $t_payment = $report->carring + $report->other_charge + $report->payment;
                                            $balance = -$report->total_price - $report->carring - $report->other_charge;
                                        @endphp

                                        <tr style="background-color: #f3e1e1" data-type="return">
                                            {{-- Date --}}
                                            <td style="white-space: nowrap;">{{date('d-m-Y', strtotime($report->date))}}</td>
                                            {{-- ID --}}
                                            <td class="text-center">{{$report->id}}</td>
                                            {{-- Description --}}
                                            <td>
                                                @foreach ($filtered_products as $product)
                                                    @php
                                                        $type = $product->product->type;
                                                    @endphp
                                                    <p class="mb-0 text-left">
                                                        {{ $product->product_code}} -
                                                        {{ $product->product_name}} {{'('}}{{ $product->product->size->description}}{{')'}} -
                                                        {{ $product->quantity}} {{ trans_choice('labels.'.$type, $product->quantity)}}{{' @ '}}{{ $product->unit_price}}/=
                                                        {{ $product->total_price}}/=
                                                    </p>
                                                @endforeach
                                            </td>
                                            {{-- Quantity --}}
                                            <td class="text-center"></td>
                                            {{-- Dis. Qty --}}
                                            <td class="text-center"></td>
                                            {{-- Return Qty --}}
                                            <td class="text-center">
                                                @foreach ($filtered_products as $product)
                                                    @php
                                                        $product_info = DB::table('products')->where('id', $product->product_id)->first();
                                                        $type = $product_info->type;
                                                        $qty_summary[$type] = $qty_summary[$type] ?? 0;
                                                        $qty_summary[$type] += $product->quantity;
                                                        $qty_mt += $product->product->size->name * $product->quantity;
                                                    @endphp
                                                @endforeach
                                                @php
                                                    ksort($qty_summary);
                                                @endphp
                                                <div>
                                                    @if( isset($qty_summary) && count($qty_summary) > 0)
                                                        @foreach ($qty_summary as $key => $value)
                                                            @if( $value > 0)
                                                                {{ $value }} {{trans_choice('labels.'.strtolower($key), $value)}}
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                            {{-- Pur. Qty --}}
                                            <td class="text-center">
                                                <div>
                                                    @if( isset($qty_summary) && count($qty_summary) > 0)
                                                        -
                                                        @foreach ($qty_summary as $key => $value)
                                                            @if( $value > 0)
                                                                {{ $value }} {{trans_choice('labels.'.strtolower($key), $value)}}
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                            {{-- Weight --}}
                                            <td>-{{$qty_mt / 1000}} MT</td>
                                            {{-- Pur. (TK) --}}
                                            <td class="text-right"></td>
                                            {{-- Dis. (TK) --}}
                                            <td class="text-right">{{$report->price_discount > 0 ? number_format($report->price_discount) . '/=' : ''}}</td>
                                            {{-- Return (TK) --}}
                                            <td class="text-right">{{$report->total_price > 0 ? number_format($report->total_price) . '/=' : ''}}</td>
                                            {{-- Total (TK) --}}
                                            <td class="text-right">-{{number_format($report->total_price)}}/=</td>
                                            {{-- VAT --}}
                                            <td class="text-right">{{$report->vat > 0 ? number_format($report->vat) . '/=' : ''}}</td>
                                            {{-- Carring --}}
                                            <td class="text-right">{{$report->carring > 0 ? number_format($report->carring) . '/=' : ''}}</td>
                                            {{-- Other Charge --}}
                                            <td class="text-right">{{$report->other_charge > 0 ? number_format($report->other_charge) . '/=' : ''}}</td>
                                            {{-- Payment --}}
                                            <td class="text-right"></td>
                                            {{-- Total Payment --}}
                                            <td class="text-right">{{$t_payment > 0 ? number_format($t_payment) . '/=' : ''}}</td>
                                            {{-- Balance --}}
                                            <td class="text-right">{{number_format($balance)}}/=</td>
                                            {{-- Due Amount --}}
                                            <td class="text-right">{{number_format($report->balance)}}/=</td>
                                            {{-- Action --}}
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2" data-toggle="dropdown">
                                                        <i class="fa fa-list"></i> <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="{{ route('purchase.delete', ['invoice' => $report->id, 'view' => 'return']) }}" class="btn btn-danger" id="delete"><i class="fa fa-trash"></i></a></li>
                                                        <li><a href="{{ route('purchase.view', ['invoice' => $report->id, 'view' => 'return']) }}" class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @elseif( $report->type == 'payment' )
                                        <tr style="background-color: #e1e3f3" data-type="payment">
                                            {{-- Date --}}
                                            <td style="white-space: nowrap;">{{date('d-m-Y', strtotime($report->date))}}</td>
                                            {{-- Invoice --}}
                                            <td class="text-center">{{$report->id}}</td>
                                            {{-- Description --}}
                                            <td class="text-left">
                                                {{ $report->payment_by }}
                                                {{ $report->bank_title ? ' - ' . $report->bank_title : '' }}
                                                {{ $report->remarks ? ' - ' . $report->remarks : '' }}
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            {{-- Payment --}}
                                            <td class="text-right">{{number_format($report->payment)}}/=</td>
                                            {{-- Total Payment --}}
                                            <td class="text-right">{{number_format($report->payment)}}/=</td>
                                            {{-- Balance --}}
                                            <td class="text-right">{{number_format(-$report->payment)}}/=</td>
                                            {{-- Due Amount --}}
                                            <td class="text-right">{{number_format($report->balance)}}/=</td>
                                            {{-- Action --}}
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2" data-toggle="dropdown">
                                                        <i class="fa fa-list"></i> <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="{{ route('purchase.delete', ['invoice' => $report->id, 'view' => 'payment']) }}" class="btn btn-danger" id="delete"><i class="fa fa-trash"></i></a></li>
                                                        <li><a href="{{ route('purchase.view', ['invoice' => $report->id, 'view' => 'payment']) }}" class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>

                            <tfoot>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td></td>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $("#supplier_ledger").DataTable({
            ordering: false,
            footerCallback: function (row, data, start, end, display) {
                let api = this.api();
                let intVal = function (i) {
                    return typeof i === 'string'
                        ? i.replace(/[\/,\=,]/g, '') * 1
                        : typeof i === 'number'
                        ? i
                        : 0;
                };

                // Helper function to pluralize units dynamically
                var pluralizeUnit = function(unit) {
                    // Convert to lowercase for consistency
                    unit = unit.toLowerCase();

                    // Check if the unit ends with 's' or matches some common plural/singular rules
                    if (unit.endsWith('s')) {
                        return unit;
                    }

                    // Custom rules for some units, you can add more here as needed
                    if (unit === 'box') return 'boxes';
                    if (unit === 'kg') return 'kg';
                    if (unit === 'mt') return 'MT';

                    // Default to adding 's' for plural form
                    return unit + 's';
                };

                // updated calculateTotals function 30-10-2024
                var calculateTotals = function (columnIndex) {
                    // Initialize an object to hold totals for each unit type
                    var totals = {};

                    api.rows({ page: 'current' }).every(function (rowIdx, tableLoop, rowLoop) {
                        var row = this.node(); // Get the row element
                        var operation = $(row).attr('data-type'); // Get data-operation attribute

                        // Get the value from the specified column
                        var value = api.cell(rowIdx, columnIndex).data();

                        // Extract quantity and unit from the string (e.g., "5 Kg" or "8 Bags")
                        var matches = value.match(/(\d+(?:\.\d+)?)\s*([a-zA-Z]+)/g);
                        // console.log(matches);
                        if (matches) {
                            matches.forEach(function (match) {
                                var parts = match.split(/\s+/);
                                var quantity = parseFloat(parts[0]);
                                var unit = parts[1];

                                // Pluralize the unit dynamically
                                unit = pluralizeUnit(unit);

                                // Initialize the total for the unit if not already set
                                if (!totals[unit]) {
                                    totals[unit] = 0;
                                }

                                // Conditionally add or subtract the quantity based on the operation
                                if (operation === 'purchase') {
                                    totals[unit] += quantity;
                                } else if (operation === 'return') {
                                    totals[unit] -= quantity;
                                }
                            });
                        }
                    });


                    // Format the totals into a readable string (e.g., "38 Bags, 40 Kg")
                    var formattedTotals = Object.entries(totals).map(function([unit, quantity]) {
                        return quantity % 1 === 0 ? `${quantity} ${unit.charAt(0).toUpperCase() + unit.slice(1)}` : `${quantity.toFixed(2)} ${unit.charAt(0).toUpperCase() + unit.slice(1)}`;
                    }).join(', ');
                    // console.log(formattedTotals);
                    return formattedTotals;
                };

                var calculateSum = function (columnIndex) {
                    // Initialize an object to hold totals for each unit type
                    var totals = {};

                    api.rows({ page: 'current' }).every(function (rowIdx, tableLoop, rowLoop) {
                        var row = this.node(); // Get the row element
                        // var operation = $(row).attr('data-type'); // Get data-operation attribute

                        // Get the value from the specified column
                        var value = api.cell(rowIdx, columnIndex).data();

                        // Extract quantity and unit from the string (e.g., "5 Kg" or "8 Bags")
                        var matches = value.match(/(\d+(?:\.\d+)?)\s*([a-zA-Z]+)/g);
                        // console.log(matches);
                        if (matches) {
                            matches.forEach(function (match) {
                                var parts = match.split(/\s+/);
                                var quantity = parseFloat(parts[0]);
                                var unit = parts[1];

                                // Pluralize the unit dynamically
                                unit = pluralizeUnit(unit);

                                // Initialize the total for the unit if not already set
                                if (!totals[unit]) {
                                    totals[unit] = 0;
                                }

                                // Conditionally add or subtract the quantity based on the operation
                                totals[unit] += quantity;
                                // if (operation === 'purchase') {
                                // } else if (operation === 'return') {
                                //     totals[unit] -= quantity;
                                // }
                            });
                        }
                    });


                    // Format the totals into a readable string (e.g., "38 Bags, 40 Kg")
                    var formattedTotals = Object.entries(totals).map(function([unit, quantity]) {
                        return quantity % 1 === 0 ? `${quantity} ${unit.charAt(0).toUpperCase() + unit.slice(1)}` : `${quantity.toFixed(2)} ${unit.charAt(0).toUpperCase() + unit.slice(1)}`;
                    }).join(', ');
                    // console.log(formattedTotals);
                    return formattedTotals;
                };


                total_purchase_amount = api
                    .rows({ page: 'current' })
                    .nodes()
                    .toArray()
                    .reduce((total, row) => {
                        const value = intVal($(row).find('td').eq(8).text());
                        const operation = $(row).attr('data-type');
                        if (operation === 'purchase') {
                            return total + value;
                        } else if (operation === 'return') {
                            return total - value;
                        }
                        return total;
                    }, 0);

                total_discount_amount = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                total_return_amount = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                total_G_amount = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                total_vat_amount = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                total_carring = api
                    .column(13, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                total_others = api
                    .column(14, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                total_payment = api
                    .rows({ page: 'current' })
                    .nodes()
                    .toArray()
                    .reduce((total, row) => {
                        const value = intVal($(row).find('td').eq(15).text());
                        const operation = $(row).attr('data-type');
                        if (operation === 'purchase' || operation === 'payment' || operation === 'other') {
                            return total + value;
                        } else if (operation === 'return') {
                            return total - value;
                        }
                        return total;
                    }, 0);

                total_payment_amount = api
                    .column(16, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                total_line_amount = api
                    .column(17, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);



                total_balance_amount = api
                    .rows({ page: 'current' })
                    .nodes()
                    .toArray()
                    .reduce((total, row) => {
                        const value = intVal($(row).find('td').eq(16).text());
                        return total + value;
                    }, 0);

                // Update footer
                api.column(3).footer().innerHTML =
                    '<b>' + calculateTotals(3) + '</b>';
                api.column(4).footer().innerHTML =
                    '<b>' + calculateSum(4) + '</b>';
                api.column(5).footer().innerHTML =
                    '<b>' + calculateSum(5) + '</b>';
                api.column(6).footer().innerHTML =
                    '<b>' + calculateTotals(6) + '</b>';
                api.column(7).footer().innerHTML =
                    '<b>' + calculateTotals(7) + '</b>';
                api.column(8).footer().innerHTML =
                    total_purchase_amount ? '<b>' + fzNumberFormater(total_purchase_amount) + '/=</b>' : '';
                api.column(9).footer().innerHTML =
                    total_discount_amount ? '<b>' + fzNumberFormater(total_discount_amount) + '/=</b>' : '';
                api.column(10).footer().innerHTML =
                    total_return_amount ? '<b>' + fzNumberFormater(total_return_amount) + '/=</b>' : '';
                api.column(11).footer().innerHTML =
                    total_G_amount ? '<b>' + fzNumberFormater(total_G_amount) + '/=</b>' : '';
                api.column(12).footer().innerHTML =
                    total_vat_amount ? '<b>' + fzNumberFormater(total_vat_amount) + '/=</b>' : '';
                api.column(13).footer().innerHTML =
                    total_carring ? '<b>' + fzNumberFormater(total_carring) + '/=</b>' : '';
                api.column(14).footer().innerHTML =
                    total_others ? '<b>' + fzNumberFormater(total_others) + '/=</b>' : '';
                api.column(15).footer().innerHTML =
                    total_payment ? '<b>' + fzNumberFormater(total_payment) + '/=</b>' : '';
                api.column(16).footer().innerHTML =
                    total_payment_amount ? '<b>' + fzNumberFormater(total_payment_amount) + '/=</b>' : '';
                api.column(17).footer().innerHTML =
                    total_line_amount ? '<b>' + fzNumberFormater(total_line_amount) + '/=</b>' : '';
                api.column(18).footer().innerHTML =
                    total_line_amount ? '<b>' + fzNumberFormater(total_line_amount) + '/=</b>' : '';
            }
        });
    });
</script>
@endpush
