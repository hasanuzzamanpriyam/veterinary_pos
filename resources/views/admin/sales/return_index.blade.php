@extends('layouts.admin')

@section('page-title')
Sales Return List
@endsection

@section('main-content')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto">Sales Return List</h2>
                <a href="{{route('live.sales.return.create')}}" class="btn btn-md btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add New Sales Return</a>
            </div>
        </div>
        <div class="x_content p-3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    <div class="card-box table-responsive">
                        {{-- notification message --}}
                        @if(session()->has('msg'))
                            <div class="text-center alert alert-success">
                                {{session()->get('msg')}}
                            </div>
                        @endif
                        <table id="SaleReturnList" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <th class="all">SL</th>
                                <th class="all">Purchase Date</th>
                                <th class="all">Return Date</th>
                                <th class="all">Customer Name</th>
                                <th class="all">Address</th>
                                <th class="all">Mobile</th>
                                <th class="all">Particular</th>
                                <th class="all">Quantity</th>
                                <th class="all">Return Value</th>
                                <th class="all">Carring</th>
                                <th class="all">Other Charges</th>
                                <th class="all">Total</th>
                                <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale_returns as $sales)
                                    @php
                                        $qty_summary = [];
                                        $filtered_products = $products->where('transaction_id', $sales->id);
                                    @endphp
                                    <tr>
                                        {{-- SL --}}
                                        <td class="text-center">{{$loop->iteration}}</td>

                                        {{-- Purchase Date --}}
                                        <td class="text-center">{{date('d-m-Y', strtotime($sales->sale_date))}}</td>

                                        {{-- Return Date --}}
                                        <td class="text-center">{{date('d-m-Y', strtotime($sales->date))}} </td>

                                        {{-- Customer Name --}}
                                        <td class="text-left">{{$sales->customer->name}}</td>

                                        {{-- Address --}}
                                        <td class="text-left">{{$sales->customer->address}}</td>

                                        {{-- Mobile --}}
                                        <td class="text-left">{{$sales->customer->phone ?? $sales->customer->mobile ?? ''}}</td>

                                        {{-- Particular --}}
                                        <td class="text-left">
                                            @foreach ($filtered_products as $product)
                                                @php
                                                    $type = $product->product->type;
                                                    $qty_summary['total'][$type] = $qty_summary['total'][$type] ?? 0;
                                                    $qty_summary['total'][$type] += $product->quantity;
                                                @endphp
                                                <p class="mb-0 text-left">
                                                    {{ $product->product_code}} -
                                                    {{ $product->product_name}} {{'('}}{{ $product->product->size->description}}{{')'}} -
                                                    {{ $product->quantity}} {{ trans_choice('labels.'.$type, ($product->quantity))}}{{' @ '}}{{ $product->unit_price}}/=
                                                    {{ $product->total_price}}/=
                                                </p>
                                            @endforeach
                                        </td>

                                        {{-- Quantity --}}
                                        <td class="text-center">
                                            @if(isset($qty_summary['total']))
                                            @foreach ($qty_summary['total'] as $type => $qty)
                                                {{$qty > 0 ? $qty . ' ' . trans_choice('labels.' . $type, $qty) : ''}}
                                            @endforeach
                                            @endif
                                        </td>

                                        {{-- Return Value --}}
                                        <td class="text-right">{{number_format($sales->total_price)}}/=</td>

                                        {{-- Carring --}}
                                        <td class="text-right">{{$sales->carring ? number_format($sales->carring) . '/=' : ''}}</td>

                                        {{-- Other Charges --}}
                                        <td class="text-right">{{$sales->other_charge ? number_format($sales->other_charge) . '/=' : ''}}</td>

                                        {{-- Total --}}
                                        <td class="text-right">
                                            @php
                                                $total = $sales->total_price + $sales->carring + $sales->other_charge;
                                            @endphp
                                            {{number_format($total)}}/=
                                        </td>

                                        {{-- Action --}}
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                        data-toggle="dropdown">
                                                        <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a href="{{ route('sales.delete', ['invoice' => $sales->id, 'view' => 'return']) }}" class="btn btn-danger" id="delete"><i class="fa fa-trash" ></i></a></li>
                                                    <li><a href="{{route('sales.return.view',$sales->id)}}" class="btn btn-info"><i class="fa fa-eye" ></i></a></li>
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
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $("#SaleReturnList").DataTable({
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

                return_value = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                total_carring = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                total_others = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                total_return_amount = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);


                // Update footer
                api.column(7).footer().innerHTML =
                    '<b>' + calculateSum(7) + '</b>';
                api.column(8).footer().innerHTML =
                    return_value ? '<b>' + fzNumberFormater(return_value) + '/=</b>' : '';
                api.column(9).footer().innerHTML =
                    total_carring ? '<b>' + fzNumberFormater(total_carring) + '/=</b>' : '';
                api.column(10).footer().innerHTML =
                    total_others ? '<b>' + fzNumberFormater(total_others) + '/=</b>' : '';
                api.column(11).footer().innerHTML =
                    total_return_amount ? '<b>' + fzNumberFormater(total_return_amount) + '/=</b>' : '';
            }
        });
    });
</script>
@endpush
