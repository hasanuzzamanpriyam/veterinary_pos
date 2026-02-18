@section('page-title', 'Product Stock')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2 ">
                <h2>Product Stock</h2>
                <ul class="nav navbar-right panel_toolbox mr-auto">
                    <li><span class="collapse-link btn btn-md btn-primary text-white "><i class="fa fa-eye"></i> Advance</span>
                    </li>
                </ul>
                <a href="{{route('live.product.create')}}" class="btn btn-md btn-primary"> <i class="fa fa-plus" aria-hidden="true"></i> Add Product</a>
            </div>
        </div>
        <div class="p-3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 x_content" style="display: none" wire:ignore>
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="supplier-search-area">
                                <label  class="py-1 border">Store/Warehouse</label>
                                <div class="form-group">
                                    <select type="search" id="store_id" wire:model="store_id" name="store_id" placeholder="Select Store/Warehouse" class="form-control">
                                        <option value="all">All</option>
                                        @foreach($stores as $key => $store)
                                            <option value="{{$store->id}}" @if ($store_id == $store->id) selected @endif>{{$store->name}} - {{$store->address}} - {{$store->mobile}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="supplier-search-area">
                                <label  class="py-1 border">Products</label>
                                <div class="form-group">
                                    <select type="search" id="product_id" wire:model ="product_id" name="product_id" placeholder="Selct Product" class="form-control">
                                        <option value="all">All</option>
                                        @foreach ($all_products as $product)
                                            <option value="{{$product->id}}" @if ($product_id == $product->id) selected @endif>
                                                {{$product->code}} - {{$product->name}} - {{$product->brand->name ?? " "}} - {{$product->category->name ?? " "}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 d-flex align-items-end gap-2" style="padding-bottom: 10px;">

                            <button type="button" class="btn btn-primary btn-sm" id="search-btn" wire:click="productSearch"><i class="fa fa-search"></i> Get</button>
                            <button type="button" class="btn btn-warning btn-sm" id="reset-btn" wire:click="resetData"><i class="fa fa-refresh"></i> Reset</button>
                        </div>
                    </div>
                </div>
                {{-- notification message --}}
                @if(session()->has('msg'))
                    <div class="text-center alert alert-success">
                        {{session()->get('msg')}}
                    </div>
                @endif
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    {{cute_loader()}}
                    <div class="table-header d-flex align-items-center justify-content-between">
                        <div class="per-page">
                            <div class="form-group">
                                <select id="perpage" class="form-control" wire:model.live="perPage">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="all">All</option>
                                </select>
                            </div>
                        </div>
                        <div class="ajax-search d-flex align-items-center gap-2">
                            <div class="form-group">
                                <input type="text" wire:model="queryString" class="form-control form-control-sm" style="min-width: 300px"/>
                            </div>

                            <div class="form-group">
                                <button type="button" class="btn btn-primary btn-sm" wire:click="productSearch" style="min-width: 100px"><i class="fa fa-search"></i> Get</button>
                                <button type="reset" class="btn btn-warning btn-sm" wire:click="resetData" style="min-width: 100px"><i class="fa fa-refresh"></i> Reset</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-box table-responsive">
                        <table id="" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">Code</th>
                                    <th class="all">Name</th>
                                    <th class="all">Brand</th>
                                    <th class="all">Category</th>
                                    <th class="all">Group</th>
                                    <th class="all">Size</th>
                                    <th class="all">Type</th>
                                    <th class="all">Stock</th>
                                    <th class="all">Weight</th>
                                    <th class="all">Purchase Value</th>
                                    <th class="all">Sale Value</th>
                                    <th class="all">MRP Value</th>
                                    <th class="all">Offer</th>
                                    <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                    $items = 0;
                                    $gtotal_purchase_price = 0;
                                    $gtotal_sale_price = 0;
                                    $gtotal_mrp_price = 0;
                                    $gtotal_stock = [];
                                    $gtotal_matricton = 0;
                                @endphp
                                @if (count($stock_list) > 0)
                                    @foreach($stock_list as $key => $stock)
                                        @php
                                            $items++;
                                            $purchase_price = isset($stock['purchase_price']) ? $stock['qty'] * $stock['purchase_price'] : 0;
                                            $sale_price = isset($stock['sale_price']) ? $stock['qty'] * $stock['sale_price'] : 0;
                                            $totatl_weight = $stock['qty'] * $stock['size'] / 1000;


                                            $gtotal_purchase_price += $purchase_price;
                                            $gtotal_sale_price += $sale_price;

                                            $mrp_price = isset($stock['mrp_price']) ? $stock['qty'] * $stock['mrp_price'] : 0;
                                            $gtotal_mrp_price += $mrp_price;
                                            
                                            $gtotal_matricton += $totatl_weight;

                                            $type = $stock['type'];
                                            $gtotal_stock['qty'][$type] = $gtotal_stock['qty'][$type] ?? 0;
                                            $quantity = $stock['qty'];
                                            $gtotal_stock['qty'][$type] += $quantity;
                                        @endphp
                                        <tr>
                                            <td>{{$stock['code']}}</td>
                                            <td class="text-left">{{$stock['product_name']}}</td>
                                            <td class="text-left">{{$stock['brand']}}</td>
                                            <td class="text-left">{{$stock['category'] ?? ""}}</td>
                                            <td class="text-left">{{$stock['group'] ?? ''}}</td>
                                            <td>{{$stock['size']}}</td>
                                            <td>{{ucfirst($type)}}</td>
                                            <td>{{formatAmount($quantity)}}</td>
                                            <td>{{formatAmount($totatl_weight)}}</td>
                                            <td class="text-right">{{ $purchase_price ? formatAmount($purchase_price) . '/-' : '' }}</td>
                                            <td class="text-right">{{ $sale_price ? formatAmount($sale_price) . '/-' : '' }}</td>
                                            <td class="text-right">{{ $mrp_price ? formatAmount($mrp_price) . '/-' : '' }}</td>
                                            <td class="text-right">
                                                @if(isset($stock['offer']) && $stock['offer'])
                                                    @php $offer = $stock['offer']; @endphp
                                                    @if($offer->type === \App\Models\ProductOffer::TYPE_PERCENTAGE)
                                                        {{ rtrim(rtrim(number_format($offer->value, 4, '.', ''), '0'), '.') }}%
                                                    @else
                                                        {{ formatAmount($offer->value) }} /-
                                                    @endif
                                                    <div class="small text-muted">New: {{ formatAmount($stock['sale_price_with_offer']) }}/-</div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-vertical customer_diplay_list">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                        data-toggle="dropdown">
                                                        <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="{{route('product.view', $stock['product_id'])}}" class="btn btn-info d-flex align-items-center gap-2"><i class="fa fa-eye" ></i><span>View</span></a></li>
                                                        <li><a href="{{route('product.delete', $stock['product_id'])}}" class="btn btn-danger d-flex align-items-center gap-2" id="delete"><i class="fa fa-trash" ></i><span>Delete</span></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td  class="text-right"></td>
                                    <td  class="text-right"></td>
                                    <td  class="text-right"></td>
                                    <td  class="text-right"></td>
                                    <td  class="text-right"></td>
                                    <td  class="text-right"></td>
                                    <td  class="text-right"></td>
                                    <td  class="text-center">
                                        <div>
                                            @if( isset($gtotal_stock['qty']) && $gtotal_stock['qty'] > 0)
                                                @php
                                                    // sort by key
                                                    ksort($gtotal_stock['qty']);
                                                @endphp
                                                @foreach ($gtotal_stock['qty'] as $key => $value)
                                                    <div><strong>{{ formatAmount($value) }}</strong></div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </td>
                                    <td  class="text-right"><strong>{{formatAmount($gtotal_matricton)}}</strong></td>
                                    <td  class="text-right"><strong>{{formatAmount($gtotal_purchase_price)}}/-</strong></td>
                                    <td  class="text-right"><strong>{{formatAmount($gtotal_sale_price)}}/-</strong></td>
                                    <td  class="text-right"><strong>{{formatAmount($gtotal_mrp_price)}}/-</strong></td>
                                    <td  class="text-right"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $stock_list->links() }}
                </div>
            </div>

        </div>
    </div>
</div>


@push('scripts')
<script>
    jQuery(document).ready(function($) {
        $('#store_id').select2();
        $('#store_id').on('select2:select', function (e) {
            const selectedValue = e.params.data.id;
            const selectedText = e.params.data.text;
            @this.set('store_id', selectedValue);
        });
        $('#product_id').select2();
        $('#product_id').on('select2:select', function (e) {
            const selectedValue = e.params.data.id;
            const selectedText = e.params.data.text;
            @this.set('product_id', selectedValue);
        });
    })
</script>
@endpush
