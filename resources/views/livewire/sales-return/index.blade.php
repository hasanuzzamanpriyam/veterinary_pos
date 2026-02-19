@section('page-title', 'Sales Return Entry')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Sales Return Entry</h2>
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
                        <div class="col-lg-12">

                            <div class="row justify-content-end">
                                <div
                                    class="search-area col-lg-12 col-md-12 col-sm-12 text-left pb-3 purchase_return_entry_supplier_col">
                                    {{-- start supplier select area --}}
                                    <div wire:ignore class="row">
                                        <div class="col-md-4">
                                            <label class="py-1 border entry-lebel sales_entry_lebel" for="customer">Customer</label>
                                        </div>
                                        <div class="col-md-8">
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

                            <form wire:submit.prevent="customerInfo()" enctype="multipart/form-data"
                                data-parsley-validate class="form-horizontal form-label-left fz-entry-form">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="py-1 border sales_entry_lebel"for="supplier_name">Customer Name</label>
                                            <input type="text" name="customer_name" wire:model="customer_name"
                                                value="{{ $customer_name }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="py-1 border sales_entry_lebel" for="transport_no">Address</label>
                                            <input type="text" name="address" wire:model="address" id="address" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="py-1 border sales_entry_lebel" for="transport_no">Mobile</label>
                                            <input type="text" name="mobile" id="mobile" wire:model="mobile"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group ">
                                            <label class="border py-1 sales_entry_lebel" for="date">Date</label>
                                            <div class="input-group date" id="sale_date_picker">
                                                <input name="date" wire:model="date" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-th"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="py-1 border sales_entry_lebel" for="purchase_invoice_no">Sale Invoice
                                                No</label>
                                            <input type="text" name="sales_invoice_no" wire:model="sales_invoice_no" wire:change="invoiceSearch($event.target.value)"
                                                id="sales_invoice_no" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group ">
                                            <label class="border py-1 sales_entry_lebel" for="return_date">Return Date</label>
                                            <div class="input-group date" id="return_date_picker">
                                                <input name="date" wire:model="return_date" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-th"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!--Start supplier area-->
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="py-1 border sales_entry_lebel" for="product_store_name">Store</label>
                                            <input type="text" wire:model="product_store_name"
                                                wire:model="product_store_name" name="product_store_name"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="py-1 border sales_entry_lebel" for="remarks">Remarks</label>
                                            <input type="text" name="remarks" id="remarks" wire:model="remarks" class="form-control">
                                        </div>
                                    </div>
                                    <!--End supplier area-->
                                </div>

                                <div class="row">
                                    <!--Start Search Product area-->
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="row justify-content-end">
                                            <div
                                                class="search-area col-lg-12 col-md-12 col-sm-12 text-left float-right py-3 purchase_return_entry_supplier_col">
                                                {{-- start product select area --}}
                                                <div wire:ignore.self class="row">
                                                    <div class="col-md-4">
                                                        <label class="py-1 border sales_entry_lebel" for="product">Product</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        {{-- {{dump($products)}} --}}
                                                        <select class="form-control" id="product-search">
                                                            <option class="text-left" value="">Select
                                                                Products
                                                            </option>
                                                            @if (isset($products))
                                                                @foreach ($products as $product)
                                                                    <option class="text-left p-2"
                                                                        value="{{ $product->product_id }}">
                                                                        {{ $product->product_code }} -
                                                                        {{ $product->product->name }} -
                                                                        {{ $product->quantity - $product->discount_qty }}{{$product->discount_qty > 0 ? '+' . $product->discount_qty : '' }}
                                                                        {{ trans_choice('labels.' . $product->product->type, ($product->quantity - $product->discount_qty)) }}
                                                                        -
                                                                        {{ $product->unit_price }}/=
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

                                        <table
                                            class="table table-striped table-bordered sales-return-table table-sales-entry"
                                            cellspacing="0" width="100%">
                                            <thead>
                                                <tr class="text-center">
                                                    <th class="all smaller"
                                                        style="width: 70px; min-width: 70px; text-align: center">
                                                        Code</th>
                                                    <th class="all bigger" style="width: 100%">Name</th>

                                                    <th class="all"
                                                        style="width: 90px; min-width: 90px; text-align: center">
                                                        Sale (Q)</th>
                                                    <th class="all"
                                                        style="width: 70px; min-width: 70px; text-align: center">
                                                        Return (Q)</th>
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
                                                    $total_return = 0;
                                                    $total_qty = 0;
                                                    $type = 0;
                                                    $items = 0;
                                                    $total_price = 0;
                                                    $isError = false;
                                                @endphp
                                                @if (Cart::instance('sales_return')->count() == 0)
                                                @else
                                                    @forelse (Cart::instance('sales_return')->content() as $product)
                                                        @php

                                                            // dd($product);

                                                            $total_price +=
                                                                ($product->qty - $product->options->discount) *
                                                                $product->price;
                                                            $total_return += $product->qty;
                                                            $total_qty += $product->qty;
                                                            $type = $product->options->type;
                                                            $items++;
                                                            $id = $product->id;

                                                            $summary['qty'][$type] = $summary['qty'][$type] ?? 0;
                                                            $summary['sale_qty'][$type] = $summary['sale_qty'][$type] ?? 0;

                                                            $summary['discount'][$type] =
                                                                $summary['discount'][$type] ?? 0;
                                                            $summary['total'][$type] = $summary['total'][$type] ?? 0;
                                                            $summary['qty'][$type] += $product->qty;
                                                            $summary['sale_qty'][$type] +=$product->options->sale_qty;

                                                            $summary['discount'][$type] += $product->options->discount;
                                                            $summary['total'][$type] +=
                                                                $product->qty - $product->options->discount;
                                                        @endphp
                                                        <tr class="text-center">
                                                            <td>
                                                                <div class="d-flex flex-column align-items-start">
                                                                    <span>{{ $product->options->code }}</span>
                                                                    @if($product->options->barcode)
                                                                        <svg class="barcode-render" data-barcode="{{ $product->options->barcode }}"
                                                                            style="height: 25px; margin-top: 4px; max-width: 100%;"></svg>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            <td class="text-left">{{ $product->name }}</td>

                                                            <td>{{ $product->options->sale_qty }} {{ trans_choice('labels.' . strtolower($type), $product->options->sale_qty) }}</td>

                                                            <td class="return-qty">
                                                                <div
                                                                    class="d-flex justify-content-center flex-row gap-2">
                                                                    <input type="number"
                                                                        wire:change="updateQuantity({{ $id }},{{ $sales_invoice_no }}, $event.target.value || 0)"
                                                                        value="{{ $product->qty }}"class="form-control sales-entry-qty">

                                                                </div>
                                                                @php
                                                                    if($product->options->sale_qty < $product->qty){
                                                                        $isError = true;
                                                                    }
                                                                @endphp
                                                                <small
                                                                    class="text-danger"><strong>{!! $product->options->stock == 0 ? '<span class="text-danger text-center">Unavailable</span>' : '' !!}</strong></small>

                                                            </td>

                                                            <td class="text-right sale-return-rate">
                                                                <div
                                                                    class="d-flex justify-content-center flex-row gap-0">
                                                                    <input type="text" @disabled(true)
                                                                        wire:change="updatePrice({{ $id }}, $event.target.value)"
                                                                        value="{{ number_format($product->price) }}"
                                                                        class="form-control ">
                                                                    <span>Tk</span>
                                                                </div>
                                                            </td>
                                                            <td class="text-right">
                                                                @php
                                                                    $total = $product->price * $product->qty
                                                                @endphp
                                                                {{ number_format($total) }}/-</td>
                                                            <td>
                                                                <div class="input-group justify-content-center">
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
                                                            <span><strong>{{ trans_choice('labels.items', $items) }}:</strong>
                                                                {{ $items }}</span>
                                                        </div>
                                                    </td>
                                                    <td></td>
                                                    <td>
                                                        <div>
                                                            @if (isset($summary['sale_qty']) && $summary['sale_qty'] > 0)
                                                                @foreach ($summary['sale_qty'] as $key => $value)
                                                                    <span
                                                                        class="d-inline-block"><strong>{{ $value }}</strong>
                                                                        <span
                                                                            class="ttl">{{ trans_choice('labels.' . strtolower($key), $value) }}</span></span>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            @if (isset($summary['qty']) && $summary['qty'] > 0)
                                                                @foreach ($summary['qty'] as $key => $value)
                                                                    <span
                                                                        class="d-inline-block"><strong>{{ $value }}</strong>
                                                                        <span
                                                                            class="ttl">{{ trans_choice('labels.' . strtolower($key), $value) }}</span></span>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </td>


                                                    <td></td>
                                                    <td>
                                                        <div class="d-flex justify-content-end">
                                                            <span><strong>TK:</strong>
                                                                {{ number_format($total_price) }}/=</span>
                                                        </div>
                                                    </td>

                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="justify-content-center d-flex" style="gap: 10px">
                                            <button type="button" wire:click="cancel"
                                                class="btn btn-danger btn-md">Cancel</button>
                                            <input type="submit" @php echo $isError ? 'disabled' : '' @endphp value="Checkout" class="btn btn-primary btn-md">
                                            <a type="button" href="{{ url('/dashboard') }}"
                                                class="btn btn-info btn-md">Hold</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-5 col-sm-12">
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
                                @if (isset($products))
                                    @foreach ($products as $product)
                                        <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                            <div class="thumbnail"
                                                wire:click.prevent="sessionStore({{ $product->product->id }})">
                                                <div class="image view view-first">

                                                    @if (empty($product->product->photo))
                                                        <h5 my-auto>Opps No Image Found!</h5>
                                                    @else
                                                        <img style="width: 100%; display: block;"
                                                            src="{{ asset($product->product->photo) }}"
                                                            alt="image" />
                                                    @endif
                                                    <form enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="mask">
                                                            <p>
                                                                <strong>৳.{{ $product->product->price_rate }}/=</strong>
                                                                {{-- <span class="text-wrap">{{ $product->product->name }}</span> --}}
                                                                <span
                                                                    class="badge badge-info text-light">Qty:{{ $product->product_quantity }}</span>
                                                            </p>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="caption">
                                                    @if (empty($product->product->brand_id))
                                                    @else
                                                        <small>{{ $product->product->brand->name }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-md-12">
                                        <div class="jumbotron">
                                            <h4 class="text-danger">Please select followings to see products</h4>
                                            <ol>
                                                <li>Customer</li>
                                                <li>Purchase Date</li>
                                            </ol>
                                        </div>
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
            });
        });

        $(document).on('dataUpdated', function () {
            const timeout = setTimeout(() => {
                $('#product-search').select2();
                clearTimeout(timeout);
            }, 10);
        })
        $('#sale_date_picker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });
        $('#return_date_picker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });
        $('#sale_date_picker input[name=date]').on('change', function(e) {
            @this.set('date', e.target.value);
        });
        $('#return_date_picker input[name=date]').on('change', function(e) {
            @this.set('return_date', e.target.value);
        });
    </script>
@endpush
