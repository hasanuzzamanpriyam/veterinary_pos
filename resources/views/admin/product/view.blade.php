@extends('layouts.admin')

@section('page-title')
    Product View
@endsection

@section('main-content')
    <div class="container">
        <div class="product-area single-product">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <h5 class="text-center single_p_title">{{$product->name}} </h5>
                            <div class="text-center">
                                @if($product->barcode)
                                    <svg class="barcode-render" data-barcode="{{$product->barcode}}"></svg>
                                @endif
                            </div>

                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12">
                            <div class="back_button mb-2">
                                <a href="{{route('product.index')}}" class="btn btn-md btn-primary float-right"> <i
                                        class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- @dump($product) --}}
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="logo text-center my-3">
                                @if(empty($product->photo))
                                    <h4>No Image Found!</h4>
                                @else
                                    @php
                                        $photoPath = $product->photo;
                                        if (strpos($photoPath, public_path()) === 0) {
                                            $photoPath = str_replace(public_path(), '', $photoPath);
                                        } elseif (strpos($photoPath, base_path()) === 0) {
                                            $photoPath = str_replace(base_path() . '/public', '', $photoPath);
                                        }
                                        $photoPath = str_replace('\\', '/', $photoPath);
                                        $photoUrl = asset(ltrim($photoPath, '/'));
                                    @endphp
                                    <img src="{{ $photoUrl }}" class="img-thumbnail img-responsive" alt="Logo" width="250"
                                        height="320">
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <table class="product-data table table-striped">
                                <tr>
                                    <th>Product Name</th>
                                    <td>{{$product->name}}</td>
                                </tr>
                                @if(empty($product->brand_id))
                                @else
                                    <tr>
                                        <th>Company</th>
                                        <td>{{$product->brand->name}}</td>
                                    </tr>
                                @endif
                                @if(empty($product->category_id))
                                @else
                                    <tr>
                                        <th>Category</th>
                                        <td>{{$product->category->name}}</td>
                                    </tr>
                                @endif
                                @if(empty($product->group_id))
                                @else
                                    <tr>
                                        <th>Product Group</th>
                                        <td>{{$product->productGroup->name}}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Type</th>
                                    <td>{{$product->type}}</td>
                                </tr>

                            </table>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            @php
                                $item_stock = isset($stock[$product->id]['qty']) ? $stock[$product->id]['qty'] : 0;
                            @endphp

                            <table class="product-data table table-striped">
                                @if(empty($product->size_id))
                                @else
                                    <tr>
                                        <th>Size</th>
                                        <td>{{$product->size->description}}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Available Stock</th>
                                    <td>{{$item_stock}}</td>
                                </tr>
                                <tr>
                                    <th>Alert Quantity</th>
                                    <td>{{$product->alert_quantity}}</td>
                                </tr>
                                <tr>
                                    <th>Remarks</th>
                                    <td>{{$product->remarks}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <h3 class="text-center mt-4">Alternative Product List</h3>
        <div class="table-responsive">
            <table class="product-data table table-striped table-bordered mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center">SL</th>
                        <th class="text-center">Product Name</th>
                        <th class="text-center">Company</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alternative_products as $alt_product)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $alt_product->name }}</td>
                            <td class="text-center">{{ $alt_product->brand->name ?? 'N/A' }}</td>
                            <td class="text-center">{{ $alt_product->category->name ?? 'N/A' }}</td>
                            <td class="text-center">
                                <a href="{{ route('product.view', $alt_product->id) }}" class="btn btn-sm btn-info">
                                    <i class="fa fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center"><b>No alternative products found!</b></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h3 class="text-center">Stock Details</h3>
        <div class="table-responsive">
            <table id="stock-table" class="product-data table table-striped table-bordered mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center">SL</th>
                        <th class="text-center">Purchase Date</th>
                        <th class="text-center">Store</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Purchase Rate</th>
                        <th class="text-center">Purchase Value</th>
                        <th class="text-center">Sales Rate</th>
                        <th class="text-center">Sales Value</th>
                        <th class="text-center">Production Date</th>
                        <th class="text-center">Expire Date</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $summary = [
                            'qty' => 0,
                            'purchase_tk' => 0,
                            'sale_tk' => 0,
                        ];
                    @endphp
                    @forelse($stock_history as $key => $stockEntry)
                        @php
                            $qty = floatval($stockEntry->quantity);
                            $purchase_value = floatval($stockEntry->total_price);
                            $purchase_rate = $qty > 0 ? $purchase_value / $qty : 0;
                            $sale_value = $qty * floatval($product->price_rate);

                            $summary['qty'] += $qty;
                            $summary['purchase_tk'] += $purchase_value;
                            $summary['sale_tk'] += $sale_value;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $stockEntry->date ? \Carbon\Carbon::parse($stockEntry->date)->format('d-m-Y') : \Carbon\Carbon::parse($stockEntry->created_at)->format('d-m-Y') }}</td>
                            <td class="text-center">{{ $stockEntry->store->name ?? 'N/A' }}</td>
                            <td class="text-center">{{ formatAmount($qty) }} {{ trans_choice($product->type, $qty) }}</td>
                            <td class="text-right">{{ formatAmount($purchase_rate) }}</td>
                            <td class="text-right">{{ formatAmount($purchase_value) }}</td>
                            <td class="text-right">{{ formatAmount($product->price_rate) }}</td>
                            <td class="text-right">{{ formatAmount($sale_value) }}</td>
                            <td class="text-center">{{ $stockEntry->production_date ? \Carbon\Carbon::parse($stockEntry->production_date)->format('d-m-Y') : 'N/A' }}</td>
                            <td class="text-center">{{ $stockEntry->expire_date ? \Carbon\Carbon::parse($stockEntry->expire_date)->format('d-m-Y') : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center"><b>There is no stock available!</b></td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                        <td colspan="3" class="text-right">Total:</td>
                        <td class="text-center">{{ formatAmount($summary['qty']) }}
                            {{ trans_choice($product->type, $summary['qty']) }}</td>
                        <td></td>
                        <td class="text-right">{{ formatAmount($summary['purchase_tk']) }}</td>
                        <td></td>
                        <td class="text-right">{{ formatAmount($summary['sale_tk']) }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
@endsection