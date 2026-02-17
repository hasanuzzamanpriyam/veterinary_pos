@extends('layouts.admin')

@section('page-title')
Supplier Statement 1
@endsection

@section('main-content')

@php
$type = 0;
@endphp
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Supplier Statement 1</h2>
                <h6 class="text-dark mb-0 mr-auto">{{$supplier->company_name}}{{$supplier->address ? ' - '.$supplier->address : ''}}{{$supplier->mobile ? ' - '.$supplier->mobile : ''}}</h6>
                <a href="{{route('supplier.index')}}" class="ml-3 cursor-pointer"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
        </div>
        <div class="x_content p-3">
            <div class="due-list-area">
                <div class="table-responsive sales_list_table">
                    {{-- @dump($supplier_ledger_info) --}}
                    <table id="datatable-responsiverr" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr class="text-center">
                                <th>Date</th>
                                <th>Invoice</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Weight</th>
                                <th>Total Tk</th>
                                <th>Payment</th>
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
                                $total_amount = 0;
                                $total_balance = 0;
                                $total_due = 0;
                                $total_payment_due = 0;
                                $total_payment_advance = 0;
                            @endphp
                            @foreach ($supplier_ledger_info as $report)
                                @if( $report->type == 'other' )
                                    <tr>
                                        <td style="white-space: nowrap;">{{date('d-m-Y', strtotime($report->date))}}</td>
                                        <td class="text-center">{{$report->id}}</td>
                                        <td class="text-left">Opening Balance</td>
                                        <td class=""></td>
                                        <td class=""></td>
                                        <td class=""></td>
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

                                @elseif( $report->type == 'purchase' )
                                    @php
                                        $filtered_products = $products->where('transaction_id', $report->id);
                                        $qty_summary = [];
                                        $qty_summary_dis = [];
                                        $qty_summary_sale = [];
                                        $qty_mt = 0;

                                        $total_tk = $report->total_price - $report->price_discount;
                                        $total_payment = $report->payment + $report->vat + $report->carring + $report->other_charge;
                                        $balance = $total_tk - $total_payment;
                                    @endphp
                                    <tr style="background-color: #e1f3eb" data-type="purchase">
                                        <td>{{date('d-m-Y', strtotime($report->date))}}</td>
                                        <td class="text-center">{{$report->id}}</td>
                                        <td class="text-left">
                                            {{'Purchase'}} {{$report->transport_no}}{{$report->delivery_man && $report->transport_no ? ", " : ''}}{{$report->delivery_man}}
                                        </td>

                                        {{-- Quantity --}}
                                        <td>
                                            @foreach ($filtered_products as $product)
                                                @php
                                                    $product_info = DB::table('products')->where('id', $product->product_id)->first();
                                                    $type = $product_info->type;
                                                    $qty_summary_sale[$type] = $qty_summary_sale[$type] ?? 0;
                                                    $qty_summary_sale[$type] += ($product->quantity - $product->discount_qty);
                                                    $qty_mt += $product->product->size->name * ($product->quantity - $product->discount_qty);
                                                @endphp
                                            @endforeach
                                            @php
                                                ksort($qty_summary_sale);
                                            @endphp
                                            <div>
                                                @if( isset($qty_summary_sale) && count($qty_summary_sale) > 0)
                                                    @foreach ($qty_summary_sale as $key => $value)
                                                        @if( $value > 0)
                                                            {{ $value }} {{trans_choice('labels.'.strtolower($key), $value)}}
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Weight --}}
                                        <td>{{$qty_mt / 1000}} MT</td>

                                        {{-- Total Tk --}}
                                        <td class="text-right">{{$total_tk > 0 ? number_format($total_tk) . '/=' : ''}}</td>

                                        {{-- Payment --}}
                                        <td class="text-right">{{$total_payment > 0 ? number_format($total_payment) . '/=' : ''}}</td>

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
                                                    <li><a href="{{ route('purchase.delete', ['invoice' => $report->id, 'view' => 'purchase']) }}" class="btn btn-danger" id="delete"><i class="fa fa-trash"></i></a></li>
                                                    <li><a href="{{ route('purchase.view', ['invoice' => $report->id, 'view' => 'purchase']) }}" class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @elseif( $report->type == 'payment' )
                                    <tr style="background-color: #e1e3f3" data-type="payment">
                                        <td class="">{{date('d-m-Y', strtotime($report->date))}}</td>
                                        <td class="text-center">{{$report->id}}</td>
                                        <td class="text-left">
                                            {{ $report->payment_by }}
                                            {{ $report->bank_title ? ' - ' . $report->bank_title : '' }}
                                            {{ $report->payment_remarks ? ' - ' . $report->payment_remarks : '' }}

                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right">{{$report->payment > 0 ? number_format($report->payment) . '/=' : ''}}</td>
                                        <td class="text-right">-{{$report->payment > 0 ? number_format($report->payment) . '/=' : ''}}</td>
                                        <td class="text-right">{{number_format($report->balance)}}/=</td>
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
                                @elseif( $report->type == 'return' )
                                    @php
                                        $filtered_products = $products->where('transaction_id', $report->id);
                                        $qty_summary = [];
                                        $qty_summary_return = [];
                                        $qty_mt = 0;
                                        $t_payment = $report->carring + $report->other_charge + $report->payment;
                                        $total_tk = $report->total_price - $report->price_discount + $report->carring + $report->other_charge;
                                    @endphp
                                    <tr style="background-color: #f3e1e1" data-type="return">
                                        <td class="">
                                            <span class="d-block text-nowrap">{{date('d-m-Y', strtotime($report->date))}}</span>
                                        </td>
                                        <td class="text-center">{{$report->id}}</td>
                                        <td class="text-left">
                                            <span class="d-block text-nowrap">Returned</span>

                                        </td>
                                        <td>
                                            @foreach ($filtered_products as $product)
                                                @php
                                                    $product_info = DB::table('products')->where('id', $product->product_id)->first();
                                                    $type = $product_info->type;
                                                    $qty_summary_return[$type] = $qty_summary_return[$type] ?? 0;
                                                    $qty_summary_return[$type] += $product->quantity;
                                                    $qty_mt += $product->product->size->name * $product->quantity;
                                                @endphp
                                            @endforeach
                                            @php
                                                ksort($qty_summary_return);
                                            @endphp
                                            <div>
                                                @if( isset($qty_summary_return) && count($qty_summary_return) > 0)
                                                    (-)
                                                    @foreach ($qty_summary_return as $key => $value)
                                                        {{ $value }} {{trans_choice('labels.'.strtolower($key), $value)}}
                                                    @endforeach
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Weight --}}
                                        <td>-{{$qty_mt / 1000}} MT</td>

                                        {{-- Total Tk --}}
                                        <td class="text-right">-{{number_format($total_tk)}}/=</td>

                                        {{-- Payment --}}
                                        <td class="text-right">{{$t_payment ? number_format($t_payment) . '/=' : ''}}</td>

                                        {{-- Balance --}}
                                        <td class="text-right">{{number_format(-$total_tk)}}/=</td>

                                        {{-- Due Amount --}}
                                        <td class="text-right">{{number_format($report->balance)}}/=</td>
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
                                @endif
                            @endforeach
                        </tbody>




                        <tfoot>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td></td>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $("#datatable-responsiverr").DataTable({
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

                total_line_amount = api
                    .column(5, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                total_payment_amount = api
                    .column(6, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                total_balance_amount = api
                    .rows({ page: 'current' })
                    .nodes()
                    .toArray()
                    .reduce((total, row) => {
                        const value = intVal($(row).find('td').eq(7).text());
                        return total + value;
                    }, 0);

                // Update footer
                api.column(3).footer().innerHTML =
                    '<b>' + calculateTotals(3) + '</b>';
                api.column(4).footer().innerHTML =
                    '<b>' + calculateTotals(4) + '</b>';
                api.column(5).footer().innerHTML =
                    total_line_amount ? '<b>' + fzNumberFormater(total_line_amount) + '/=</b>' : '';
                api.column(6).footer().innerHTML =
                    total_payment_amount ? '<b>' + fzNumberFormater(total_payment_amount) + '/=</b>' : '';
                api.column(7).footer().innerHTML =
                    total_balance_amount ? '<b>' + fzNumberFormater(total_balance_amount) + '/=</b>' : '';
                api.column(8).footer().innerHTML =
                    total_balance_amount ? '<b>' + fzNumberFormater(total_balance_amount) + '/=</b>' : '';
            }
        });
    });
</script>
@endpush
