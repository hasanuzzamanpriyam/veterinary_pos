@section('page-title', 'Product List')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center justify-content-between gap-2">
                <h2 class="mr-auto">Product List</h2>
                <a href="{{ route('live.product.create') }}" class="btn btn-md btn-primary">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add Product
                </a>
            </div>
        </div>
        <div class="x_content p-3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="table-responsive">
                        {{ cute_loader() }}
                        @if(session()->has('msg'))
                            <div class="text-center alert alert-success">
                                {{ session()->get('msg') }}
                            </div>
                        @endif

                        <div class="mb-3 d-flex gap-3 align-items-center flex-wrap">
                            <div class="search-box mr-auto">
                                <input type="text" wire:model.live="search" class="form-control" 
                                    placeholder="Search by name, barcode, brand, category, group, size..." style="min-width: 300px;">
                            </div>
                            <div class="per-page-select d-flex align-items-center gap-2">
                                <span class="text-muted">Show</span>
                                <select wire:model.live="perPage" class="form-control" style="width: auto;">
                                    <option value="15">15</option>
                                    <option value="35">35</option>
                                    <option value="100">100</option>
                                    <option value="500">All</option>
                                </select>
                            </div>
                        </div>

                        <table id="productListTable" class="table table-striped table-bordered dt-responsive nowrap"
                            cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">S.N.</th>
                                    <th class="all">Code</th>
                                    <th class="all">image</th>
                                    <th class="all">Name</th>
                                    <th class="all">Company</th>
                                    <th class="all">Category</th>
                                    <th class="all">Group</th>
                                    <th class="all">Size</th>
                                    <th class="all">Type</th>
                                    <th class="all">Stock</th>
                                    <th class="all">TP Rate</th>
                                    <th class="all">MRP Rate</th>
                                    <th class="all">Sales Rate</th>
                                    <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $barcodeGenerator = new Picqer\Barcode\BarcodeGeneratorSVG();
                                @endphp
                                @forelse($products as $product)
                                    @php
                                        $stock_qty = isset($stockList[$product->id]) ? $stockList[$product->id]['qty'] : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                        
                                        <td class="text-center">
                                            @if($product->barcode)
                                                <div class="d-inline-block bg-white border rounded pt-2 pb-1 px-2 text-center" style="min-width: 90px;">
                                                    <div style="height: 30px; display: flex; align-items: center; justify-content: center;">
                                                        {!! $barcodeGenerator->getBarcode($product->barcode, $barcodeGenerator::TYPE_CODE_128, 1.5, 30, 'black') !!}
                                                    </div>
                                                    <div class="mt-1" style="font-size: 13px; font-family: monospace; letter-spacing: 2px;">
                                                        {{ $product->barcode }}
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">No Barcode</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($product->photo)
                                                <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>

                                        <td class="text-left">{{ $product->name }}</td>

                                        <td class="text-left">{{ $product->brand->name ?? '' }}</td>

                                        <td class="text-left">{{ $product->category->name ?? '' }}</td>

                                        <td class="text-left">{{ $product->productGroup->name ?? '' }}</td>

                                        <td>{{ $product->size->name ?? '' }}</td>

                                        <td>{{ ucfirst($product->type) }}</td>

                                        <td>{{ $stock_qty }}</td>

                                        <td class="text-right">{{ $product->purchase_rate ? formatAmount($product->purchase_rate) . '/-' : '' }}</td>

                                        <td class="text-right">{{ $product->mrp_rate ? formatAmount($product->mrp_rate) . '/-' : '' }}</td>

                                        <td class="text-right">{{ $product->price_rate ? formatAmount($product->price_rate) . '/-' : '' }}</td>

                                        <td>
                                            <div class="btn-group btn-group-vertical customer_diplay_list">
                                                <button type="button"
                                                    class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                    data-toggle="dropdown">
                                                    <i class="fa fa-list"></i> <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <a href="{{ route('product.edit', $product->id) }}"
                                                            class="btn btn-success d-flex align-items-center gap-2">
                                                            <i class="fa fa-edit"></i><span>Edit</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('product.view', $product->id) }}"
                                                            class="btn btn-info d-flex align-items-center gap-2">
                                                            <i class="fa fa-eye"></i><span>View</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('product.delete', $product->id) }}"
                                                            class="btn btn-danger d-flex align-items-center gap-2"
                                                            id="delete">
                                                            <i class="fa fa-trash"></i><span>Delete</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center">No products found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-end align-items-center mt-3">
                            <div class="me-3">
                                {{ $products->links() }}
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
    // Barcode rendering is handled by Alpine.js x-init on each row
</script>
@endpush
