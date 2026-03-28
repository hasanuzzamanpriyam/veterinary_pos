@section('page-title', 'Purchase Entry')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Purchase Entry</h2>
                <a href="{{ route('purchase.index', ['view' => 'v1']) }}" class="mr-auto ml-3 cursor-pointer">
                    <i class="fa fa-close"></i>
                </a>
            </div>
        </div>

        <div class="x_content p-3">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Supplier Search --}}
            <div class="row mb-3">
                <div class="col-12">
                    <div wire:ignore>
                        <label class="purchase_entry_lebel mb-1" for="supplier-search">Company</label>
                        <select class="form-control" id="supplier-search">
                            <option value="">Select Company</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">
                                    {{ $supplier->company_name }} - {{ $supplier->address }} - {{ $supplier->mobile }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Main Form --}}
            <form wire:submit.prevent="supplierInfo()" enctype="multipart/form-data" data-parsley-validate class="form-horizontal form-label-left">
                @csrf

                {{-- Supplier & Warehouse Details --}}
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="purchase_entry_lebel" for="supplier_name">Company Name</label>
                            <input type="text" name="supplier_name" wire:model="supplier_name" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="purchase_entry_lebel" for="address">Address</label>
                            <textarea name="address" id="address" wire:model="address" class="form-control" rows="1"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="purchase_entry_lebel" for="mobile">Mobile</label>
                            <input type="text" name="mobile" id="mobile" wire:model="mobile" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="form-group">
                            <label class="purchase_entry_lebel" for="purchase_date">Purchase Date</label>
                            <div class="input-group date" id="purchase_date_picker_main">
                                <input name="purchase_date" type="text" class="form-control" placeholder="dd-mm-yyyy" wire:model="purchase_date">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="form-group">
                            <label class="purchase_entry_lebel" for="warehouse_id">Warehouse</label>
                            <select wire:model="warehouse_id" wire:change="warehouseSearch($event.target.value)" name="warehouse_id" class="form-control">
                                <option value="">Select Option</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="form-group">
                            <label class="purchase_entry_lebel" for="product_store_id">Store Name</label>
                            <select wire:model="product_store_id" name="product_store_id" class="form-control">
                                <option value="">Select Option</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="purchase_entry_lebel" for="transport_no">Vehicle Number</label>
                            <input type="text" name="transport_no" wire:model="transport_no" id="transport_no" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="purchase_entry_lebel" for="delivery_man">Delivery Man</label>
                            <input type="text" name="delivery_man" id="delivery_man" wire:model="delivery_man" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="purchase_entry_lebel" for="supplier_remarks">Remarks</label>
                            <input type="text" name="supplier_remarks" wire:model="supplier_remarks" class="form-control">
                        </div>
                    </div>
                </div>

                {{-- Product Search --}}
                <div class="row mt-3">
                    <div class="col-12">
                        <div wire:ignore>
                            <label class="purchase_entry_lebel mb-1" for="product-search">Product</label>
                            <select class="form-control text-center" id="product-search">
                                <option value="">Select Products</option>
                                @foreach ($products as $product)
                                    @php
                                        $line_stock_qty = isset($store_stocks[$product->id]) ? $store_stocks[$product->id]['qty'] : 0;
                                    @endphp
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }} -
                                        {{ $line_stock_qty }} {{ trans_choice($product->type, $line_stock_qty) }} -
                                        {{ $product->purchase_rate }}/=
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Cart Table --}}
                <div class="row mt-3">
                    <div class="col-12">
                        <table class="table table-bordered table-sales-entry" width="100%">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 70px;">Code</th>
                                    <th>Product Name</th>
                                    <th style="width: 95px;">Prod. Date</th>
                                    <th style="width: 95px;">Exp. Date</th>
                                    <th style="width: 110px;">Purchase(Q)</th>
                                    <th style="width: 70px;">Discount</th>
                                    <th style="width: 92px;">Quantity</th>
                                    <th style="width: 90px;">Rate</th>
                                    <th style="width: 90px;">Value</th>
                                    <th style="width: 90px;">Discount (TK)</th>
                                    <th style="width: 90px;">VAT</th>
                                    <th style="width: 90px;">Sub Total</th>
                                    <th style="width: 50px;"><i class="fa fa-trash"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_amount = 0;
                                    $total_qty = 0;
                                    $discount = 0;
                                    $total_discount_tk = 0;
                                    $total_vat = 0;
                                    $total_value = 0;
                                    $items = 0;
                                    $purchase_total = 0;
                                    $summary = ['qty' => [], 'discount' => [], 'total' => []];
                                @endphp

                                @if(Cart::instance('purchase')->content()->count() > 0)
                                    @foreach (Cart::instance('purchase')->content()->sortBy(function($item) {
                                        return $item->options->sort_index ?? 0;
                                    }) as $product)
                                        @php
                                            $qty = $product->qty ?: 0;
                                            $dis_qty = $product->options->discount ?: 0;
                                            $total_qty += $qty;
                                            $discount += $dis_qty;
                                            $type = $product->options->type;
                                            $purchase_total += $qty - $dis_qty;
                                            $line_value = ($qty - $dis_qty) * $product->price;
                                            $line_discount = $product->options->item_discount ?? 0;
                                            $line_vat = $product->options->item_vat ?? 0;
                                            $line_total = $line_value - $line_discount + $line_vat;

                                            $total_value += $line_value;
                                            $total_discount_tk += $line_discount;
                                            $total_vat += $line_vat;
                                            $total_amount += $line_total;

                                            $items++;
                                            $id = $product->id;

                                            $summary['qty'][$type] = ($summary['qty'][$type] ?? 0) + $qty;
                                            $summary['discount'][$type] = ($summary['discount'][$type] ?? 0) + $dis_qty;
                                            $summary['total'][$type] = ($summary['total'][$type] ?? 0) + ($qty - $dis_qty);
                                        @endphp

                                        <tr class="sales-entry" wire:key="cart-item-{{ $product->rowId }}">
                                            <td class="text-left">
                                                <div class="d-flex flex-column align-items-start">
                                                    @if($product->options->barcode)
                                                        <svg class="barcode-render" data-barcode="{{ $product->options->barcode }}"
                                                             style="height: 25px; margin-top: 4px; max-width: 100%;"></svg>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-left">
                                                <span>{{ $product->name }}</span>
                                            </td>
                                            <td class="text-left">
                                                <input type="text"
                                                       data-row-id="{{ $product->rowId }}"
                                                       data-type="production_date"
                                                       value="{{ $product->options->production_date ? date('d-m-Y', strtotime($product->options->production_date)) : '' }}"
                                                       class="form-control p-1 table-datepicker"
                                                       style="font-size: 12px; width: 100%; min-width: 95px;"
                                                       placeholder="dd-mm-yyyy"
                                                       readonly>
                                            </td>
                                            <td class="text-left">
                                                <input type="text"
                                                       data-row-id="{{ $product->rowId }}"
                                                       data-type="expire_date"
                                                       value="{{ $product->options->expire_date ? date('d-m-Y', strtotime($product->options->expire_date)) : '' }}"
                                                       class="form-control p-1 table-datepicker"
                                                       style="font-size: 12px; width: 100%; min-width: 95px;"
                                                       placeholder="dd-mm-yyyy"
                                                       readonly>
                                            </td>
                                            <td class="text-left purchase-qty">
                                                <input type="text"
                                                       wire:change="updatePurchaseQty({{$id}}, $event.target.value)"
                                                       value="{{ $qty - $dis_qty }}"
                                                       class="form-control">
                                            </td>
                                            <td class="text-left">
                                                <input type="number"
                                                       wire:change="updateDiscount({{$id}}, $event.target.value)"
                                                       value="{{ $dis_qty }}"
                                                       class="form-control">
                                            </td>
                                            <td class="text-left">
                                                <input type="text"
                                                       @disabled(true)
                                                       value="{{ $qty }}"
                                                       class="form-control purchase-entry-qty">
                                            </td>
                                            <td class="text-left">
                                                <input type="text"
                                                       wire:change="updatePrice({{ $id }}, $event.target.value || 0)"
                                                       value="{{ $product->price }}"
                                                       class="form-control">
                                            </td>
                                            <td class="text-left">
                                                <input type="text"
                                                       @disabled(true)
                                                       value="{{ number_format($line_value, 2, '.', '') }}"
                                                       class="form-control">
                                            </td>
                                            <td class="text-left">
                                                <input type="number"
                                                       wire:change="updateItemDiscount({{ $id }}, $event.target.value || 0)"
                                                       value="{{ $line_discount }}"
                                                       class="form-control">
                                            </td>
                                            <td class="text-left">
                                                <input type="number"
                                                       wire:change="updateItemVat({{ $id }}, $event.target.value || 0)"
                                                       value="{{ $line_vat }}"
                                                       class="form-control">
                                            </td>
                                            <td class="text-left sub-total">
                                                <input type="text"
                                                       @disabled(true)
                                                       value="{{ number_format($line_total, 2, '.', '') }}/-"
                                                       class="form-control">
                                            </td>
                                            <td class="text-center">
                                                <button type="button"
                                                        class="btn btn-danger btn-sm m-0"
                                                        wire:click="itemRemove('{{ $product->rowId }}')"
                                                        title="Remove">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="10" class="text-center">No products added</td>
                                    </tr>
                                @endif

                                {{-- Summary Row --}}
                                <tr class="text-left">
                                    <td><strong>{{ trans_choice('labels.items', $items) }}:</strong> {{ $items }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        @if(!empty($summary['total']))
                                            @foreach ($summary['total'] as $key => $value)
                                                <span class="d-inline-block"><strong>{{ $value }}</strong> <span class="ttl">{{ trans_choice(strtolower($key), $value) }}</span></span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($summary['discount']))
                                            @foreach ($summary['discount'] as $key => $value)
                                                <span class="d-inline-block"><strong>{{ $value }}</strong> <span class="ttl">{{ trans_choice(strtolower($key), $value) }}</span></span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($summary['qty']))
                                            @foreach ($summary['qty'] as $key => $value)
                                                <span class="d-inline-block"><strong>{{ $value }}</strong> <span class="ttl">{{ trans_choice(strtolower($key), $value) }}</span></span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td></td>
                                    <td class="text-right">
                                        <strong>V:</strong> {{ number_format($total_value, 2, '.', '') }}
                                    </td>
                                    <td class="text-right">
                                        <strong>D:</strong> {{ number_format($total_discount_tk, 2, '.', '') }}
                                    </td>
                                    <td class="text-right">
                                        <strong>Vat:</strong> {{ number_format($total_vat, 2, '.', '') }}
                                    </td>
                                    <td class="text-right">
                                        <strong>TK:</strong> {{ number_format($total_amount, 2, '.', '') }}/=
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="row mt-3">
                    <div class="col-12 d-flex justify-content-center gap-2">
                        <button type="button" wire:click="cancel" class="btn btn-danger btn-md">Cancel</button>
                        <input type="submit" @if($items == 0) disabled @endif value="Checkout" class="btn btn-primary btn-md">
                        <button type="button" wire:click="hold" @if($items == 0) disabled @endif class="btn btn-info btn-md">Hold</button>
                    </div>
                </div>
            </form>

            {{-- ========== HELD PURCHASES SECTION ========== --}}
            @if(isset($held_purchases) && $held_purchases->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="x_panel mb-0" style="border: 1px solid #17a2b8;">
                        <div class="x_title" style="background-color: #17a2b8; color: white; padding: 10px;">
                            <h2 style="font-size: 16px; margin: 0;"><i class="fa fa-pause-circle"></i> Held Purchases ({{ $held_purchases->count() }})</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content p-3">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Company Name</th>
                                        <th>Total Items</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($held_purchases as $hold)
                                    <tr>
                                        <td class="align-middle">{{ $hold->created_at->format('d-m-Y h:i A') }}</td>
                                        <td class="align-middle">{{ $hold->supplier_name ?: 'N/A' }}</td>
                                        <td class="align-middle">{{ is_array($hold->cart_data) ? count($hold->cart_data) : 0 }} items</td>
                                        <td class="align-middle" style="width: 250px;">
                                            <button type="button" wire:click="resumeHold({{ $hold->id }})" class="btn btn-primary btn-sm m-0"><i class="fa fa-plus"></i> Add</button>
                                            <button type="button" wire:click="deleteHold({{ $hold->id }})" class="btn btn-danger btn-sm m-0"><i class="fa fa-trash"></i> Delete</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ========== MOVED PRODUCT GALLERY SECTION ========== --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="x_panel product-thumb-gallery mb-0">
                        <div class="x_title" wire:click="toggleSidebar" style="padding: 0; border-bottom: 1px solid #E6E9ED; cursor: pointer; background-color: #f8f9fa; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#e2e6ea'" onmouseout="this.style.backgroundColor='#f8f9fa'">
                            <div class="w-100 text-left m-0 d-flex justify-content-between align-items-center" style="color: #495057; padding: 12px 15px; font-weight: 600; font-size: 14px; user-select: none;">
                                <div>
                                    @if ($showSidebar)
                                        <i class="fa fa-eye-slash mr-2"></i> Hide Product Gallery
                                    @else
                                        <i class="fa fa-eye mr-2"></i> Show Product Gallery
                                    @endif
                                </div>
                                <div>
                                    <i class="fa @if($showSidebar) fa-chevron-up @else fa-chevron-down @endif"></i>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content p-3" @if(!$showSidebar) style="display: none;" @endif>
                            <div class="row">
                                <div class="col-lg-5 col-md-5 col-sm-12">
                                    <div class="input-group">
                                        <select name="brand_id" id="brand_id" wire:change="brandSearch($event.target.value)" class="form-control">
                                            <option value="0">All Brand</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
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
                                @if(isset($products_grid))
                                    @foreach($products_grid as $product)
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-6 mb-3">
                                            <form wire:submit.prevent="sessionStore({{ $product->id }})" enctype="multipart/form-data" style="height: 100%;">
                                                @csrf
                                                <div class="thumbnail @if(empty($product->photo)) no-image @endif h-100"
                                                     wire:click.prevent="sessionStore({{ $product->id }})"
                                                     style="cursor: pointer;">
                                                    <div class="image view view-first text-center">
                                                        @if(!empty($product->photo))
                                                            <img style="width: 100%; display: block; margin-bottom: 10px;" src="{{ asset($product->photo) }}" alt="product image">
                                                        @endif
                                                        <h6 class="m-0">{{ $product->name }}</h6>
                                                    </div>
                                                    <div class="mask">
                                                        <p class="m-0"><span>৳.{{ $product->purchase_rate }}/=</span></p>
                                                        <div class="badge-info text-light">
                                                            {{ $product->opening_stock ?? 0 }} {{ trans_choice('labels.bag', $product->opening_stock ?? 0) }}
                                                        </div>
                                                    </div>
                                                    @if(!empty($product->product->brand_id))
                                                        <div class="caption">
                                                            <small>{{ $product->product->brand->name }}</small>
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
        </div> {{-- end x_content --}}
    </div> {{-- end x_panel --}}
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        // Supplier select2
        $('#supplier-search').select2();
        $('#supplier-search').on('change', function (e) {
            @this.set('supplier_search', $('#supplier-search').select2("val"));
        });

        // Product select2
        $('#product-search').select2();
        $('#product-search').on('change', function (e) {
            @this.sessionStore(e.target.value);
        });

        // Initialize all datepickers
        function initDatePickers() {
            // Main purchase date picker
            $('#purchase_date_picker_main').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            }).on('changeDate', function(e) {
                @this.set('purchase_date', e.format());
            });

            // Table datepickers
            $('.table-datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            }).on('changeDate', function(e) {
                var rowId = $(this).data('row-id');
                var type = $(this).data('type');
                if (type === 'production_date') {
                    @this.updateProductionDate(rowId, e.format());
                } else if (type === 'expire_date') {
                    @this.updateExpireDate(rowId, e.format());
                }
            });
        }

        initDatePickers();

        // Reinitialize on Livewire updates
        Livewire.on('refresh', function() {
            initDatePickers();
        });

        // Livewire v3 hook if available
        if (typeof Livewire !== 'undefined' && Livewire.hook) {
            Livewire.hook('morph.updated', function({ el, component }) {
                initDatePickers();
            });
        }
    });
</script>
@endpush
