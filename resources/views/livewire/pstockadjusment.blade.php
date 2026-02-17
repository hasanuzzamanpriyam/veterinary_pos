@section('page-title', 'Stock Adjustment')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Product Stock Adjustment</h2>
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
                                    <form wire:submit.prevent="stockAdjustment()" enctype="multipart/form-data"
                                        data-parsley-validate
                                        class="form-horizontal form-label-left sales_entry_form">
                                        @csrf
                                        <div class="row">
                                            <!--Start supplier area-->
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label class="py-1 border" for="source_store_id">Source Store/Warehouse</label>
                                                    <select type="text" wire:model="source_store_id" id="source_store_id"
                                                        wire:change="source_store_id_update($event.target.value)"
                                                        name="source_store_id" class="form-control">
                                                        <option value="0">Select Option</option>
                                                        @foreach ($stores as $store)
                                                            <option value="{{ $store->id }}">{{ $store->name }} - {{ $store->address }} - {{ $store->mobile }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label class="py-1 border" for="destination_store_id">Destination Store/Warehouse</label>
                                                    <select type="text" wire:model="destination_store_id" id="destination_store_id"
                                                        wire:change="destination_store_id_update($event.target.value)"
                                                        name="destination_store_id" class="form-control">
                                                        <option value="0">Select Option</option>
                                                        @foreach ($stores as $store)
                                                            <option value="{{ $store->id }}">{{ $store->name }} - {{ $store->address }} - {{ $store->mobile }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <!--End supplier area-->
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="py-1 border" for="remarks">Remarks</label>
                                                    <input type="text" wire:model="remarks" name="remarks" id="remarks" class="form-control">
                                                </div>
                                            </div>
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
                                                                    <option class="text-left" value="">Select Products</option>
                                                                    @if (isset($products))
                                                                        @foreach ($products as $id => $product)
                                                                            <option class="text-left p-2"
                                                                                value="{{ $id }}">
                                                                                {{ $product['code'] }} -
                                                                                {{ $product['name'] }} -
                                                                                {{ $product['qty'] }}
                                                                                {{trans_choice( 'labels.' . $product['type'], $product['qty'] )}}
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
                                                                Stock</th>
                                                            <th class="all"
                                                                style="width: 90px; min-width: 90px; text-align: center">
                                                                Quantity</th>
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
                                                            $total_price = 0;
                                                            $total_qty = 0;
                                                            $items = 0;
                                                        @endphp

                                                        @if (Cart::instance('stock_adjust')->count() > 0)
                                                            @forelse (Cart::instance('stock_adjust')->content() as $product)
                                                                @php
                                                                    $total_price += $product->qty * $product->price;
                                                                    $type = $product->options->type;
                                                                    $total_qty += $product->qty;
                                                                    $items++;
                                                                    $id = $product->id;
                                                                    $summary['qty'][$type] = $summary['qty'][$type] ?? 0;
                                                                    $summary['qty'][$type] += $product->qty;
                                                                    // dump($product);
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
                                                                        <div class="d-flex justify-content-center">
                                                                            <span>{{ $product->options->stock }} {{ trans_choice('labels.' . strtolower($product->options->type), $product->options->stock) }}</span>
                                                                        </div>
                                                                    </td>

                                                                    {{-- Quantity --}}
                                                                    <td>
                                                                        <div
                                                                            class="input-group align-items-center justify-content-center sales-entry-qty-wrapper">
                                                                            <input type="text"
                                                                                wire:model="quantities"
                                                                                wire:change="updateQuantity({{ $id }}, $event.target.value || 0)"
                                                                                value="{{ $product->qty }}"class="form-control sales-entry-qty">
                                                                            <span>{{ trans_choice('labels.' . strtolower($product->options->type), $product->qty) }}</span>
                                                                        </div>
                                                                        <small
                                                                            class="text-danger"><strong>{!! $product->options->stock >= $product->qty ? '' : '<span class="text-danger text-center">Unavailable</span>' !!}</strong></small>
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
                                                    </tbody>
                                                    {{-- Footer --}}
                                                    <tfoot>
                                                        <tr class="text-left">
                                                            <td colspan="2">
                                                                 <div class="d-flex justify-content-start">
                                                                    <span><strong>Total: {{ $items }} {{trans_choice('labels.items', $items)}}</strong></span>
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
                                                            <td></td>
                                                            <td>
                                                                <div class="d-flex justify-content-end">
                                                                    <span><strong>TK:</strong>
                                                                        {{ $total_price }}/=</span>
                                                                </div>
                                                            </td>

                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="justify-content-center d-flex" style="gap: 10px">
                                                    <button type="button" wire:click="cancel"
                                                        class="btn btn-danger btn-md">Cancel</button>
                                                    <input type="submit" value="Proceed to Next Step"
                                                        class="btn btn-primary btn-md">
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

            // $('#customer-search').select2();
            $('#destination_store_id').select2();
            $('#source_store_id').select2();
            // $('#customer-search').on('change', function(e) {
            //     var data = $('#customer-search').select2("val");
            //     @this.set('customer_search', data);
            // });
            $('#source_store_id').on('change', function(e) {
                var data = $('#source_store_id').select2("val");
                @this.set('source_store_id', data);
            });
            $('#destination_store_id').on('change', function(e) {
                var data = $('#destination_store_id').select2("val");
                @this.set('destination_store_id', data);
            });

            $('#product-search').select2();
            $('#product-search').on('change', function(e) {
                @this.sessionStore(e.target.value);
                // var data = $('#product-search').select2("val");
                // @this.set('product_search', data);
            });

            $(document).on('dataUpdated', function () {
                const timeout = setTimeout(() => {
                    $('#destination_store_id').select2();
                    $('#source_store_id').select2();
                    $('#product-search').select2();
                    clearTimeout(timeout);
                }, 10);
            })

            $('.date-picker').datepicker({
                format: "dd-mm-yyyy",
                orientation: "auto"
            });
        });

        // $(document).ready(function() {

        // });
    </script>
@endpush
