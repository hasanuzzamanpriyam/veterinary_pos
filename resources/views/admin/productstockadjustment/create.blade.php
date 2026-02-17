<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Sales Entry</h2>
                <a href="{{ route('sales.index', [ 'view' => 'v1' ]) }}" class="mr-auto ml-3 cursor-pointer"><i class="fa fa-close"></i></a>
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
                <div class="col-lg-8 col-md-7 col-sm-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-6">
                                    <div class="row justify-content-end">
                                        <div
                                            class="search-area col-lg-12 col-md-12 col-sm-12 text-left pb-3 purchase_return_entry_supplier_col">
                                            {{-- start supplier select area --}}
                                            <div wire:ignore class="row">
                                                <div class="col-md-3">
                                                    <label class="py-1 border entry-lebel" for="customer">Customer</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <select class="d-block py-1 border" class="form-control"
                                                        id="customer-search">
                                                        <option value="">Select Customer</option>
                                                        @foreach ($customers as $customer)
                                                            <option value="{{ $customer->id }}">
                                                                {{ $customer->name }} -
                                                                {{ $customer->address }} -
                                                                {{ $customer->mobile }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>{{-- end supplier select area --}}
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-6">
                                    <form wire:submit.prevent="customerInfo()" enctype="multipart/form-data"
                                        data-parsley-validate
                                        class="form-horizontal form-label-left sales_entry_form">
                                        @csrf
                                        <div class="row">

                                            <!--Start supplier area-->
                                            <div class="col-lg-3 col-md-6 col-sm-12">
                                                <div class="form-group ">
                                                    <label class=" py-1 border" for="date">Date</label>
                                                    <input id="date" name="date" wire:model="date"
                                                        class="date-picker form-control" placeholder="dd-mm-yyyy"
                                                        type="text">
                                                    <script>
                                                        function timeFunctionLong(input) {
                                                            setTimeout(function() {
                                                                input.type = 'text';
                                                            }, 60000);
                                                        }
                                                    </script>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12">

                                                <div class="form-group">
                                                    <label class="py-1 border" for="supplier_name">Customer
                                                        Name</label>
                                                    <input type="text" name="customer_name"
                                                        wire:model="customer_name" value=""
                                                        class="form-control">
                                                </div>


                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12">
                                                 <div class="form-group">
                                                    <label class="py-1 border" for="transport_no">Address</label>
                                                    <textarea type="text" name="address" id="address" wire:model="address" class="form-control" cols="5"
                                                        rows="1"></textarea>
                                                </div>


                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label class="py-1 border" for="transport_no">Mobile</label>
                                                    <input type="text" name="mobile" id="mobile"
                                                        wire:model="mobile" class="form-control">
                                                </div>

                                            </div>

                                            <!--End supplier area-->
                                        </div>
                                        <div class="row">

                                            <!--Start supplier area-->
                                            <div class="col-lg-3 col-md-6 col-sm-12">

                                                <div class="form-group">
                                                    <label class="py-1 border" for="product_store_id">Store/Warehouse</label>
                                                    <select type="text" wire:model="product_store_id"
                                                        wire:change="productSearch($event.target.value)"
                                                        name="product_store_id" class="form-control">
                                                        <option value="0">Select Option</option>
                                                        @foreach ($stores as $store)
                                                            <option value="{{ $store->id }}">{{ $store->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12">


                                                <div class="form-group">
                                                    <label class=" py-1 border" for="prepare">Gari Number</label>
                                                    <input type="text" name="transport_no"
                                                        wire:model="transport_no" id="transport_no"
                                                        class="form-control">
                                                </div>


                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12">

                                                <div class="form-group">
                                                    <label class="py-1 border" for="delivery_man" style="font-size: 14px">Delivery
                                                        Man</label>
                                                    <input type="text" name="delivery_man" id="delivery_man"
                                                        wire:model="delivery_man" class="form-control">
                                                </div>


                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12">

                                                <div class="form-group">
                                                    <label class="py-1 border" for="transport_no">Remarks</label>
                                                    <input type="text" wire:model="remarks" name="remarks" id="remarks" class="form-control">
                                                </div>

                                            </div>

                                            <!--End supplier area-->
                                        </div>

                                        <div class="row">




                                            <!--Start Search Product area-->
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="row justify-content-end">
                                                    <div class="search-area col-lg-12 col-md-12 col-sm-12 text-left float-right py-3 purchase_return_entry_supplier_col">
                                                        {{-- start product select area --}}
                                                        <div wire:ignore.self class="row">
                                                            <div class="col-md-3">
                                                                <label class="py-1 border"
                                                                    for="product">Product</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <select class="form-control" id="product-search">
                                                                    <option class="text-left" value="">Select
                                                                        Products
                                                                    </option>



                                                                    @if (isset($products))
                                                                        @foreach ($products as $product)

                                                                            @php

                                                                            $product_price_group = DB::table('price_group_products')->where('price_group_id', $priceGroupProduct->price_group_id)->where('product_id',$product->id)->first();

                                                                                // var_dump($product_price_group);
                                                                            @endphp

                                                                            <option class="text-left p-2"
                                                                                value="{{ $product->product_id }}">
                                                                                {{ $product->product_code }} -
                                                                                {{ $product->name }} -
                                                                                {{ $product->product_quantity }}
                                                                                {{trans_choice( 'labels.' . $product->type, $product->product_quantity )}} -

                                                                                @if ($product_price_group != null)
                                                                                {{ $product_price_group->price_group_rate }}/=
                                                                                @else
                                                                                {{ $product->price_rate }}/=
                                                                                @endif

                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>{{-- end product select area --}}
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                {{-- Cart product table --}}

                                                <table class="table table-bordered table-sales-entry"
                                                    cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th class="all smaller"
                                                                style="width: 70px; min-width: 70px; text-align: center">
                                                                Code</th>
                                                            <th class="all bigger" style="width: 100%">Name</th>
                                                            <th class="all"
                                                                style="width: 90px; min-width: 90px; text-align: center">
                                                                Quantity</th>
                                                            <th class="all"
                                                                style="width: 70px; min-width: 70px; text-align: center">
                                                                Discount</th>
                                                            <th class="all" style="width: 125px;min-width: 125px;">Sales(Q)</th>
                                                            <th class="all"
                                                                style="width: 90px; min-width: 90px;; text-align: center">
                                                                Rate</th>
                                                            <th class="all bigger"
                                                                style="width: 90px; min-width: 90px;; text-align: center">
                                                                Sub Total</th>
                                                            <th class="all" style="width: 100px">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $type = 0;
                                                            $total_sales = 0;
                                                            $total_price = 0;
                                                            $total_qty = 0;
                                                            $discount = 0;
                                                            $items = 0;
                                                        @endphp


                                                        @if (Cart::instance('sales')->count() > 0)
                                                            @forelse (Cart::instance('sales')->content() as $product)
                                                                @php
                                                                    $total_price +=
                                                                        ($product->qty -
                                                                            $product->options->discount) *
                                                                        $product->price;
                                                                    $total_sales +=
                                                                        $product->qty - $product->options->discount;
                                                                    $type = $product->options->type;
                                                                    $total_qty += $product->qty;
                                                                    $discount += $product->options->discount;
                                                                    $items++;
                                                                    $id = $product->id;
                                                                    $summary['qty'][$type] = $summary['qty'][$type] ?? 0;
                                                                    $summary['discount'][$type] = $summary['discount'][$type] ?? 0;
                                                                    $summary['total'][$type] = $summary['total'][$type] ?? 0;
                                                                    $summary['qty'][$type] += $product->qty;
                                                                    $summary['discount'][$type] += $product->options->discount;
                                                                    $summary['total'][$type] += $product->qty - $product->options->discount;
                                                                @endphp
                                                                <tr class="text-center sales-entry">
                                                                    {{-- Code --}}
                                                                    <td>
                                                                        <div class="d-flex">
                                                                            <span>{{ $product->options->code }}</span>
                                                                        </div>
                                                                    </td>

                                                                    {{-- Name --}}
                                                                    <td>
                                                                        <div class="d-flex">
                                                                            <span>{{ $product->name }}</span>
                                                                        </div>
                                                                    </td>

                                                                    {{-- Quantity --}}
                                                                    <td>
                                                                        <div
                                                                            class="input-group justify-content-center">
                                                                            <input type="text"
                                                                                wire:model="quantities"
                                                                                wire:change="updateQuantity({{ $id }},{{ $product->options->product_store_id }}, $event.target.value || 0)"
                                                                                value="{{ $product->qty }}"class="form-control sales-entry-qty">
                                                                            {{-- <span>{{ trans_choice('labels.bags', $product->qty) }}</span> --}}
                                                                        </div>
                                                                        <small
                                                                            class="text-danger"><strong>{!! $product->options->stock == 1 ? '' : '<span class="text-danger text-center">Unavailable</span>' !!}</strong></small>
                                                                    </td>

                                                                    {{-- Discount --}}
                                                                    <td>
                                                                        <div
                                                                            class="input-group justify-content-center">
                                                                            <input type="number"
                                                                                wire:model="discounts"
                                                                                wire:change="updateDiscount({{ $id }}, $event.target.value || 0)"
                                                                                value="{{ $product->options->discount }}"
                                                                                class="form-control">
                                                                            {{-- <span>{{ trans_choice('labels.bags', $product->options->discount) }}</span> --}}
                                                                        </div>
                                                                    </td>

                                                                    {{-- Sales(Q) --}}
                                                                    <td class="purchase-qty">
                                                                        <div
                                                                            class="d-flex justify-content-center flex-row gap-2">
                                                                            <input type="text"
                                                                                @disabled(true)
                                                                                value="{{ $product->qty - $product->options->discount }}"
                                                                                class="form-control">
                                                                            <span>{{ trans_choice('labels.' . strtolower($product->options->type), ($product->qty - $product->options->discount)) }}</span>
                                                                        </div>
                                                                    </td>

                                                                    {{-- Rate --}}
                                                                    <td>
                                                                        <div class="input-group">
                                                                            <input type="text"
                                                                                wire:model="update_price"
                                                                                wire:change="updatePrice({{ $id }}, $event.target.value || 0)"
                                                                                value="{{ $product->price }}"
                                                                                class="form-control">
                                                                        </div>
                                                                    </td>

                                                                    {{-- Sub Total --}}
                                                                    <td class="sub-total">
                                                                        <div
                                                                            class="input-group justify-content-center">
                                                                            <input type="text"
                                                                                @disabled(true)
                                                                                value="{{ $product->price * ($product->qty - $product->options->discount) }}/-"
                                                                                class="form-control">
                                                                        </div>
                                                                    </td>

                                                                    {{-- Action --}}
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

                                                        {{-- Footer --}}
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
                                                            <td></td>
                                                            <td>
                                                                <div class="d-flex justify-content-end">
                                                                    <span><strong>TK:</strong>
                                                                        {{ $total_price }}/=</span>
                                                                </div>
                                                            </td>

                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="justify-content-center d-flex" style="gap: 10px">
                                                    <button type="button" wire:click="canceal"
                                                        class="btn btn-danger btn-md">Cancel</button>
                                                    <input type="submit" value="Checkout"
                                                        class="btn btn-primary btn-md">
                                                    <a type="button" href="{{ url('/dashboard') }}"
                                                        class="btn btn-info btn-md">Hold</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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
        $(document).ready(function() {

            $('#customer-search').select2();
            $('#customer-search').on('change', function(e) {
                var data = $('#customer-search').select2("val");
                @this.set('customer_search', data);
            });

            $('#product-search').select2();
            $('#product-search').on('change', function(e) {
                @this.sessionStore(e.target.value);
                // var data = $('#product-search').select2("val");
                // @this.set('product_search', data);
            });

            $('.date-picker').datepicker({
                format: "dd-mm-yyyy",
                orientation: "auto"
            });
        });

        // $(document).ready(function() {

        // });
    </script>
@endpush
