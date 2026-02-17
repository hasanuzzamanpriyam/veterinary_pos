@extends('layouts.admin')

@section('page-title')
Customer Statement 2
@endsection

@section('main-content')

@php
$type = 0;
@endphp
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel p-3">
        <div class="x_title">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Customer Statement 2</h2>
                <h6 class="text-dark mb-0 mr-auto">{{$customer->name}}{{$customer->address ? ' - '.$customer->address : ''}}{{$customer->mobile ? ' - '.$customer->mobile : ''}}</h6>
                <a href="{{route('customer.index')}}" class="ml-3 cursor-pointer"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
        </div>
        <div class="x_content">
            <div class="due-list-area">
                @if(count($customer_ledger_info) > 0)
                    <div class="table-responsive sales_list_table">
                        <table id="datatable-responsiverr"
                            class="table table-striped table-bordered nowrap"
                            cellspacing="0">
                            <thead>
                                <tr class="text-center">
                                    <th>Date</th>
                                    <th>Invoice</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Dis. Qty</th>
                                    <th>Return Qty</th>
                                    <th>Sale Qty</th>
                                    <th>Sale TK</th>
                                    <th>Dis. TK</th>
                                    <th>VAT</th>
                                    <th>Carring</th>
                                    <th>Others</th>
                                    <th>Total</th>
                                    <th>Collection</th>
                                    <th>Balance</th>
                                    <th>Due Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_summary = [
                                        'payment' => 0,
                                        'balance' => 0,
                                        'sale_tk' => 0,
                                        'sale_return_tk' => 0,
                                        'total' => 0,
                                        'dis_tk' => 0,
                                        'vat' => 0,
                                        'carring' => 0,
                                        'other_charge' => 0
                                    ];
                                @endphp
                                @foreach( $customer_ledger_info as $customer_info )

                                    @if( $customer_info->type == 'other' )
                                        <tr data-type="other">
                                            <td class="text-center">{{date('d-m-Y', strtotime($customer_info->date))}}</td>
                                            <td class="text-center">{{$customer_info->id}}</td>
                                            <td class="text-left">{{$customer_info->received_by}}</td>
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
                                            <td class="text-right">
                                                @if( $customer_info->balance < 0 )
                                                    {{-$customer_info->balance}}/=
                                                    @php
                                                        $total_summary['payment'] = $total_summary['payment'] ?? 0;
                                                        $total_summary['payment'] -= $customer_info->balance;
                                                    @endphp

                                                @endif
                                            </td>
                                            <td class="text-right">{{$customer_info->balance}}/=</td>
                                            <td class="text-right">{{$customer_info->balance}}/=</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                        data-toggle="dropdown">
                                                        <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="{{ route('sales.delete', ['invoice' => $customer_info->id, 'view' => 'other']) }}"
                                                                class="btn btn-danger" id="delete"><i
                                                                    class="fa fa-trash"></i></a></li>
                                                        <li><a href="{{ route('sales.view', $customer_info->id) }}"
                                                                class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @elseif( $customer_info->type == 'sale' )
                                        @php
                                            $filtered_products = $products->where('transaction_id', $customer_info->id);
                                            $qty_summary = [];
                                            $qty_summary_dis = [];
                                            $qty_summary_sale = [];
                                        @endphp
                                        <tr style="background-color: #e1f3eb" data-type="sale">
                                            <td>{{date('d-m-Y', strtotime($customer_info->date))}}</td>
                                            <td class="text-center">{{$customer_info->id}}</td>
                                            <td class="text-left">{{'Sale'}} {{$customer_info->product_store_id ? ' : '. $customer_info->store->name : ''}} {{$customer_info->transport_no ? ' : '.$customer_info->transport_no : ''}}</td>

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
                                                            @php
                                                                $total_summary['sale'][$key] = $total_summary['sale'][$key] ?? 0;
                                                                $total_summary['sale'][$key] += $value;
                                                            @endphp
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
                                                            @php
                                                                $total_summary['discount'][$key] = $total_summary['discount'][$key] ?? 0;
                                                                $total_summary['discount'][$key] += $value;
                                                            @endphp
                                                            @if( $value > 0)
                                                                {{ $value }} {{trans_choice('labels.'.strtolower($key), $value)}}
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>

                                            {{-- Return Qty --}}
                                            <td></td>

                                            {{-- Sale Qty --}}
                                            <td class="text-center">

                                                @foreach ($filtered_products as $product)
                                                    @php
                                                        $product_info = DB::table('products')->where('id', $product->product_id)->first();
                                                        $type = $product_info->type;
                                                        $qty_summary_sale[$type] = $qty_summary_sale[$type] ?? 0;
                                                        $qty_summary_sale[$type] += ($product->quantity - $product->discount_qty);
                                                    @endphp
                                                @endforeach
                                                @php
                                                    ksort($qty_summary_sale);
                                                @endphp
                                                <div>
                                                    @if( isset($qty_summary_sale) && count($qty_summary_sale) > 0)
                                                        @foreach ($qty_summary_sale as $key => $value)
                                                            @php
                                                                $total_summary['total_sale'][$key] = $total_summary['total_sale'][$key] ?? 0;
                                                                $total_summary['total_sale'][$key] += $value;
                                                            @endphp
                                                            @if( $value > 0)
                                                                {{ $value }} {{trans_choice('labels.'.strtolower($key), $value)}}
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-right">
                                                @if($customer_info->total_price)
                                                    @php
                                                        $total_summary['sale_tk'] = $total_summary['sale_tk'] ?? 0;
                                                        $total_summary['sale_tk'] += $customer_info->total_price;
                                                    @endphp
                                                    {{$customer_info->total_price}}/=
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if($customer_info->price_discount)
                                                    @php
                                                        $total_summary['dis_tk'] = $total_summary['dis_tk'] ?? 0;
                                                        $total_summary['dis_tk'] += $customer_info->price_discount;
                                                    @endphp
                                                    {{$customer_info->price_discount}}/=
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if($customer_info->vat)
                                                    @php
                                                        $total_summary['vat'] = $total_summary['vat'] ?? 0;
                                                        $total_summary['vat'] += $customer_info->vat;
                                                    @endphp
                                                    {{$customer_info->vat}}/=
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if($customer_info->carring)
                                                    @php
                                                        $total_summary['carring'] = $total_summary['carring'] ?? 0;
                                                        $total_summary['carring'] += $customer_info->carring;
                                                    @endphp
                                                    {{$customer_info->carring}}/=
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if($customer_info->other_charge)
                                                    @php
                                                        $total_summary['other_charge'] = $total_summary['other_charge'] ?? 0;
                                                        $total_summary['other_charge'] += $customer_info->other_charge;
                                                    @endphp
                                                    {{$customer_info->other_charge}}/=
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @php
                                                    $total = $customer_info->total_price - $customer_info->price_discount + $customer_info->vat + $customer_info->carring + $customer_info->other_charge;
                                                    $total_summary['total'] = $total_summary['total'] ?? 0;
                                                    $total_summary['total'] += $total;
                                                @endphp
                                                {{$total}}/=
                                            </td>
                                            <td class="text-right">
                                                @if($customer_info->payment)
                                                    @php
                                                        $total_summary['payment'] = $total_summary['payment'] ?? 0;
                                                        $total_summary['payment'] += $customer_info->payment;
                                                    @endphp
                                                    {{$customer_info->payment}}/=
                                                @endif
                                            </td>



                                            <td class="text-right">
                                                @php
                                                    $total_balance = $customer_info->total_price - $customer_info->price_discount + $customer_info->vat + $customer_info->carring + $customer_info->other_charge - $customer_info->payment;
                                                @endphp
                                                {{$total_balance}}/=
                                            </td>

                                            <td class="text-right">
                                                {{$customer_info->balance}}/=
                                            </td>

                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                        data-toggle="dropdown">
                                                        <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="{{ route('sales.delete', ['invoice' => $customer_info->id, 'view' => 'sale']) }}"
                                                                class="btn btn-danger" id="delete"><i
                                                                    class="fa fa-trash"></i></a></li>
                                                        <li><a href="{{ route('sales.view', $customer_info->id) }}"
                                                                class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @elseif( $customer_info->type == 'collection' )
                                        <tr style="background-color: #e1e3f3" data-type="collection">
                                            <td class="">{{date('d-m-Y', strtotime($customer_info->date))}}</td>
                                            <td class="text-center">{{$customer_info->id}}</td>
                                            <td class="text-left">
                                                {{ 'Collection' }}
                                                {{ $customer_info->payment_by ? ' : ' . $customer_info->payment_by : '' }}
                                                {{ $customer_info->bank_title ? ' - ' . $customer_info->bank_title : '' }}
                                                {{ $customer_info->received_by ? ' - ' . $customer_info->received_by : '' }}

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
                                            <td class="text-right">
                                                @if($customer_info->payment)
                                                    @php
                                                        $total_summary['payment'] = $total_summary['payment'] ?? 0;
                                                        $total_summary['payment'] += $customer_info->payment;
                                                    @endphp
                                                    {{$customer_info->payment}}/=
                                                @endif
                                            </td>
                                            @if($customer_info->payment)
                                                <td class="text-right">-{{$customer_info->payment}}/=</td>
                                            @else
                                                <td></td>
                                            @endif
                                            <td class="text-right">{{$customer_info->balance}}/=</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                        data-toggle="dropdown">
                                                        <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="{{ route('sales.delete', ['invoice' => $customer_info->id, 'view' => 'collection']) }}"
                                                                class="btn btn-danger" id="delete"><i
                                                                    class="fa fa-trash"></i></a></li>
                                                        <li><a href="{{ route('collection.view', $customer_info->id) }}"
                                                                class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @elseif( $customer_info->type == 'return' )
                                        @php
                                            $filtered_products = $products->where('transaction_id', $customer_info->id);
                                            $qty_summary = [];
                                            $qty_summary_return = [];
                                        @endphp
                                        <tr style="background-color: #f3e1e1" data-type="return">
                                            <td class="">
                                                <span class="d-block text-nowrap">{{date('d-m-Y', strtotime($customer_info->date))}}</span>
                                            </td>
                                            <td class="text-center">{{$customer_info->id}}</td>
                                            <td class="text-left">
                                                <span class="d-block text-nowrap">Returned</span>

                                            </td>

                                            {{-- Quantity --}}
                                            <td></td>
                                            <td></td>
                                            <td>
                                                @foreach ($filtered_products as $product)
                                                    @php
                                                        $product_info = DB::table('products')->where('id', $product->product_id)->first();
                                                        $type = $product_info->type;
                                                        $qty_summary[$type] = $qty_summary[$type] ?? 0;
                                                        $qty_summary[$type] += $product->quantity;
                                                    @endphp
                                                @endforeach
                                                <div>
                                                    @if( isset($qty_summary) && count($qty_summary) > 0)
                                                        @foreach ($qty_summary as $key => $value)
                                                            {{ $value }} {{trans_choice('labels.'.strtolower($key), $value)}}
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>

                                            {{-- Sale Qty --}}
                                            <td>
                                                <div>
                                                    @if( isset($qty_summary) && count($qty_summary) > 0)
                                                        -
                                                        @foreach ($qty_summary as $key => $value)
                                                            {{ $value }} {{trans_choice('labels.'.strtolower($key), $value)}}
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>

                                            @if($customer_info->total_price)
                                                @php
                                                    $total_summary['sale_return_tk'] = $total_summary['sale_return_tk'] ?? 0;
                                                    $total_summary['sale_return_tk'] += $customer_info->total_price;
                                                @endphp
                                                <td class="text-right">{{$customer_info->total_price}}/=</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td></td>
                                            <td></td>

                                            <td class="text-right">
                                                @if($customer_info->carring)
                                                    @php
                                                        $total_summary['carring'] = $total_summary['carring'] ?? 0;
                                                        $total_summary['carring'] -= $customer_info->carring;
                                                    @endphp
                                                    {{$customer_info->carring}}/=
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if($customer_info->other_charge)
                                                    @php
                                                        $total_summary['other_charge'] = $total_summary['other_charge'] ?? 0;
                                                        $total_summary['other_charge'] -= $customer_info->other_charge;
                                                    @endphp
                                                    {{$customer_info->other_charge}}/=
                                                @endif
                                            </td>

                                            <td class="text-right">
                                                @php
                                                    $total = $customer_info->total_price - ($customer_info->carring + $customer_info->other_charge);
                                                    $total_summary['total'] = $total_summary['total'] ?? 0;
                                                    $total_summary['total'] -= $total;
                                                @endphp
                                                {{$total}}/=
                                            </td>

                                            <td></td>
                                            <td class="text-right">
                                                @php
                                                    $total_balance = $customer_info->total_price - ($customer_info->carring + $customer_info->other_charge);
                                                @endphp
                                                {{-$total_balance}}/=
                                            </td>

                                            <td class="text-right">
                                                {{$customer_info->balance}}/=
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                        data-toggle="dropdown">
                                                        <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="{{ route('sales.delete', ['invoice' => $customer_info->id, 'view' => 'return']) }}"
                                                                class="btn btn-danger" id="delete"><i
                                                                    class="fa fa-trash"></i></a></li>
                                                        <li><a href="{{ route('sales.return.view', $customer_info->id) }}"
                                                                class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                    @php
                                        $total_summary['balance'] = $total_summary['balance'] ?? 0;
                                        $total_summary['balance'] = $customer_info->balance;
                                    @endphp
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
                @else
                    <p class="text-center">No data found</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $("#datatable-responsiverr").DataTable({
            paging: false,
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

                    // Default to adding 's' for plural form
                    return unit + 's';
                };

                var calculateTotals = function (columnIndex) {
                    // Initialize an object to hold totals for each unit type
                    var totals = {};

                    api.rows({ page: 'current' }).every(function (rowIdx, tableLoop, rowLoop) {
                        var row = this.node(); // Get the row element
                        var operation = $(row).attr('data-type'); // Get data-operation attribute

                        // Get the value from the specified column
                        var value = api.cell(rowIdx, columnIndex).data();

                        // Extract quantity and unit from the string (e.g., "5 Kg" or "8 Bags")
                        var matches = value.match(/(\d+)\s*([a-zA-Z]+)/g);
                        if (matches) {
                            matches.forEach(function (match) {
                                var parts = match.split(/\s+/);
                                var quantity = parseInt(parts[0]);
                                var unit = parts[1];

                                // Pluralize the unit dynamically
                                unit = pluralizeUnit(unit);

                                // Initialize the total for the unit if not already set
                                if (!totals[unit]) {
                                    totals[unit] = 0;
                                }

                                // Conditionally add or subtract the quantity based on the operation
                                if (operation === 'sale') {
                                    totals[unit] += quantity;
                                } else if (operation === 'return') {
                                    totals[unit] -= quantity;
                                }
                            });
                        }
                    });


                    // Format the totals into a readable string (e.g., "38 Bags, 40 Kg")
                    var formattedTotals = Object.entries(totals).map(function([unit, quantity]) {
                        return `${quantity} ${unit.charAt(0).toUpperCase() + unit.slice(1)}`;
                    }).join(', ');
                    console.log(formattedTotals);
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


                total_sale_amount = api
                    .rows({ page: 'current' })
                    .nodes()
                    .toArray()
                    .reduce((total, row) => {
                        const value = intVal($(row).find('td').eq(7).text());
                        const operation = $(row).attr('data-type');
                        if (operation === 'sale') {
                            return total + value;
                        } else if (operation === 'return') {
                            return total - value;
                        }
                        return total;
                    }, 0);

                total_discount_amount = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                total_vat_amount = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                total_carring = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                total_others = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                total_line_amount = api
                    .rows({ page: 'current' })
                    .nodes()
                    .toArray()
                    .reduce((total, row) => {
                        const value = intVal($(row).find('td').eq(12).text());
                        const operation = $(row).attr('data-type');
                        if (operation === 'sale') {
                            return total + value;
                        } else if (operation === 'return') {
                            return total - value;
                        }
                        return total;
                    }, 0);

                total_collection_amount = api
                    .column(13, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                total_balance_amount = api
                    .rows({ page: 'current' })
                    .nodes()
                    .toArray()
                    .reduce((total, row) => {
                        const value = intVal($(row).find('td').eq(14).text());
                        return total + value;
                    }, 0);

                    // total_discount_amount = 12;
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
                    total_sale_amount ? '<b>' + total_sale_amount + '/=</b>' : '';
                api.column(8).footer().innerHTML =
                    total_discount_amount ? '<b>' + total_discount_amount + '/=</b>' : '';
                api.column(9).footer().innerHTML =
                    total_vat_amount ? '<b>' + total_vat_amount + '/=</b>' : '';
                api.column(10).footer().innerHTML =
                    total_carring ? '<b>' + total_carring + '/=</b>' : '';
                api.column(11).footer().innerHTML =
                    total_others ? '<b>' + total_others + '/=</b>' : '';
                api.column(12).footer().innerHTML =
                    total_line_amount ? '<b>' + total_line_amount + '/=</b>' : '';
                api.column(13).footer().innerHTML =
                    total_collection_amount ? '<b>' + total_collection_amount + '/=</b>' : '';
                api.column(14).footer().innerHTML =
                    total_balance_amount ? '<b>' + total_balance_amount + '/=</b>' : '';
                api.column(15).footer().innerHTML =
                    total_balance_amount ? '<b>' + total_balance_amount + '/=</b>' : '';
            }
        });
    });
</script>

@endpush
