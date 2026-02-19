@section('page-title', 'Manage Stock')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Manage Product Stock</h2>
                <a href="{{ route('product.stock') }}" class="mr-auto ml-3 cursor-pointer"><i class="fa fa-close"></i></a>
            </div>
        </div>

        <div class="x_content p-3">
            @if ($errors->any())
                <div class="mb-3">
                    <ul class="list-group">
                        @foreach ($errors->all() as $error)
                            <li class="list-group-item list-group-item-danger"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="ml-2">{{ $error }}</span></li>
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
                                    <form wire:submit.prevent="stockUpdate()" enctype="multipart/form-data"
                                        data-parsley-validate
                                        class="form-horizontal form-label-left sales_entry_form">
                                        @csrf
                                        <div class="row">

                                            <!--Start supplier area-->
                                            <div class="col-lg-3 col-md-6 col-sm-12">

                                                <div class="form-group">
                                                    <label class="py-1 border sales_entry_lebel" for="product_store_id">Store/Warehouse</label>
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <select type="text" wire:model="product_store_id" id="product_store_id"
                                                wire:change="productSearch($event.target.value)"
                                                name="product_store_id" class="form-control">
                                                    <option>Select Store/Warehouse</option>
                                                    @foreach ($stores as $store)
                                                        <option value="{{ $store->id }}">{{ $store->name }} - {{ $store->address }} - {{ $store->mobile }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!--End supplier area-->
                                        </div>

                                        <div class="row">

                                            <div class="search-area col-lg-12 col-md-12 col-sm-12 text-left float-right py-3 purchase_return_entry_supplier_col">
                                                {{-- start product select area --}}
                                                <div wire:ignore.self class="row">
                                                    <div class="col-md-3">
                                                        <label class="py-1 borde sales_entry_lebel"
                                                            for="product">Product</label>
                                                    </div>
                                                    {{-- @dump( $product_stores) --}}
                                                    <div class="col-md-9">
                                                        <select class="form-control" id="product-search">
                                                            <option class="text-left">Select Products</option>

                                                            @if (isset($products))
                                                                @foreach ($products as $product)
                                                                    <option class="text-left p-2"
                                                                        value="{{ $product->id }}">
                                                                        {{ $product->code }} -
                                                                        {{ $product->name }} -
                                                                        {{ $product['qty'] }}
                                                                        {{ isset($product_stores[$product->id]) ? $product_stores[$product->id]['qty']  : 0 }}
                                                                        {{trans_choice( 'labels.' . $product->type, (isset($product_stores[$product->id]) ? $product_stores[$product->id]['qty'] : 0) )}} -
                                                                        {{ $product->purchase_rate }}/=
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-bordered table-sales-entry"
                                                    cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th class="all smaller" style="width: 70px; min-width: 70px; text-align: center">Code</th>
                                                            <th class="all bigger" style="width: 100%">Name</th>
                                                            <th class="all" style="width: 90px; min-width: 90px; text-align: center">Quantity</th>
                                                            <th class="all" style="width: 90px; min-width: 90px; text-align: center">Prev Stock</th>
                                                            <th class="all" style="width: 90px; min-width: 90px;; text-align: center">Rate</th>
                                                            <th class="all bigger" style="width: 90px; min-width: 90px;; text-align: center">Sub Total</th>
                                                            <th class="all" style="width: 100px">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $type = 0;
                                                            $total_price = 0;
                                                            $items = 0;
                                                        @endphp


                                                        @if (Cart::instance('manage_stock')->count() > 0)
                                                            @forelse (Cart::instance('manage_stock')->content() as $product)
                                                                @php
                                                                    $total_price += $product->qty * $product->price;
                                                                    $type = $product->options->type;
                                                                    $id = $product->id;
                                                                    $items++;
                                                                    $summary['qty'][$type] = $summary['qty'][$type] ?? 0;
                                                                    $summary['qty'][$type] += $product->qty;
                                                                @endphp
                                                                <tr class="text-center sales-entry">
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
                                                                                wire:change="updateQuantity({{ $id }}, $event.target.value || 0)"
                                                                                value="{{ $product->qty }}"class="form-control sales-entry-qty">
                                                                        </div>
                                                                    </td>

                                                                    {{-- Stock --}}
                                                                    <td>
                                                                        <div class="text-center">
                                                                            <span>{{ $product->options->stock }} {{trans_choice($product->options->type, $product->options->stock)}}</span>
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
                                                                                value="{{ $product->price * $product->qty }}/-"
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
                                                                    <span>
                                                                        {{ $items }}</span>
                                                                </div>
                                                            </td>
                                                            <td></td>
                                                            <td>
                                                                <div>
                                                                    @if( isset($summary['qty']) && $summary['qty'] > 0)
                                                                        @foreach ($summary['qty'] as $key => $value)
                                                                            <span class="d-inline-block"><strong>{{ $value }}</strong></span>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>
                                                                <div class="d-flex justify-content-end">
                                                                    <span><strong>TK:</strong>
                                                                        {{ $total_price }}/=</span>
                                                                </div>
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="justify-content-center d-flex" style="gap: 10px">
                                                    <button type="button" wire:click="cancel"
                                                        class="btn btn-danger btn-md">Cancel</button>
                                                    <input type="submit" @if ($items == 0) disabled @endif  value="Checkout" class="btn btn-primary btn-md">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-lg-4 col-md-5 col-sm-12">
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
                                        <div class="col-lg-5 col-md-5 col-sm-12">
                                            <div class="input-group">
                                                <select name="brand_id" id="brand_id"
                                                    wire:change="brandSearch($event.target.value)" type="text"
                                                    class="form-control">
                                                    <option value="0">All Brand</option>
                                                    @foreach ($brands as $brand)
                                                        <option value="{{ $brand->id }}">{{ $brand->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" wire:model.live="search"
                                                    placeholder="Search for...">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row pt-4">
                                        @if (isset($products))
                                            @foreach ($products as $product)
                                                <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                                    <div class="thumbnail" wire:click.prevent="sessionStore({{ $product->product->id }})">
                                                        <div class="image view view-first">

                                                            @if (empty($product->product->photo))
                                                                <p my-auto>Opps No Image Found!</p>
                                                            @else
                                                                <img style="width: 100%; display: block;"
                                                                    src="{{ asset($product->product->photo) }}"
                                                                    alt="image" />
                                                            @endif
                                                            <form
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="mask">
                                                                    <p class="m-0">
                                                                        <span>৳.{{ $product->product->price_rate }}/=</span>
                                                                        <span class="badge-info text-light">Qty: {{ $product->product_quantity }} {{ trans_choice('labels.bag', $product->product_quantity) }}</span>
                                                                    </p>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="caption">{{ $product->product->name }}</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="col-md-12">
                                                <h4 class="m-auto py-5 text-danger">Please select a customer and store then
                                                    add your necessary product from here</h4>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {

            $(document).on('dataUpdated', function(){
                const timeout = setTimeout(() => {
                    $('#product_store_id').select2();
                    $('#product-search').select2();
                    clearTimeout(timeout);
                }, 10);
            });

            $('#product_store_id').on('change', function(e) {

                var data = $('#product_store_id').select2("val");
                @this.set('product_store_id', data);
            });

            $('#product-search').on('change', function(e) {
                @this.sessionStore(e.target.value);
            });
        });
    </script>
@endpush
