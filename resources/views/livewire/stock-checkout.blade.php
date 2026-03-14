<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <div class="d-flex align-items-center justify-content-between">
                <h2>Stock Checkout</h2>
                <a href="#" wire:click="cancel()" class="btn btn-sm btn-outline-secondary">
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
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form wire:submit.prevent="stockStore()" enctype="multipart/form-data" data-parsley-validate>
                @csrf
                <div class="row">
                    <div class="col-lg-8 col-md-8 mx-auto">
                        <!-- Store Info Card -->
                        @if ($product_store_data)
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white py-3">
                                    <h5 class="mb-0">Store Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr>
                                            <th style="width: 120px;">ID</th>
                                            <td>{{ $product_store_data['id'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $product_store_data['name'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <td>{{ $product_store_data['address'] }}</td>
                                        </tr>
                                        <tr>
                                            <th>Mobile</th>
                                            <td>{{ $product_store_data['mobile'] }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- Products Card -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0">Products to Checkout</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr class="text-center">
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Quantity</th>
                                                <th>Prev Stock</th>
                                                <th>Price Rate</th>
                                                <th>Sub Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $total_qty = 0;
                                                $items = 0;
                                                $summaryQty = [];
                                            @endphp

                                            @forelse ($products as $product)
                                                @php
                                                    $total_qty += $product->qty;
                                                    $items++;
                                                    $summaryQty[$product->options->type] = ($summaryQty[$product->options->type] ?? 0) + $product->qty;
                                                @endphp
                                                <tr>
                                                    <td class="align-middle text-center">
                                                        @if($product->options->barcode)
                                                            <svg class="barcode-render" data-barcode="{{ $product->options->barcode }}"
                                                                 style="height: 25px; max-width: 100%;"></svg>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle">{{ $product->name }}</td>
                                                    <td class="align-middle text-center">
                                                        {{ $product->qty }} {{ trans_choice($product->options->type, $product->qty) }}
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        {{ $product->options->stock }} {{ trans_choice($product->options->type, $product->options->stock) }}
                                                    </td>
                                                    <td class="align-middle text-right">{{ $product->price }}/=</td>
                                                    <td class="align-middle text-right">{{ $product->qty * $product->price }}/=</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4">No products found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot class="bg-light">
                                            <tr>
                                                <td><strong>Items:</strong> {{ $items }}</td>
                                                <td></td>
                                                <td>
                                                    @foreach ($summaryQty as $type => $qty)
                                                        <span class="badge badge-info mr-1">{{ $qty }} {{ $type }}</span>
                                                    @endforeach
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right"><strong>{{ $grand_total }}/=</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-center mt-3">
                            <button type="button" wire:click="back" class="btn btn-primary mr-2">
                                <i class="fa fa-arrow-left"></i> Back
                            </button>
                            <button type="button" wire:click="cancel" class="btn btn-danger mr-2">
                                <i class="fa fa-times"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-plus"></i> Add Stock
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>