@extends('layouts.admin')

@section('page-title')
Customer Ledger 1
@endsection

@section('main-content')

@php
$type = 0;
@endphp
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel p-3">
        <div class="x_title">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Customer Ledger 1</h2>
                <h6 class="text-dark mb-0 mr-auto">{{$customer->name}}{{$customer->address ? ' - '.$customer->address : ''}}{{$customer->mobile ? ' - '.$customer->mobile : ''}}</h6>
                <a href="{{route('customer.index.due')}}" class="ml-3 cursor-pointer"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
        </div>
        <div class="x_content">
            <div class="due-list-area">
                @if(!empty($customer_ledger_info))
                    <div class="table-responsive">
                        <table id="customer_ledger" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Date</th>
                                    <th>Invoice</th>
                                    {{-- <th>Store/Warehouse</th> --}}
                                    <th>Particular</th>
                                    <th>Quantity</th>
                                    <th>Total (Tk)</th>
                                    <th>Collection</th>
                                    <th>Balance</th>
                                    <th>Due (Tk)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach( $customer_ledger_info as $customer_info )
                                    @if( $customer_info->type == 'other' )
                                        <tr>
                                            <td>{{date('d-m-Y', strtotime($customer_info->date))}}</td>
                                            <td class="text-center">{{$customer_info->id}}</td>
                                            {{-- <td class="text-center"></td> --}}
                                            <td class="text-left">{{$customer_info->received_by}}</td>
                                            <td></td>
                                            <td class=""></td>
                                            <td class="text-right">{{$customer_info->payment ?? 0}}/=</td>
                                            <td class="text-right">{{$customer_info->balance ?? 0}}/=</td>
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
                                        @endphp
                                        <tr style="background-color: #e1f3eb" data-type="sale">
                                            <td>{{date('d-m-Y', strtotime($customer_info->date))}}</td>
                                            <td class="text-center">{{$customer_info->id}}</td>
                                            {{-- <td class="text-center">{{$customer_info->store->name}}</td> --}}
                                            <td class="text-left">
                                                @foreach ($products as $product)
                                                @php
                                                    $type = $product->product->type;
                                                @endphp
                                                    @if($product->transaction_id == $customer_info->id)
                                                        <p class="mb-0">
                                                            {{ $product->product_code}} -
                                                            {{ $product->product_name}} -
                                                            {{'('}}{{ $product->product->size->description}}{{')'}} -
                                                            {{ $product->quantity - $product->discount_qty}} {{ trans_choice('labels.'.$type, ($product->quantity - $product->discount_qty))}} {{' @ '}}
                                                            {{ $product->unit_price}}/=
                                                            {{$product->total_price}}/=
                                                        </p>

                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($filtered_products as $product)
                                                    @php
                                                        $product_info = DB::table('products')->where('id', $product->product_id)->first();
                                                        $type = $product_info->type;
                                                        $qty_summary[$type] = $qty_summary[$type] ?? 0;
                                                        $qty_summary[$type] += ($product->quantity - $product->discount_qty);

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


                                            <td class="text-right">{{$customer_info->total_price - $customer_info->price_discount + $customer_info->vat + $customer_info->carring + $customer_info->other_charge }}/=</td>
                                            @if($customer_info->payment)
                                                <td class="text-right">{{$customer_info->payment}}/=</td>
                                            @else
                                                <td></td>
                                            @endif
                                            <td class="text-right">{{ $customer_info->total_price - $customer_info->price_discount + $customer_info->vat + $customer_info->carring + $customer_info->other_charge - $customer_info->payment }}/=</td>
                                            <td class="text-right">{{$customer_info->balance}}/=</td>
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
                                        <tr style="background-color: #e1e3f3">
                                            <td class="">{{date('d-m-Y', strtotime($customer_info->date))}}</td>
                                            <td class="text-center">{{$customer_info->id}}</td>
                                            {{-- <td></td> --}}
                                            <td class="text-left">
                                                {{ 'Collection' }} :
                                                {{ $customer_info->payment_by }}
                                                {{ $customer_info->bank_title ? ' : ' . $customer_info->bank_title : '' }}
                                                {{ $customer_info->received_by ? ' - ' . $customer_info->received_by : '' }}
                                            </td>

                                            <td></td>
                                            <td></td>
                                            @if($customer_info->payment)
                                                <td class="text-right">{{$customer_info->payment}}/=</td>
                                            @else
                                            <td></td>
                                            @endif
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
                                        @endphp
                                        <tr style="background-color: #f3e1e1" data-type="return">
                                            <td class="">
                                                <span class="d-block text-nowrap">{{date('d-m-Y', strtotime($customer_info->date))}}</span>
                                            </td>
                                            <td class="text-center">{{$customer_info->id}}</td>
                                            {{-- <td></td> --}}
                                            <td class="text-left">
                                                {{-- <span class="d-block text-nowrap">Returned: {{date('d-m-Y', strtotime($customer_info->sale_date))}}</span> --}}
                                                @foreach ($products as $product)
                                                    @php
                                                        $type = $product->product->type;
                                                    @endphp
                                                    @if($product->transaction_id == $customer_info->id)
                                                        <p class="mb-0">
                                                            {{ $product->product_code}} -
                                                            {{ $product->product_name}} -
                                                            {{'('}}{{ $product->product->size->description}}{{')'}} -
                                                            {{ $product->quantity}} {{ trans_choice('labels.'.$type, $product->quantity)}} {{' @ '}}
                                                            {{ $product->unit_price}}/=
                                                            {{$product->total_price}}/=
                                                        </p>
                                                    @endif
                                                @endforeach
                                            </td>
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

                                            <td class="text-right">{{$customer_info->total_price - ($customer_info->carring + $customer_info->other_charge) }}/=</td>

                                            <td></td>
                                            <td class="text-right">-{{$customer_info->total_price - ($customer_info->carring + $customer_info->other_charge) }}/=</td>


                                            <td class="text-right">{{$customer_info->balance}}/=</td>
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
                                @endforeach
                            </tbody>
                            <tfoot>
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
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    #customer_ledger th {
        white-space: nowrap;
    }
</style>
@endpush
@push('scripts')
<script>
    $(document).ready(function() {
        // $('#customer_ledger').DataTable({
        //     ordering: false
        // });
        $("#customer_ledger").DataTable({
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

                    return formattedTotals;
                };


                total_sale_amount = api
                    .rows({ page: 'current' })
                    .nodes()
                    .toArray()
                    .reduce((total, row) => {
                        const value = intVal($(row).find('td').eq(4).text());
                        const operation = $(row).attr('data-type');
                        if (operation === 'sale') {
                            return total + value;
                        } else if (operation === 'return') {
                            return total - value;
                        }
                        return total;
                    }, 0);

                total_collection_amount = api
                    .column(5, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                total_balance_amount = api
                    .rows({ page: 'current' })
                    .nodes()
                    .toArray()
                    .reduce((total, row) => {
                        const value = intVal($(row).find('td').eq(6).text());
                        return total + value;
                    }, 0);


                // Update footer
                api.column(3).footer().innerHTML =
                    '<b>' + calculateTotals(3) + '</b>';
                api.column(4).footer().innerHTML =
                    total_sale_amount ? '<b>' + total_sale_amount + '/=</b>' : '';
                api.column(5).footer().innerHTML =
                    total_collection_amount ? '<b>' + total_collection_amount + '/=</b>' : '';
                api.column(6).footer().innerHTML =
                    total_balance_amount ? '<b>' + total_balance_amount + '/=</b>' : '';
                api.column(7).footer().innerHTML =
                    total_balance_amount ? '<b>' + total_balance_amount + '/=</b>' : '';
            }
        });
    });
</script>
@endpush
