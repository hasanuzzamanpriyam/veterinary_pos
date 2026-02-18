@extends('layouts.admin')

@section('page-title')
Purchase List 1
@endsection

@section('main-content')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Purchase List-1</h2>
                <h6 class="text-dark mb-0 mr-auto">{{$supplier->company_name}}{{$supplier->address ? ' - '.$supplier->address : ''}}{{$supplier->mobile ? ' - '.$supplier->mobile : ''}}</h6>
                <a href="{{route('live.purchase.create')}}" class="btn btn-md btn-primary"> <i class="fa fa-plus"
                        aria-hidden="true"></i> Add New Purchase</a>
            </div>
        </div>
        <div class="x_content p-3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card-box purchase-list table-responsive">
                        <form action="{{route('supplier.purchase.list', ['id' => $supplier->id])}}" data-parsley-validate class="form-horizontal form-label-left">
                            @csrf
                            <input type="hidden" name="v" value="1">
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-6">
                                    <div class="input-group date" id="start_date">
                                        <input name="start_date" type="text" wire:model="start_date" class="form-control"
                                            placeholder="dd-mm-yyyy" value="{{$start_date}}">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-6">
                                    <div class="input-group date" id="end_date">
                                        <input name="end_date" type="text" wire:model="end_date" class="form-control"
                                            placeholder="dd-mm-yyyy" value="{{$end_date}}">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-6">
                                    <div class="form-group">
                                        <input type="submit" value="Search" class="form-control btn btn-success btn-sm">
                                    </div>
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-6">
                                    <div class="form-group">
                                        <a type="button" href="{{ route('supplier.purchase.list', ['id' => $supplier->id, 'v' => 1]) }}"
                                            class="form-control btn btn-danger btn-sm">Reset</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                        {{-- @dump($supplier_ledgers) --}}
                        <table id="fz_table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">

                            <thead>
                                <tr>
                                    <th class="all">Date</th>
                                    <th class="all">Invoice</th>
                                    <th class="all">Delivery Info</th>
                                    <th class="all">Particular</th>
                                    <th class="all">Purchase Qty</th>
                                    <th class="all">Weight</th>
                                    <th class="all">Purchase Tk</th>
                                    <th class="all">Balance</th>
                                    <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_amount = 0;
                                @endphp
                                @foreach($supplier_ledgers as $purchase)
                                    @php
                                        $qty_summary = [];
                                        $invoices = DB::table('supplier_transaction_details')->where('transaction_id', $purchase->id)->get();
                                        $purchase_value = $purchase->type == "return" ? -$purchase->total_price : $purchase->total_price - $purchase->price_discount;
                                        $row_bg = $purchase->type == "return" ? "#f3e1e1" : "#e1f3eb";
                                        $total_amount += $purchase_value;
                                        $t_type = $purchase->type;
                                    @endphp

                                    @foreach ($invoices as $invoice)
                                        {{-- @dd($invoice); --}}

                                            @php
                                                $product_info = DB::table('products')->where('id', $invoice->product_id)->first();

                                                if ($product_info) {
                                                    $type = $product_info->type;
                                                    $qty_summary['total'][$type] = $qty_summary['total'][$type] ?? 0;
                                                    $qty_summary['discount'][$type] = $qty_summary['discount'][$type] ?? 0;
                                                    $qty_summary['return_value'] = $qty_summary['return_value'] ?? 0;
                                                    $qty_summary['weight'] = $qty_summary['weight'] ?? 0;
                                                    $qty_summary['total'][$type] += $invoice->quantity;
                                                    $qty_summary['discount'][$type] += $invoice->discount_qty;
                                                    $qty_summary['return_value'] += $purchase->type == 'return' ? $invoice->total_price : 0;
                                                    $qty_summary['weight'] += $t_type == 'return' ? ($invoice->quantity * $invoice->weight) : ($t_type == 'purchase' ? ($invoice->quantity - $invoice->discount_qty) * $invoice->weight : 0);
                                                    $qty_summary['purchase'][$type] = $qty_summary['purchase'][$type] ?? 0;
                                                    $f_qty = ($invoice->quantity - $invoice->discount_qty);
                                                    $qty_summary['purchase'][$type] += $f_qty;
                                                } else {
                                                    // Log or handle missing product
                                                }
                                            @endphp

                                        @endforeach
                                    <tr data-type={{$t_type}} style="background-color: {{$row_bg}}">
                                        <td class="text-left">{{date('d-m-Y', strtotime($purchase->date))}}</td>


                                        <td class="text-left">{{$purchase->id}}</td>
                                        {{-- <td class="text-left">{{$purchase->warehouse->name ?? ''}}</td> --}}

                                        {{-- Transport Info --}}
                                        <td class="text-left">
                                            @if ($t_type == "purchase")
                                                {{$purchase->warehouse->name ?? ''}}
                                                {{$purchase->transport_no ? ' - ' . $purchase->transport_no : ''}}
                                                {{-- {{$purchase->delivery_man && $purchase->transport_no ? ", " : ''}}{{$purchase->delivery_man}} --}}
                                            @elseif ($t_type == "return")
                                                {{"Return"}}
                                            @endif
                                        </td>

                                        {{-- Particular --}}
                                        <td class="text-left">
                                            @php
                                                $filtered_products = $products->where('transaction_id', $purchase->id);
                                            @endphp
                                            @foreach ($products as $product)
                                                @php
                                                    $type = $product->product->type;
                                                @endphp
                                                @if($product->transaction_id == $purchase->id)
                                                    <p class="mb-0 text-left">
                                                        {{ $product->product_code}} -
                                                        {{ $product->product_name}} {{'('}}{{ $product->product->size->description}}{{')'}} -
                                                        {{ $product->quantity - $product->discount_qty}} {{ trans_choice('labels.'.$type, ($product->quantity - $product->discount_qty))}}{{' @ '}}{{ $product->unit_price}}/=
                                                        {{ $product->total_price}}/=
                                                    </p>
                                                @endif
                                            @endforeach
                                        </td>

                                        {{-- Purchase Qty --}}
                                        <td class="text-center">
                                            @if(isset($qty_summary['purchase']))
                                                {{ $purchase->type == 'return' ? '(-)' : ''}}
                                                @foreach ($qty_summary['purchase'] as $type => $qty)
                                                    {{$qty > 0 ? $qty . ' ' . trans_choice('labels.' . $type, $qty) : ''}}
                                                @endforeach
                                            @endif
                                        </td>

                                        {{-- Weight --}}
                                        <td class="text-center">{{ $purchase->type == 'return' ? '-' : ''}}{{$qty_summary['weight'] > 0 ? $qty_summary['weight']/1000 : 0}}</td>

                                        {{-- Purchase Value --}}
                                        <td class="text-right">{{number_format($purchase_value)}}/=</td>

                                        {{-- Balance --}}
                                        <td class="text-right">{{number_format($total_amount)}}/=</td>

                                        {{-- Action --}}
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2" data-toggle="dropdown">
                                                    <i class="fa fa-list"></i> <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a href="{{ route('purchase.delete', ['invoice' => $purchase->id, 'view' => $purchase->type]) }}" class="btn btn-danger" id="delete"><i class="fa fa-trash" ></i></a></li>
                                                    <li><a href="{{ route('purchase.view', ['invoice' => $purchase->id, 'view' => $purchase->type]) }}" class="btn btn-info"><i class="fa fa-eye" ></i></a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                </tr>
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
<script type="text/javascript">
    $('#start_date').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true
    });

    $('#end_date').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true
    });

    $(document).ready(function() {
        $("#fz_table").DataTable({
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

                total_purchase_amount = api
                    .column(6, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                // Update footer
                api.column(4).footer().innerHTML =
                    '<b>' + calculateTotals(4) + '</b>';
                api.column(5).footer().innerHTML =
                    '<b>' + calculateTotals(5) + '</b>';
                api.column(6).footer().innerHTML =
                    total_purchase_amount ? '<b>' + fzNumberFormater(total_purchase_amount) + '/=</b>' : '';
                api.column(7).footer().innerHTML =
                    total_purchase_amount ? '<b>' + fzNumberFormater(total_purchase_amount) + '/=</b>' : '';

            }
        });
    });
</script>
@endpush
