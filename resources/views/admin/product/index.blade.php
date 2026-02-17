@extends('layouts.admin')

@section('page-title')
Product List
@endsection

@section('main-content')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2 ">
                <h2 class="mr-auto">Product List</h2>
                <a href="{{route('live.product.create')}}" class="btn btn-md btn-primary"> <i class="fa fa-plus" aria-hidden="true"></i> Add Product</a>
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
                        <table id="prodcutList" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <th class="all">S.N.</th>
                                <th class="all">Code</th>
                                <th class="all">Name</th>
                                <th class="all">Brand</th>
                                <th class="all">Category</th>
                                <th class="all">Group</th>
                                <th class="all">Size</th>
                                <th class="all">Type</th>
                                <th class="all">Stock</th>
                                <th class="all">Weight</th>
                                <th class="all">TP Rate</th>
                                <th class="all">MRP Rate</th>
                                <th class="all">Sales Rate</th>
                                <th class="all">Offer</th>
                                <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    @php
                                        $stock_qty = isset($stock_list[$product->id]) ? $stock_list[$product->id]['qty'] : 0;
                                        $stocks_line = isset($stock_list[$product->id]) ? ($stock_list[$product->id]['qty'] * $product->size->name / 1000) . ' ' . trans_choice('labels.ton', $stock_list[$product->id]['qty']) : '0 Ton';
                                    @endphp
                                    <tr>
                                        {{-- S.N. --}}
                                        <td>{{$loop->iteration}}</td>

                                        {{-- Code --}}
                                        <td>{{$product->code}}</td>

                                        {{-- Name --}}
                                        <td class="text-left">{{$product->name}}</td>

                                        {{-- Brand --}}
                                        @if(empty($product->brand_id))
                                            <td></td>
                                        @else
                                            <td class="text-left">{{$product->brand->name}}</td>
                                        @endif

                                        {{-- Category --}}
                                        @if(empty($product->category_id))
                                            <td></td>
                                        @else
                                            <td class="text-left">{{$product->category->name ?? ''}}</td>
                                        @endif

                                        {{-- Group --}}
                                        @if(empty($product->group_id))
                                            <td></td>
                                        @else
                                            <td class="text-left">{{$product->productGroup->name}}</td>
                                        @endif

                                        {{-- Size --}}
                                        @if(empty($product->size_id))
                                            <td></td>
                                        @else
                                            <td>{{$product->size->name}} Kg</td>
                                        @endif

                                        {{-- Type --}}
                                        <td>{{ucfirst($product->type)}}</td>

                                        {{-- Stock --}}
                                        <td>{{$stock_qty }} {{trans_choice('labels.' . $product->type, $stock_qty)}}</td>

                                        {{-- M/T --}}
                                        @if(empty($product->size_id))
                                            <td></td>
                                        @else
                                            <td>{{$stocks_line}}</td>
                                        @endif

                                        {{-- Purches Rate --}}
                                        @if(empty($product->purchase_rate))
                                            <td></td>
                                        @else
                                            <td class="text-right">{{formatAmount($product->purchase_rate)}}/-</td>
                                        @endif

                                        {{-- MRP Rate --}}
                                        @if(empty($product->mrp_rate))
                                            <td></td>
                                        @else
                                            <td class="text-right">{{formatAmount($product->mrp_rate)}}/-</td>
                                        @endif

                                        {{-- Sales Rate --}}
                                        @if(empty($product->price_rate))
                                            <td></td>
                                        @else
                                            <td class="text-right">{{formatAmount($product->price_rate)}}/-</td>
                                        @endif

                                        {{-- Offer --}}
                                        <td class="text-right">
                                            @if(isset($product->active_offer) && $product->active_offer)
                                                @php $offer = $product->active_offer; @endphp
                                                @if($offer->type === \App\Models\ProductOffer::TYPE_PERCENTAGE)
                                                    {{ rtrim(rtrim(number_format($offer->value, 4, '.', ''), '0'), '.') }}%
                                                @else
                                                    {{ formatAmount($offer->value) }} /-
                                                @endif
                                                <div class="small text-muted">New: {{ formatAmount($product->sale_price_with_offer) }}/-</div>
                                            @endif
                                        </td>

                                        {{-- Action --}}
                                        <td>
                                            <div class="btn-group btn-group-vertical customer_diplay_list">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                    data-toggle="dropdown">
                                                    <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a href="{{route('product.edit',$product->id)}}" class="btn btn-success d-flex align-items-center gap-2"><i class="fa fa-edit" ></i><span>Edit</span></a></li>
                                                    <li><a href="{{route('product.view',$product->id)}}" class="btn btn-info d-flex align-items-center gap-2"><i class="fa fa-eye" ></i><span>View</span></a></li>
                                                    <li> <a href="{{route('product.delete',$product->id)}}" class="btn btn-danger d-flex align-items-center gap-2" id="delete"><i class="fa fa-trash" ></i><span>Delete</span></a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Single product view by modal --}}
 <!-- Large modal -->
 {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".product-view">Large modal</button> --}}

 <div class="modal fade product-view" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Product Info</h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Text in a modal</h4>
                    </div>
                    <div class="card-body">


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
        $("#prodcutList").DataTable({
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

                // sale_amount = api
                //     .column(12, { page: 'current' })
                //     .data()
                //     .reduce((a, b) => intVal(a) + intVal(b), 0);

                // total_discount_amount = api
                //     .column(13, { page: 'current' })
                //     .data()
                //     .reduce((a, b) => intVal(a) + intVal(b), 0);

                // total_sale_amount = api
                //     .column(14, { page: 'current' })
                //     .data()
                //     .reduce((a, b) => intVal(a) + intVal(b), 0);


                // // Update footer
                // api.column(8).footer().innerHTML =
                //     '<b>' + calculateSum(8) + '</b>';
                // api.column(9).footer().innerHTML =
                //     '<b>' + calculateSum(9) + '</b>';
                // api.column(10).footer().innerHTML =
                //     '<b>' + calculateSum(10) + '</b>';
                // api.column(11).footer().innerHTML =
                //     '<b>' + calculateSum(11) + '</b>';
                // api.column(12).footer().innerHTML =
                //     sale_amount ? '<b>' + fzNumberFormater(sale_amount) + '/=</b>' : '';
                // api.column(13).footer().innerHTML =
                //     total_discount_amount ? '<b>' + fzNumberFormater(total_discount_amount) + '/=</b>' : '';
                // api.column(14).footer().innerHTML =
                //     total_sale_amount ? '<b>' + fzNumberFormater(total_sale_amount) + '/=</b>' : '';
            }
        });
    });
</script>
@endpush
