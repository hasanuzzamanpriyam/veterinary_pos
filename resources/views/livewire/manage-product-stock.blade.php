@section('page-title', 'Manage Stock')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <div class="d-flex align-items-center justify-content-between">
                <h2>Manage Product Stock</h2>
                <a href="{{ route('product.stock') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-times"></i> Close
                </a>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li><i class="fa fa-info-circle mr-2"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form wire:submit.prevent="stockUpdate()" enctype="multipart/form-data" data-parsley-validate>
                @csrf
                <div class="row mb-4">
                    <div class="col-lg-8 col-md-10 mx-auto">
                        <!-- Store & Product Selection Card -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_store_id" class="font-weight-bold">Store/Warehouse</label>
                                            <select wire:model="product_store_id" id="product_store_id"
                                                    wire:change="productSearch($event.target.value)"
                                                    class="form-control">
                                                <option value="">Select Store/Warehouse</option>
                                                @foreach ($stores as $store)
                                                    <option value="{{ $store->id }}">
                                                        {{ $store->name }} — {{ $store->address }} — {{ $store->mobile }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product-search" class="font-weight-bold">Product</label>
                                            <select class="form-control" id="product-search">
                                                <option value="">Select Products</option>
                                                @if (isset($products))
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}">
                                                            {{ $product->name }} —
                                                            {{ $product['qty'] ?? 0 }}
                                                            {{ isset($product_stores[$product->id]) ? $product_stores[$product->id]['qty'] : 0 }}
                                                            {{ trans_choice($product->type, isset($product_stores[$product->id]) ? $product_stores[$product->id]['qty'] : 0) }}
                                                            — {{ $product->purchase_rate }}/=
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cart Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr class="text-center">
                                        <th style="width: 80px;">Code</th>
                                        <th>Name</th>
                                        <th style="width: 100px;">Quantity</th>
                                        <th style="width: 100px;">Prev Stock</th>
                                        <th style="width: 100px;">Rate</th>
                                        <th style="width: 120px;">Sub Total</th>
                                        <th style="width: 80px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_price = 0;
                                        $items = 0;
                                        $summaryQty = [];
                                    @endphp

                                    @forelse (Cart::instance('manage_stock')->content() as $product)
                                        @php
                                            $total_price += $product->qty * $product->price;
                                            $items++;
                                            $summaryQty[$product->options->type] = ($summaryQty[$product->options->type] ?? 0) + $product->qty;
                                        @endphp
                                        <tr class="text-center">
                                            <td class="align-middle">
                                                <span>{{ $product->options->code }}</span>
                                                @if($product->options->barcode)
                                                    <svg class="barcode-render d-block mx-auto mt-1"
                                                         data-barcode="{{ $product->options->barcode }}"
                                                         style="height: 25px;"></svg>
                                                @endif
                                            </td>
                                            <td class="align-middle text-left">{{ $product->name }}</td>
                                            <td class="align-middle">
                                                <input type="text"
                                                       wire:change="updateQuantity({{ $product->id }}, $event.target.value || 0)"
                                                       value="{{ $product->qty }}"
                                                       class="form-control form-control-sm text-center">
                                            </td>
                                            <td class="align-middle">
                                                {{ $product->options->stock }}
                                                {{ trans_choice($product->options->type, $product->options->stock) }}
                                            </td>
                                            <td class="align-middle">
                                                <input type="text"
                                                       wire:change="updatePrice({{ $product->id }}, $event.target.value || 0)"
                                                       value="{{ $product->price }}"
                                                       class="form-control form-control-sm text-center">
                                            </td>
                                            <td class="align-middle">
                                                <input type="text" value="{{ $product->price * $product->qty }}/-" disabled
                                                       class="form-control form-control-sm text-center bg-light">
                                            </td>
                                            <td class="align-middle">
                                                <button type="button" class="btn btn-danger btn-sm"
                                                        wire:click="itemRemove('{{ $product->rowId }}')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">No products added yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light">
                                        <td colspan="1"><strong>{{ $items }} Items</strong></td>
                                        <td colspan="2">
                                            @foreach ($summaryQty as $type => $qty)
                                                <span class="badge badge-info mr-1">{{ $qty }} {{ $type }}</span>
                                            @endforeach
                                        </td>
                                        <td colspan="3" class="text-right">
                                            <strong>Total: {{ $total_price }}/=</strong>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-center mt-4">
                            <button type="button" wire:click="cancel" class="btn btn-danger mr-2">
                                <i class="fa fa-times"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" @if($items == 0) disabled @endif>
                                <i class="fa fa-check"></i> Checkout
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Stock List Panel -->
    <div class="x_panel mt-4">
        <div class="x_title">
            <h2>Stock List</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Store</th>
                            <th>Quantity</th>
                            <th>Purchase Price</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stock_list as $key => $stock)
                            <tr class="text-center">
                                <td>{{ $key + 1 }}</td>
                                <td class="text-left">{{ $stock->product->name ?? 'N/A' }}</td>
                                <td>{{ $stock->store->name ?? 'N/A' }}</td>
                                <td>{{ $stock->product_quantity }}</td>
                                <td>{{ $stock->purchase_price }}/=</td>
                                <td>{{ $stock->created_at ? $stock->created_at->format('d-m-Y') : 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No stock data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            // Reinitialize Select2 after Livewire updates
            $(document).on('dataUpdated', function () {
                setTimeout(() => {
                    $('#product_store_id').select2();
                    $('#product-search').select2();
                }, 10);
            });

            $('#product_store_id').on('change', function (e) {
                @this.set('product_store_id', $('#product_store_id').select2("val"));
            });

            $('#product-search').on('change', function (e) {
                @this.sessionStore(e.target.value);
            });
        });
    </script>
@endpush