@extends('layouts.admin')

@section('page-title')
    Sales 2 List
@endsection

@section('main-content')
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title p-3">
                <div class="header-title d-flex align-items-center gap-2">
                    <h2>Sales List-2</h2>
                    <ul class="nav navbar-right">
                        <li><span class="collapse-link btn btn-md btn-primary text-white"><i class="fa fa-eye"></i> Advance</span>
                        </li>
                    </ul>
                    <h5 class="mr-auto mb-0">{{ $customer->name }} - {{ $customer->address }} - {{ $customer->mobile }} </h5>
                </div>
            </div>
            <div class="p-3">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 ">
                        <form action="{{ route('customer.sales.report2', ['id' => $id]) }}" method="get" data-parsley-validate
                            class="form-horizontal form-label-left x_content" style="display: none;">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-6">
                                            <div class="form-group">
                                                <input type="date" id="start_date" name="start_date" value="{{ $request->start_date ?? '' }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-6">
                                            <div class="form-group ">
                                                <input type="date" id="end_date" name="end_date" value="{{ $request->end_date ?? '' }}" class="form-control">
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
                            <table id="datatable-responsiveeee"
                                class="table table-striped table-bordered dt-responsive nowrap"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="all">Date</th>
                                        <th class="all">Invoice</th>
                                        <th class="all">Store/Warehouse</th>
                                        <th class="all">Transport</th>
                                        <th class="all">Particular</th>
                                        <th class="all">Quantity</th>
                                        <th class="all">Dis. Qty</th>
                                        <th class="all">Return Qty</th>
                                        <th class="all">Sales Qty</th>
                                        <th class="all">Total Value</th>
                                        <th class="all">Discount</th>
                                        <th class="all">Vat</th>
                                        <th class="all">Carring</th>
                                        <th class="all">Others</th>
                                        <th class="all">Sale Value</th>
                                        <th class="all">Balance Tk</th>
                                        <th class="all">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_sale_value = 0;
                                    @endphp

                                    @foreach ($customer_ledger as $sales)

                                        @php
                                            $qty_summary = [];
                                            $line_total = 0;
                                            $invoices = $transactionDetails->where('transaction_id', $sales->id);
                                            // $line_total = $sales->total_price - $sales->price_discount + $sales->vat + $sales->carring + $sales->other_charge - $return_line_total;
                                            $line_total = $sales->total_price - $sales->price_discount + $sales->vat + $sales->carring + $sales->other_charge;
                                            // $total_sale_value += $line_total;
                                            $total_sale_value += $sales->type == 'return' ? -$line_total : $line_total;

                                        @endphp

                                        <tr style="background-color: {{ $sales->type == 'return' ? '#f3e1e1' : '#e1f3eb' }}" data-type="{{ $sales->type }}">
                                            {{-- Date --}}
                                            <td class="text-center">{{ date('d-m-Y', strtotime($sales->date)) }} </td>

                                            {{-- Invoice --}}
                                            <td class="text-center">{{ $sales->id }}</td>

                                            {{-- Store --}}
                                            <td class="text-center">{{ $sales->store->name }}</td>

                                            {{-- Transport --}}
                                            <td class="text-left">
                                                {{ $sales->transport_no }}{{ $sales->delivery_man ? ' - ' . $sales->delivery_man : '' }}
                                            </td>

                                            {{-- Particular --}}
                                            <td class="text-left">
                                                @foreach ($invoices as $invoice)

                                                    @php
                                                        $type = $invoice->product->type;
                                                        if ( $sales->type == 'return' ) {
                                                            $qty_summary['return'][$type] = $qty_summary['return'][$type] ?? 0;
                                                            $qty_summary['return'][$type] += $invoice->quantity;
                                                            $qty_summary['sale'][$type] = $qty_summary['sale'][$type] ?? 0;
                                                            $qty_summary['sale'][$type] += $invoice->quantity;

                                                        }else{
                                                            $qty_summary['total'][$type] = $qty_summary['total'][$type] ?? 0;
                                                            $qty_summary['total'][$type] += $invoice->quantity;
                                                            $qty_summary['sale'][$type] = $qty_summary['sale'][$type] ?? 0;
                                                            $qty_summary['sale'][$type] += ($invoice->quantity - $invoice->discount_qty);
                                                        }
                                                        $qty_summary['discount'][$type] = $qty_summary['discount'][$type] ?? 0;
                                                        $qty_summary['discount'][$type] += $invoice->discount_qty;
                                                        @endphp

                                                    @if($invoice->transaction_id == $sales->id)
                                                        <p class="mb-0">
                                                            {{ $invoice->product_code }} -
                                                            {{ $invoice->product_name }} -
                                                            {{'('}}{{ $invoice->product->size->description}}{{')'}} -
                                                            {{ $invoice->quantity - $invoice->discount_qty}} {{ trans_choice('labels.'.$type, ($invoice->quantity - $invoice->discount_qty))}} {{' @ '}}
                                                            {{ $invoice->unit_price }}/=
                                                            {{ $invoice->total_price }}/=
                                                        </p>
                                                    @endif

                                                @endforeach
                                            </td>

                                            {{-- Quantity --}}
                                            <td class="text-center">
                                                @if (isset($qty_summary['total']))
                                                    @foreach ($qty_summary['total'] as $type => $qty)
                                                        {{$qty > 0 ? $qty . ' ' . trans_choice('labels.' . $type, $qty) : ''}}
                                                    @endforeach
                                                @endif
                                            </td>

                                            {{-- Dis. Qty --}}
                                            <td class="text-center">
                                                @foreach ($qty_summary['discount'] as $type => $qty)
                                                    {{$qty > 0 ? $qty . ' ' . trans_choice('labels.' . $type, $qty) : ''}}
                                                @endforeach
                                            </td>

                                            {{-- Return Qty --}}
                                            <td class="text-center">
                                                @if (isset($qty_summary['return']))
                                                    @foreach ($qty_summary['return'] as $type => $qty)
                                                        {{$qty > 0 ? $qty . ' ' . trans_choice('labels.' . $type, $qty) : ''}}
                                                    @endforeach
                                                @endif
                                            </td>

                                            {{-- Sale Qty --}}
                                            <td class="text-center">
                                                {{-- @if ($sales->type == 'return')
                                                -
                                                @endif --}}
                                                @foreach ($qty_summary['sale'] as $type => $qty)
                                                    {{$qty > 0 ? $qty . ' ' . trans_choice('labels.' . $type, $qty) : ''}}
                                                @endforeach
                                            </td>

                                            {{-- Total Value --}}
                                            <td class="text-right">
                                                {{ $sales->total_price }}/=
                                            </td>

                                            {{-- Discount --}}
                                            <td class="text-right">
                                                {{ $sales->price_discount > 0 ? $sales->price_discount . '/=' : ''}}
                                            </td>

                                            {{-- Vat --}}
                                            @if (empty($sales->vat))
                                                <td></td>
                                            @else
                                                <td class="text-right">{{ $sales->vat }}/=</td>
                                            @endif

                                            {{-- Carring --}}
                                            @if (empty($sales->carring))
                                                <td></td>
                                            @else
                                                <td class="text-right">{{ $sales->carring }}/=</td>
                                            @endif

                                            {{-- Others --}}
                                            @if (empty($sales->other_charge))
                                                <td></td>
                                            @else
                                                <td class="text-right">{{ $sales->other_charge }}/=</td>
                                            @endif

                                            {{-- Sale Value --}}
                                            <td class="text-right">
                                                {{ $line_total }}/=
                                            </td>

                                            {{-- Balance Tk --}}
                                            <td class="text-right">
                                                {{ $total_sale_value }}/=
                                            </td>

                                            {{-- Action --}}
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                        data-toggle="dropdown">
                                                        <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="{{ route('sales.delete', ['invoice' => $sales->id, 'view' => $sales->type]) }}"
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
                                    <td></td>
                                    <td></td>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <script>
        $(document).ready(function () {
            $('#get_customer_id').select2();
            // $('#datatable-responsiveeee').DataTable({
            //     ordering: false
            // });
            $("#datatable-responsiveeee").DataTable({
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
                            const value = intVal($(row).find('td').eq(9).text());
                            const operation = $(row).attr('data-type');
                            if (operation === 'sale') {
                                return total + value;
                            } else if (operation === 'return') {
                                return total - value;
                            }
                            return total;
                        }, 0);
                    total_discount_amount = api
                        .column(10, { page: 'current' })
                        .data()
                        .reduce((a, b) => intVal(a) + intVal(b), 0);
                    total_vat_amount = api
                        .column(11, { page: 'current' })
                        .data()
                        .reduce((a, b) => intVal(a) + intVal(b), 0);
                    total_carring = api
                        .column(12, { page: 'current' })
                        .data()
                        .reduce((a, b) => intVal(a) + intVal(b), 0);
                    total_others = api
                        .column(13, { page: 'current' })
                        .data()
                        .reduce((a, b) => intVal(a) + intVal(b), 0);
                    total_tk = api
                        .rows({ page: 'current' })
                        .nodes()
                        .toArray()
                        .reduce((total, row) => {
                            const value = intVal($(row).find('td').eq(14).text());
                            const operation = $(row).attr('data-type');
                            if (operation === 'sale') {
                                return total + value;
                            } else if (operation === 'return') {
                                return total - value;
                            }
                            return total;
                        }, 0);


                    // Update footer
                    api.column(5).footer().innerHTML =
                        '<b>' + calculateTotals(5) + '</b>';
                    api.column(6).footer().innerHTML =
                        '<b>' + calculateSum(6) + '</b>';
                    api.column(7).footer().innerHTML =
                        '<b>' + calculateSum(7) + '</b>';
                    api.column(8).footer().innerHTML =
                        '<b>' + calculateTotals(8) + '</b>';
                    api.column(9).footer().innerHTML =
                        total_sale_amount > 0 ? '<b>' + total_sale_amount + '/=</b>' : '' ;
                    api.column(10).footer().innerHTML =
                        total_discount_amount > 0 ? '<b>' + total_discount_amount + '/=</b>' : '';
                    api.column(11).footer().innerHTML =
                        total_vat_amount > 0 ? '<b>' + total_vat_amount + '/=</b>' : '';
                    api.column(12).footer().innerHTML =
                        total_carring > 0 ? '<b>' + total_carring + '/=</b>' : '';
                    api.column(13).footer().innerHTML =
                        total_others > 0 ? '<b>' + total_others + '/=</b>' : '';
                    api.column(14).footer().innerHTML =
                        total_tk > 0 ? '<b>' + total_tk + '/=</b>' : '';
                    api.column(15).footer().innerHTML =
                        total_tk > 0 ? '<b>' + total_tk + '/=</b>' : '';
                }
            });
        });
    </script>

@endpush
