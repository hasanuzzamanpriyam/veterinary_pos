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
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session()->has('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fa fa-info-circle mr-2"></i> {{ session('info') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

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
                                            <select id="product_store_id" class="form-control">
                                                <option value="">Select Store/Warehouse</option>
                                                @foreach ($stores as $store)
                                                    <option value="{{ $store->id }}" {{ $product_store_id == $store->id ? 'selected' : '' }}>
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
                                                            {{ $product->size->name ?? $product->type }}
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
                                                {{ $product->options->type }}
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

    <!-- Product Stock History Panel -->
    <div class="x_panel mt-4">
        <div class="x_title" data-toggle="collapse" data-target="#stockHistoryCollapse" style="cursor: pointer;">
            <h2>Product Stock History <small>(Click to toggle)</small></h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content collapse" id="stockHistoryCollapse" wire:ignore.self>
            <div class="row mb-3 align-items-center">
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <label for="perPage" class="mr-2 mb-0">Show:</label>
                        <select id="perPage" wire:model.live="perPage" class="form-control" style="width: auto;">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="ml-2">Entries</span>
                    </div>
                </div>
                <div class="col-md-5 ml-auto">
                    <div class="input-group">
                        <input type="text" wire:model.live="stockHistorySearch" class="form-control" placeholder="Search by Product or Store...">
                        <div class="input-group-append">
                            <button class="btn btn-danger" type="button" wire:click="$set('stockHistorySearch', '')">
                                <i class="fa fa-times"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>SL</th>
                            <th>Date</th>
                            <th>Date</th>
                            <th>Product Name</th>
                            <th>Company Name</th>
                            <th>Category</th>
                            <!-- <th>Store</th> -->
                            <th>Quantity</th>
                            <th>Purchase Price</th>
                            <th>Sale Rate</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($paginated_stock_list as $key => $stock)
                            <tr class="text-center">
                                <td>{{ $paginated_stock_list->firstItem() + $key }}</td>
                                <td>{{ $stock->created_at ? $stock->created_at->format('d-m-Y') : 'N/A' }}</td>
                                <td>{{ $stock->created_at ? $stock->created_at->format('d-m-Y') : 'N/A' }}</td>
                                <td class="text-left">{{ $stock->product->name ?? 'N/A' }}</td>
                                <td>{{ $stock->product->brand->name ?? 'N/A' }}</td>
                                <td>{{ $stock->product->category->name ?? 'N/A' }}</td>
                                <!-- <td>{{ $stock->store->name ?? 'N/A' }}</td> -->
                                <td>{{ $stock->product_quantity }}</td>
                                <td>{{ $stock->purchase_price }}/=</td>
                                <td>{{ $stock->product->price_rate ?? 'N/A' }}{{ isset($stock->product->price_rate) ? '/=' : '' }}</td>
                                <td>
                                    <button type="button" class="btn btn-info btn-xs" wire:click="editStock({{ $stock->id }})" title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-xs" 
                                            onclick="confirm('Are you sure you want to delete this stock entry?') || event.stopImmediatePropagation()"
                                            wire:click="deleteStock({{ $stock->id }})" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">No stock data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3 d-flex justify-content-end">
                {{ $paginated_stock_list->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Initial Select2 load
            $('#product_store_id').select2({ width: '100%' });
            $('#product-search').select2({ width: '100%' });

            // Reinitialize Select2 after Livewire updates
            window.addEventListener('dataUpdated', function () {
                setTimeout(() => {
                    $('#product_store_id').select2({ width: '100%' });
                    $('#product-search').select2({ width: '100%' });
                }, 10);
            });

            // Use delegated events to survive DOM replacements
            $(document).on('change', '#product_store_id', function (e) {
                @this.set('product_store_id', $(this).val());
            });

            $(document).on('change', '#product-search', function (e) {
                let val = $(this).val();
                if (val) {
                    @this.sessionStore(val);
                    // Optionally clear the selection so you can pick it again later
                    $(this).val('').trigger('change.select2'); 
                }
            });
        });
    </script>
@endpush