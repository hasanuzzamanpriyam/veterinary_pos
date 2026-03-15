@section('page-title', 'Stock Adjustment')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <!-- Header with toggle button -->
        <div class="x_title">
            <div class="d-flex align-items-center justify-content-between">
                <h2 class="mb-0">Product Stock Adjustment</h2>
                <a href="#" wire:click.prevent="toggleList" class="btn btn-primary">
                    <i class="fa @if($showList) fa-plus @else fa-list @endif mr-1"></i>
                    <span class="d-none d-sm-inline">
                        @if($showList) Add Adjustment @else Show List @endif
                    </span>
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

            @if($showList)
                <!-- Adjustment History List -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-muted">Stock Adjustment History</h4>
                            <div style="width: 300px;">
                                <input type="text" wire:model.live="searchList" class="form-control"
                                       placeholder="Search by name, date or remarks">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Product</th>
                                        <th>Source Store</th>
                                        <th>Destination Store</th>
                                        <th>Quantity</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($adjustments as $adj)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($adj->date)->format('d-M-Y') }}</td>
                                            <td>{{ $adj->product->name ?? 'N/A' }}</td>
                                            <td>{{ $adj->sourceStore->name ?? 'N/A' }}</td>
                                            <td>{{ $adj->destinationStore->name ?? 'N/A' }}</td>
                                            <td>{{ $adj->quantity }}
                                                {{ $adj->product ? trans_choice(strtolower($adj->product->type), $adj->quantity) : '' }}
                                            </td>
                                            <td>{{ $adj->remarks }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">No stock adjustments found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end">
                            {{ $adjustments->links() }}
                        </div>
                    </div>
                </div>
            @else
                <!-- Adjustment Form -->
                <div class="row">
                    <div class="col-lg-8 col-md-10 mx-auto">
                        <form wire:submit.prevent="stockAdjustment()" enctype="multipart/form-data" data-parsley-validate>
                            @csrf

                            <!-- Store Selection Card -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white py-3">
                                    <h5 class="mb-0">Store Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="source_store_id">Source Store/Warehouse</label>
                                                <select wire:model="source_store_id" id="source_store_id"
                                                        wire:change="source_store_id_update($event.target.value)"
                                                        class="form-control">
                                                    <option value="0">Select Option</option>
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
                                                <label for="destination_store_id">Destination Store/Warehouse</label>
                                                <select wire:model="destination_store_id" id="destination_store_id"
                                                        wire:change="destination_store_id_update($event.target.value)"
                                                        class="form-control">
                                                    <option value="0">Select Option</option>
                                                    @foreach ($stores as $store)
                                                        <option value="{{ $store->id }}">
                                                            {{ $store->name }} — {{ $store->address }} — {{ $store->mobile }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="remarks">Remarks</label>
                                        <input type="text" wire:model="remarks" id="remarks" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!-- Product Selection Card -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white py-3">
                                    <h5 class="mb-0">Add Products</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="product-search">Product</label>
                                                <select class="form-control" id="product-search">
                                                    <option value="">Select Products</option>
                                                    @if (isset($products))
                                                        @foreach ($products as $id => $product)
                                                            <option value="{{ $id }}">
                                                                {{ $product['name'] }} —
                                                                {{ $product['qty'] }} {{ trans_choice($product['type'], $product['qty']) }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cart Table Card -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white py-3">
                                    <h5 class="mb-0">Adjustment Items</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover mb-0">
                                            <thead class="thead-light">
                                                <tr class="text-center">
                                                    <th style="width: 80px;">Code</th>
                                                    <th>Name</th>
                                                    <th style="width: 80px;">Stock</th>
                                                    <th style="width: 100px;">Quantity</th>
                                                    <th style="width: 100px;">Rate</th>
                                                    <th style="width: 120px;">Sub Total</th>
                                                    <th style="width: 80px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $total_price = 0;
                                                    $total_qty = 0;
                                                    $items = 0;
                                                    $summaryQty = [];
                                                @endphp

                                                @forelse (Cart::instance('stock_adjust')->content() as $product)
                                                    @php
                                                        $total_price += $product->qty * $product->price;
                                                        $total_qty += $product->qty;
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
                                                            {{ $product->options->stock }}
                                                            {{ trans_choice(strtolower($product->options->type), $product->options->stock) }}
                                                        </td>
                                                        <td class="align-middle">
                                                            <div class="d-flex align-items-center justify-content-center">
                                                                <input type="text"
                                                                       wire:change="updateQuantity({{ $product->id }}, $event.target.value || 0)"
                                                                       value="{{ $product->qty }}"
                                                                       class="form-control form-control-sm text-center mr-1"
                                                                       style="width: 60px;">
                                                                <span>{{ trans_choice(strtolower($product->options->type), $product->qty) }}</span>
                                                            </div>
                                                            @if($product->options->stock < $product->qty)
                                                                <small class="text-danger d-block">Unavailable</small>
                                                            @endif
                                                        </td>
                                                        <td class="align-middle">
                                                            <input type="text"
                                                                   wire:change="updatePrice({{ $product->id }}, $event.target.value || 0)"
                                                                   value="{{ $product->price }}"
                                                                   class="form-control form-control-sm text-center">
                                                        </td>
                                                        <td class="align-middle">
                                                            <input type="text"
                                                                   value="{{ $product->price * $product->qty }}/-"
                                                                   disabled
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
                                                        <td colspan="7" class="text-center py-4">No items added yet.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                            <tfoot class="bg-light">
                                                <tr>
                                                    <td colspan="2"><strong>Total: {{ $items }} {{ trans_choice('labels.items', $items) }}</strong></td>
                                                    <td></td>
                                                    <td>
                                                        @foreach ($summaryQty as $type => $qty)
                                                            <span class="badge badge-info mr-1">{{ $qty }} {{ $type }}</span>
                                                        @endforeach
                                                    </td>
                                                    <td></td>
                                                    <td class="text-right"><strong>TK: {{ $total_price }}/=</strong></td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-center mt-3">
                                <button type="button" wire:click="cancel" class="btn btn-danger mr-2">
                                    <i class="fa fa-times"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-arrow-right"></i> Proceed to Next Step
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            // Initialize Select2
            $('#destination_store_id').select2();
            $('#source_store_id').select2();
            $('#product-search').select2();

            // Sync Select2 changes with Livewire
            $('#source_store_id').on('change', function (e) {
                @this.set('source_store_id', $('#source_store_id').select2("val"));
            });
            $('#destination_store_id').on('change', function (e) {
                @this.set('destination_store_id', $('#destination_store_id').select2("val"));
            });
            $('#product-search').on('change', function (e) {
                @this.sessionStore(e.target.value);
            });

            // Reinitialize Select2 after Livewire updates
            $(document).on('dataUpdated', function () {
                setTimeout(() => {
                    $('#destination_store_id').select2();
                    $('#source_store_id').select2();
                    $('#product-search').select2();
                }, 10);
            });
        });
    </script>
@endpush