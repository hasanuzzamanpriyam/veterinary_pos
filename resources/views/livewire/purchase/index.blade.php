@section('page-title', 'Purchase Entry')
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Purchase Entry</h2>
                <a href="{{ route('purchase.index', ['view' => 'v1']) }}" class="mr-auto ml-3 cursor-pointer"><i class="fa fa-close"></i></a>
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
            <div class="row purchase_entry_form_row">
                <div class="col-lg-8 col-md-7 col-sm-12">

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-6">
                            <div class="row justify-content-end">
                                <div class="search-area col-lg-12 col-md-12 col-sm-12 text-left py-3">
                                    <div wire:ignore class="row">
                                        <div class="col-md-4">
                                            <label class="py-1 border entry-lebel purchase_entry_lebel" for="supplier_name">Supplier</label>
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
                    <form wire:submit.prevent="supplierInfo()" enctype="multipart/form-data"  data-parsley-validate class="form-horizontal form-label-left purchase_entry_form">
                        <div class="row">
                            @csrf
                            <!--Start supplier area-->
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="py-1 border purchase_entry_lebel" for="supplier_name">Supplier Name</label>
                                    <input type="text"  name="supplier_name"   wire:model.defer="supplier_name"  class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label  class="py-1 border purchase_entry_lebel" for="transport_no">Address</label>
                                    <textarea type="text" name="address" id="address"   wire:model.defer="address" class="form-control" cols="5" rows="1"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label  class="py-1 border purchase_entry_lebel" for="transport_no">Mobile</label>
                                    <input type="text" name="mobile" id="mobile" wire:model.defer="mobile"   class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group ">
                                    <label class="border py-1 purchase_entry_lebel" for="date">Date</label>
                                    <div class="input-group date" id="purchase_date_picker">
                                        <input name="date" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label  class="py-1 border purchase_entry_lebel" for="warehouse_id">Warehouse</label>
                                    <select type="text" wire:model="warehouse_id" wire:change="warehouseSearch($event.target.value)"  name="warehouse_id"  class="form-control">
                                        <option value="">Select Option</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="py-1 border purchase_entry_lebel" for="product_store_id">Store Name</label>
                                    <select type="text" wire:model="product_store_id"  name="product_store_id"  class="form-control">
                                        <option value="">Select Option</option>
                                        @foreach($stores as $store)
                                            <option value="{{$store->id}}">{{$store->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label   class="py-1 border purchase_entry_lebel" for="prepare">Gari Number</label>
                                    <input type="text" name="transport_no" wire:model="transport_no" id="transport_no" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label  class="py-1 border purchase_entry_lebel" for="delivery_man">Delivery Man</label>
                                    <input type="text" name="delivery_man" id="delivery_man" wire:model="delivery_man" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label  class="py-1 border purchase_entry_lebel" for="supplier_remarks">Remarks</label>
                                    <input type="text" name="supplier_remarks" wire:model.defer="supplier_remarks"  class="form-control">
                                </div>
                            </div>

                            <!--End supplier area-->

                            <!--Start Search Product area-->
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="row justify-content-end">
                                    <div class="search-area col-lg-12 col-md-12 col-sm-12 text-left py-3">  {{-- start product select area --}}
                                        <div wire:ignore class="row">
                                            <div class="col-md-4">
                                                <label class="py-1 border purchase_entry_lebel" for="supplier_name">Product</label>
                                            </div>
                                            <div class="col-md-8">
                                                <select class="form-control text-center " id="product-search">
                                                    <option value="">Select Products</option>
                                                        @foreach ($products as $product)
                                                            @php
                                                                $line_stock_qty = isset($store_stocks[$product->id]) ? $store_stocks[$product->id]['qty'] : 0;
                                                            @endphp
                                                            <option value="{{ $product->id }}">
                                                                    {{$product->code}} -
                                                                    {{$product->name}} -
                                                                    {{$line_stock_qty}} {{trans_choice( 'labels.' . $product->type, $line_stock_qty )}} -
                                                                    {{$product->purchase_rate}}/=
                                                            </option>
                                                        @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>{{--end product select area --}}
                                </div>



                                {{-- Cart product table --}}

                                <table  class="table table-bordered table-sales-entry" cellspacing="0" width="100%" >
                                    <thead>
                                        <tr class="text-center">
                                            <th class="all smaller"
                                                style="width: 70px; min-width: 70px; text-align: center">Code</th>
                                            <th class="all bigger" style="width: 100%">Name</th>
                                            <th class="all"
                                                style="width: 92px; min-width: 92px; text-align: center">Quantity</th>
                                            <th class="all"
                                                style="width: 70px; min-width: 70px; text-align: center">Discount</th>
                                            <th class="all" style="width: 125px;min-width: 125px;">Purchase(Q)</th>
                                            <th class="all"
                                                style="width: 90px; min-width: 90px;; text-align: center">Rate</th>
                                            <th class="all bigger"
                                                style="width: 90px; min-width: 90px;; text-align: center">Sub Total</th>
                                            <th class="all" style="width: 100px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_amount = 0;
                                            $total_qty = 0;
                                            $discount = 0;
                                            $items = 0;
                                            $type = 0;
                                            $purchase_total =0;
                                        @endphp
                                        @if(Cart::instance('purchase')->content()->count() > 0)
                                            @forelse (Cart::instance('purchase')->content() as $product)
                                                @php
                                                    $qty = $product->qty ? $product->qty : 0;
                                                    $dis_qty = $product->options->discount ? $product->options->discount : 0;
                                                    $total_qty+= $qty;
                                                    $discount+=$dis_qty;
                                                    $type = $product->options->type;
                                                    $purchase_total += $qty - $dis_qty;
                                                    $total_amount +=($qty - $dis_qty)*$product->price;
                                                    $items ++;
                                                    $id= $product->id;
                                                    $summary['qty'][$type] = $summary['qty'][$type] ?? 0;
                                                    $summary['discount'][$type] = $summary['discount'][$type] ?? 0;
                                                    $summary['total'][$type] = $summary['total'][$type] ?? 0;
                                                    $summary['qty'][$type] += $qty;
                                                    $summary['discount'][$type] += $dis_qty;
                                                    $summary['total'][$type] += $qty - $dis_qty;
                                                @endphp
                                                <tr class="text-center sales-entry">
                                                    <td>{{$product->options->code}}</td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <span>{{ $product->name }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group justify-content-center">
                                                            <input type="text"
                                                                wire:model="quantities"
                                                                wire:change="updateQuantity({{$id}}, $event.target.value)"
                                                                value="{{$qty}}"class="form-control purchase-entry-qty">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group justify-content-center">
                                                            <input type="number"
                                                            wire:model="discounts"
                                                            wire:change="updateDiscount({{$id}}, $event.target.value)"
                                                            value="{{$dis_qty}}" class="form-control">
                                                        </div>
                                                    </td>

                                                    <td class="purchase-qty">
                                                        <div class="d-flex justify-content-center flex-row gap-2">
                                                            <input type="text"
                                                                @disabled(true)
                                                                value="{{ $qty - $dis_qty }}"
                                                                class="form-control">
                                                            <span>{{ trans_choice('labels.' . strtolower($product->options->type), ($qty - $dis_qty)) }}</span>
                                                        </div>
                                                    </td>

                                                    <td class="text-right">
                                                        <div class="input-group">

                                                            <input type="text"
                                                                wire:model="update_price"
                                                                wire:change="updatePrice({{ $id }}, $event.target.value || 0)"
                                                                value="{{ $product->price }}"
                                                                class="form-control">
                                                        </div>
                                                    </td>

                                                    <td class="sub-total">
                                                        <div
                                                            class="input-group justify-content-center">
                                                            <input type="text"
                                                                @disabled(true)
                                                                value="{{ $product->price * ($qty - $dis_qty) }}/-"
                                                                class="form-control">
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div
                                                            class="input-group justify-content-center">
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm m-0"
                                                                wire:click="itemRemove('{{ $product->rowId }}')"><i
                                                                    class="fa fa-trash"></i></button>
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
                                            <td>
                                                <div class="d-flex justify-content-start">
                                                    <span><strong>{{trans_choice('labels.items', $items)}}:</strong>
                                                        {{ $items }}</span>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td>
                                                <div>
                                                    @if( isset($summary['qty']) && $summary['qty'] > 0)
                                                        @foreach ($summary['qty'] as $key => $value)
                                                            <span class="d-inline-block"><strong>{{ $value }}</strong> <span class="ttl">{{trans_choice('labels.'.strtolower($key), $value)}}</span></span>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    @if( isset($summary['discount']) && $summary['discount'] > 0)
                                                        @foreach ($summary['discount'] as $key => $value)
                                                            <span class="d-inline-block"><strong>{{ $value }}</strong> <span class="ttl">{{trans_choice('labels.'.strtolower($key), $value)}}</span></span>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    @if( isset($summary['total']) && $summary['total'] > 0)
                                                        @foreach ($summary['total'] as $key => $value)
                                                            <span class="d-inline-block"><strong>{{ $value }}</strong> <span class="ttl">{{trans_choice('labels.'.strtolower($key), $value)}}</span></span>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                            <td colspan="2">
                                                <div class="d-flex justify-content-end">
                                                    <span><strong>TK:</strong>
                                                        {{ $total_amount }}/=</span>
                                                </div>
                                            </td>
                                            <td></td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div><!--End Product area-->

                            <div class="col-md-12">
                                <div class="justify-content-center d-flex" style="gap: 10px">

                                    <button type="button" wire:click="cancel" class="btn btn-danger btn-md">Cancel</button>
                                    <input type="submit" @if ($items == 0) disabled @endif value="Checkout" class="btn btn-primary btn-md">
                                    <a type="button" href="{{url('/dashboard')}}" class="btn btn-info btn-md">Hold</a>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>


                <div class="col-lg-4 col-md-5 col-sm-12">
                    <div class="">
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="x_panel product-thumb-gallery">
                                    <div class="x_title">
                                        <ul class="nav navbar-right panel_toolbox">
                                            <li>
                                                <button class="btn btn-md btn-link-primary" wire:click="toggleSidebar">
                                                    @if ($showSidebar)
                                                        <i class="fa fa-chevron-up"></i> Hide
                                                    @else
                                                        <i class="fa fa-eye-slash"></i> Show
                                                    @endif
                                                </button>
                                            </li>
                                        </ul>
                                        <div class="clearfix"></div>

                                    </div>

                                    <div class="x_content p-3" @if (!$showSidebar) style="display: none" @endif>
                                        <div class="row">
                                            <div class="col-lg-5 col-md-5 col-sm-12">
                                                <div class="input-group">
                                                <select name="brand_id" id="brand_id" wire:change="brandSearch($event.target.value)"  type="text" class="form-control">
                                                    <option value="0">All Brand</option>
                                                    @foreach($brands as  $brand)
                                                        <option value="{{$brand->id}}">{{$brand->name}}</option>
                                                    @endforeach
                                                </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-7 col-md-7 col-sm-12">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" wire:model.live="search" placeholder="Search for...">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pt-4">
                                            @if (isset($products_grid))
                                                @foreach($products_grid as $product)
                                                <div class="col-lg-4 col-md-4 col-sm-12 border">
                                                    <form wire:submit.prevent="sessionStore({{$product->id}})" enctype="multipart/form-data" style="height: 100%">
                                                        @csrf
                                                        <div class="thumbnail @if (empty($product->photo)) no-image @endif" wire:click.prevent="sessionStore({{$product->id}})">
                                                            <div class="image view view-first">
                                                                @if(!empty($product->photo))
                                                                <img style="width: 100%; display: block; margin-bottom: 10px;" src="{{asset($product->photo)}}" alt="image" />
                                                                @endif
                                                                <h6 class="m-0">{{$product->name}}</h6>
                                                            </div>

                                                            <div class="mask">
                                                                <p class="m-0">
                                                                    <span>৳.{{ $product->purchase_rate }}/=</span>
                                                                </p>
                                                                <div class="badge-info text-light">{{ $product->opening_stock ?? 0 }} {{ trans_choice('labels.bag', $product->opening_stock ?? 0) }}</div>
                                                            </div>

                                                            @if(!empty($product->product->brand_id))
                                                                <div class="caption">
                                                                    <small>{{$product->product->brand->name}}</small>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </form>
                                                </div>
                                                @endforeach
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
</div>

@push('scripts')
<script>

    $(document).ready(function () {
       $('#supplier-search').select2();
       $('#supplier-search').on('change', function (e) {
           var data = $('#supplier-search').select2("val");
           @this.set('supplier_search', data);
       });
    });

    $(document).ready(function () {
       $('#product-search').select2();
       $('#product-search').on('change', function (e) {
        @this.sessionStore(e.target.value);
           //var data = $('#product-search').select2("val");
           //@this.set('product_search', data);
       });

       $('#purchase_date_picker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });

        $('#purchase_date_picker input[name=date]').on('change', function(e) {
            @this.set('date', e.target.value);
        })
    });

    </script>
@endpush
