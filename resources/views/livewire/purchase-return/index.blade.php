@section('page-title', 'Purchase Return Entry')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Purchase Return Entry</h2>
                <a href="{{ route('purchase.return.index') }}" class="mr-auto ml-3 cursor-pointer"><i class="fa fa-close"></i></a>
            </div>
        </div>
        <div class="x_content p-3">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row purchase_return_entry_form_row">
                <div class="left-area col-lg-8 col-md-7 col-sm-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-6">
                            <div class="row justify-content-end">
                                <div class="search-area col-lg-12 col-md-12 col-sm-12 text-left py-3">
                                    <div wire:ignore class="row">
                                        <div class="col-md-4">
                                            <label class="py-1 border entry-lebel" for="supplier_name">Supplier</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="form-control" id="supplier-search">
                                                <option value="">Select Supplier</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">
                                                            {{$supplier->company_name}}  -
                                                            {{$supplier->address}} -
                                                            {{$supplier->mobile}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>{{--end supplier select area --}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <form wire:submit.prevent="supplierInfo()" enctype="multipart/form-data"  data-parsley-validate class="fz-entry-form form-horizontal form-label-left purchase_return_entry_form">
                                @csrf
                                <!--Start supplier area-->
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class=" py-1 border" for="supplier_name">Supplier Name</label>
                                            <input type="text"  name="supplier_name"    wire:model="supplier_name" value="{{$supplier_name}}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class=" py-1 border" for="transport_no">Address</label>
                                            <input type="text"  name="address"    wire:model="address" value="{{$address}}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class=" py-1 border" for="transport_no">Mobile</label>
                                            <input type="text" name="mobile" id="mobile" wire:model="mobile"  class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group ">
                                            <label class="border py-1" for="date">Date</label>
                                            <div class="input-group date" id="date_picker">
                                                <input name="date" wire:model="date" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-th"></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group ">
                                            <label class="border py-1" for="date">Return Date</label>
                                            <div class="input-group date" id="return_date_picker">
                                                <input name="return_date" wire:model="return_date" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-th"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class=" py-1 border" for="remarks">Remarks</label>
                                            <input type="text" name="remarks" wire:model="remarks" value="{{$remarks}}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        @php $stores = DB::table('stores')->get(); @endphp
                                        <div class="form-group">
                                            <label class="py-1 border" class="" for="product_store_name">Store</label>
                                            <select name="product_store_name" id="product_store_name" wire:model="product_store_name" class="form-control">
                                                <option value="">Select Store</option>
                                                @foreach($stores as $store)
                                                    <option value="{{$store->name}}">{{$store->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        @php $warehouses = DB::table('warehouses')->get(); @endphp
                                        <div class="form-group">
                                            <label class=" py-1 border" for="warehouse_name">Warehouse</label>
                                            <select name="warehouse_name" id="warehouse_name" wire:model="warehouse_name" class="form-control">
                                                <option value="">Select Warehouse</option>
                                                @foreach($warehouses as $warehouse)
                                                    <option value="{{$warehouse->name}}">{{$warehouse->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label  class="py-1 border" for="delivery_man">Gari No/Delivery Man</label>
                                            <input type="text" name="delivery_man" id="delivery_man" wire:model="delivery_man" class="form-control">
                                        </div>
                                    </div>
                                </div>



                                <!--End supplier area-->

                                <!--Start Search Product area-->
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-6">
                                        <div class="row justify-content-end">
                                            <div class="search-area col-lg-12 col-md-12 col-sm-12 text-left py-3 purchase_return_entry_supplier_col">
                                                <div wire:ignore.self class="row">
                                                    <div class="col-md-4">
                                                        <label class="py-1 border entry-lebel" for="supplier_name">Product</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <select class="form-control" id="product-search">
                                                            <option class="text-left" value="">Select Products</option>
                                                            @if(isset($products))
                                                                @foreach ($products as $product)
                                                                    <option class="text-left p-2" value="{{ $product->product_id }}">
                                                                            {{$product->product->code}} -
                                                                            {{$product->product->name}} -
                                                                            {{$product->quantity}} {{trans_choice('labels.'.$product->product->type, $product->quantity)}} -
                                                                            {{$product->unit_price}}/=
                                                                    </option>
                                                                @endforeach
                                                                @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">

                                        {{-- Cart product table --}}

                                        <table class="table table-striped table-bordered fz-sales-entry" cellspacing="0" width="100%" >
                                            <thead>
                                                <tr class="text-center">
                                                    <th class="all" style="width: 70px; min-width: 70px; text-align: center">Code</th>
                                                    <th class="all" style="width: 100%;">Name</th>
                                                    <th class="all" style="width: 110px; min-width: 110px; text-align: center">Purchased (Q)</th>
                                                    <th class="all" style="width: 92px; min-width: 92px; text-align: center">Return (Q)</th>
                                                    <th class="all" style="width: 80px; min-width: 80px; text-align: center">Rate</th>
                                                    <th class="all">Sub Total</th>
                                                    <th class="all">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                            $total_amount = 0;
                                                $items = 0;
                                                $type = 0;
                                                $purchase_total =0;
                                                $total_qty = 0;
                                                $discount = 0;
                                                $items = 0;
                                                // $type = 0;
                                            @endphp
                                            @if(Cart::instance('purchase_return')->count() == 0)

                                            @else
                                                @forelse (Cart::instance('purchase_return')->content() as $product)
                                                    {{-- @dump($product) --}}
                                                    @php
                                                        $total_qty+=$product->qty;
                                                        $discount+=$product->options->discount;
                                                        $type = $product->options->type;
                                                        $purchase_total += $product->qty - $product->options->discount;
                                                        $total_amount +=($product->qty - $product->options->discount)*$product->price;
                                                        $items ++;
                                                        // $type = $product->options->type;
                                                        $id= $product->id;
                                                        $summary['qty'][$type] = $summary['qty'][$type] ?? 0;
                                                        $summary['qty'][$type] += $product->qty;
                                                        $summary['purchased_qty'][$type] = $summary['purchased_qty'][$type] ?? 0;
                                                        $summary['purchased_qty'][$type] += $product->options->purchased_qty;
                                                    @endphp
                                                    <tr class="text-center">
                                                        {{-- Code --}}
                                                        <td>
                                                            <div class="d-flex flex-column align-items-start">
                                                                <span>{{ $product->options->code }}</span>
                                                                @if($product->options->barcode)
                                                                    <svg class="barcode-render" data-barcode="{{ $product->options->barcode }}"
                                                                        style="height: 25px; margin-top: 4px; max-width: 100%;"></svg>
                                                                @endif
                                                            </div>
                                                        </td>

                                                        {{-- Name --}}
                                                        <td>{{$product->name}}</td>

                                                        {{-- Purchased Quantity --}}
                                                        <td>{{$product->options->purchased_qty}} {{trans_choice('labels.'.$product->options->type, $product->options->purchased_qty)}}</td>

                                                        {{-- Return Quantity --}}
                                                        <td>
                                                            <div class="input-group">
                                                                <input type="text"  wire:model="quantities" wire:change="updateQuantity({{$id}},{{ $purchase_invoice_no }}, $event.target.value)" value="{{$product->qty}}"class="form-control">
                                                                <small class="text-danger"><strong>{!!$product->options->stock == 0 ? '<span class="text-danger text-center">Unavailable</span>' : '' !!}</strong></small>
                                                            </div>
                                                        </td>

                                                        {{-- Rate --}}
                                                        <td class="text-right">
                                                            <div class="input-group">
                                                                <input type="text" @readonly(true) wire:model="update_price" wire:change="updatePrice({{$id}}, $event.target.value)" value="{{$product->price}}" class="form-control">
                                                            </div>
                                                        </td>

                                                        {{-- Sub-Total --}}
                                                        <td class="text-right">{{$product->price*$product->qty}}/-</td>

                                                        {{-- Action --}}
                                                        <td>
                                                            <div class="input-group justify-content-center">
                                                                <button type="button" class="btn btn-danger btn-sm" wire:click="itemRemove('{{$product->rowId}}')" ><i class="fa fa-trash" ></i></button>
                                                            </div>
                                                        </td>

                                                    </tr>

                                                @empty
                                                    <tr>
                                                        <h5 class="text-center">No Data Found!</h5>
                                                    </tr>
                                                @endforelse
                                                @endif
                                                <tr class="text-left">
                                                    {{-- Code --}}
                                                    <td>
                                                        <div class="d-flex justify-content-start">
                                                            <span><strong>{{trans_choice('labels.items', $items)}}:</strong>
                                                                {{ $items }}</span>
                                                        </div>
                                                    </td>

                                                    {{-- Name --}}
                                                    <td></td>

                                                    {{-- Purchased Quantity --}}
                                                    <td>
                                                        <div>
                                                            @if( isset($summary['purchased_qty']) && $summary['purchased_qty'] > 0)
                                                                @foreach ($summary['purchased_qty'] as $key => $value)
                                                                    <span class="d-inline-block"><strong>{{ $value }}</strong> <span class="ttl">{{trans_choice('labels.'.strtolower($key), $value)}}</span></span>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </td>

                                                    {{-- Return Quantity --}}
                                                    <td>
                                                        <div>
                                                            @if( isset($summary['qty']) && $summary['qty'] > 0)
                                                                @foreach ($summary['qty'] as $key => $value)
                                                                    <span class="d-inline-block"><strong>{{ $value }}</strong> <span class="ttl">{{trans_choice('labels.'.strtolower($key), $value)}}</span></span>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </td>

                                                    {{-- Sub Total --}}
                                                    <td colspan="2">
                                                        <div class="d-flex justify-content-end">
                                                            <span><strong>TK:</strong>
                                                                {{ $total_amount }}/=</span>
                                                        </div>
                                                    </td>

                                                    {{-- Action --}}
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div><!--End Product area-->
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="justify-content-center d-flex" style="gap: 10px">
                                            <button type="button" wire:click="cancel" class="btn btn-danger btn-md">Cancel</button>
                                            <input type="submit" value="Checkout" class="btn btn-primary btn-md">
                                            <a type="button" href="{{url('/dashboard')}}" class="btn btn-info btn-md">Hold</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="right-area col-lg-4 col-md-5 col-sm-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="x_panel product-thumb-gallery">
                                <div class="x_title">
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content p-3">
                                    <div class="row">
                                        @if(isset($products))
                                            @foreach($products as $product)
                                                <div class="col-lg-4 col-md-4 col-sm-12">
                                                    <div class="thumbnail" wire:click.prevent="sessionStore({{$product->product_id}})">
                                                        <div class="image view view-first">

                                                            @if(empty($product->product->photo))
                                                                <h5 my-auto>Opps No Image Found!</h5>
                                                            @else
                                                                <img style="width: 100%; display: block;" src="{{asset($product->product->photo)}}" alt="image" />
                                                            @endif
                                                            <div class="mask">
                                                                <p>
                                                                <span>৳.{{$product->product->price_rate}}/=</span>
                                                                {{-- <span class="text-wrap">{{$product->product->name}}</span> --}}
                                                                <span class="badge badge-info text-light">Qty: {{ $product->quantity ?? 0 }} {{ trans_choice('labels.' . $product->product->type, $product->quantity ?? 0) }}</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="caption">
                                                            @if(empty($product->product->brand_id))
                                                            @else
                                                                <small>{{$product->product->brand->name}}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <h4 class="m-auto py-5 text-danger">Please select a supplier and purchase date then add your necessary product from here</h4>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
       $('#supplier-search').select2();
       $('#supplier-search').on('change', function (e) {
           var data = $('#supplier-search').select2("val");
           @this.set('supplier_search', data);
       });

       $('#product-search').select2();
       $('#product-search').on('change', function (e) {
            @this.sessionStore(e.target.value);
       });


        $(document).on('dataUpdated', function () {
            const timeout = setTimeout(() => {
                $('#product-search').select2();
                clearTimeout(timeout);
            }, 10);
        })

        $('#date_picker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });
        $('#date_picker input[name=date]').on('change', function(e) {
            @this.set('date', e.target.value);
        });
        $('#return_date_picker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });
        $('#return_date_picker input[name=return_date]').on('change', function(e) {
            @this.set('return_date', e.target.value);
        });

    });
    </script>
@endpush
