@extends('layouts.admin')

@section('page-title')
    Sales List 1
@endsection

@section('main-content')
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title p-3">
                <div class="header-title d-flex align-items-center gap-2">
                    <h2 class="mr-auto">Sales List-1</h2>
                    <a href="{{ route('live.sales.create') }}" class="btn btn-md btn-primary"> <i class="fa fa-plus"
                            aria-hidden="true"></i> Add New Sales</a>
                </div>
            </div>

            <div class="x_content p-3">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 ">
                        <form action="{{ route('sales.search') }}" method="post" data-parsley-validate
                            class="form-horizontal form-label-left">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-6">
                                            <div class="form-group">
                                                {{-- <label class="d-block py-1 border" for="name">Start Date:</label> --}}
                                                <input type="date" id="start_date" name="start_date" value="{{ $request->start_date ?? '' }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-6">
                                            <div class="form-group ">
                                                {{-- <label class="d-block py-1 border" for="name">End Date:</label> --}}
                                                <input type="date" id="end_date" name="end_date" value="{{ $request->end_date ?? '' }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-6">
                                            <div class="form-group ">
                                                {{-- <label class="d-block py-1 border" for="name">End Date:</label> --}}
                                                {{-- <input type="date" id="end_date" name="end_date" class="form-control"> --}}
                                                <select name="get_customer_id" id="get_customer_id" class="form-control">
                                                    <option value="">Select Customer</option>
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}" @if (isset($request) && $customer->id == $request->get_customer_id) selected @endif>{{ $customer->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-6">
                                            <div class="form-group">
                                                <input type="submit" value="Search"
                                                    class="btn btn-success">
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-6">
                                            <div class="form-group">
                                                <a href="{{ route('sales.index', [ 'view' => 'v1' ]) }}"
                                                    class="btn btn-danger">Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive sales_list_table">
                            <table id="purchaseList"
                                class="table table-striped table-bordered dt-responsive nowrap"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="all">Date</th>
                                        <th class="all">Invoice</th>
                                        <th class="all">Customer Name</th>
                                        <th class="all">Address</th>
                                        <th class="all">Mobile</th>
                                        <th class="all">Delivery Info</th>
                                        <th class="all">Particular</th>
                                        <th class="all">Sales Qty</th>
                                        <th class="all">Sale Tk</th>
                                        <th class="all">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($customer_ledger as $sales)

                                        @php
                                            $qty_summary = [];
                                            $filtered_products = $products->where('transaction_id', $sales->id);
                                        @endphp

                                        <tr>
                                            {{-- Date --}}
                                            <td class="text-center">{{ date('d-m-Y', strtotime($sales->date)) }} </td>

                                            {{-- Invoice No. --}}
                                            <td class="text-center">{{ $sales->id }}</td>

                                            {{-- Customer Name --}}
                                            <td class="text-left">{{ $sales->customer->name }}</td>

                                            {{-- Address --}}
                                            <td class="text-left">{{ $sales->customer->address }}</td>

                                            {{-- Mobile --}}
                                            <td class="text-left">{{$sales->customer->phone ?? $sales->customer->mobile ?? ''}}</td>

                                            {{-- Delivery Info --}}
                                            <td class="text-left">{{$sales->store->name}}{{$sales->transport_no ? ", " . $sales->transport_no : ''}}</td>

                                            {{-- Particular --}}
                                            <td class="text-left">
                                                @foreach ($filtered_products as $product)
                                                    @php
                                                        $type = $product->product->type;
                                                        $qty_summary['sale'][$type] = $qty_summary['sale'][$type] ?? 0;
                                                        $qty_summary['sale'][$type] += ($product->quantity - $product->discount_qty);
                                                    @endphp
                                                    <p class="mb-0 text-left">
                                                        {{ $product->product_code}} -
                                                        {{ $product->product_name}} {{'('}}{{ $product->product->size->description}}{{')'}} -
                                                        {{ $product->quantity - $product->discount_qty}} {{ trans_choice('labels.'.$type, ($product->quantity - $product->discount_qty))}}{{' @ '}}{{ $product->unit_price}}/=
                                                        {{ $product->total_price}}/=
                                                    </p>
                                                @endforeach
                                            </td>

                                            {{-- Sale Qty --}}
                                            <td class="text-center">
                                                @if(isset($qty_summary['sale']))
                                                    @foreach ($qty_summary['sale'] as $type => $qty)
                                                        {{$qty > 0 ? $qty . ' ' . trans_choice('labels.' . $type, $qty) : ''}}
                                                    @endforeach

                                                @endif
                                            </td>

                                            {{-- Sale Tk --}}
                                            <td class="text-right">
                                                @php
                                                    $total  = $sales->total_price - $sales->price_discount + $sales->vat + $sales->carring + $sales->other_charge;
                                                @endphp
                                                {{ number_format($total) }}/=
                                            </td>

                                            {{-- Action --}}
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                        data-toggle="dropdown">
                                                        <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="{{ route('sales.delete', ['invoice' => $sales->id, 'view' => 'sale']) }}"
                                                                class="btn btn-danger" id="delete"><i
                                                                    class="fa fa-trash"></i></a></li>
                                                        <li><a href="{{ route('sales.view', $sales->id) }}"
                                                                class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
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
                                    <td></td>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $("#purchaseList").DataTable({
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
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);


                // Update footer
                api.column(7).footer().innerHTML =
                    '<b>' + calculateSum(7) + '</b>';

                api.column(8).footer().innerHTML =
                    total_sale_amount ? '<b>' + fzNumberFormater(total_sale_amount) + '/=</b>' : '';
            }
        });
    });
</script>
@endpush
