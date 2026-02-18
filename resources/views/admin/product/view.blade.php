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
                                    <img src="/public{{$product->photo}}" class="img-thumbnail img-responsive" alt="Logo"
                                        width="250" height="320">
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <table class="product-data table table-striped">
                                <tr>
                                    <th>Code</th>
                                    <td>{{$product->code}}</td>
                                </tr>
                                <tr>
                                    <th>Product Name</th>
                                    <td>{{$product->name}}</td>
                                </tr>
                                @if(empty($product->brand_id))
                                @else
                                    <tr>
                                        <th>Brand</th>
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
                                @if(empty($product->size_id))
                                @else
                                    <tr>
                                        <th>Size</th>
                                        <td>{{$product->size->description}}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            @php
                                $item_stock = isset($stock[$product->id]['qty']) ? $stock[$product->id]['qty'] : 0;
                            @endphp

                            <table class="product-data table table-striped">

                                <tr>
                                    <th>Available Stock</th>
                                    <td>{{$item_stock}}</td>
                                </tr>
                                <tr>
                                    <th>Alert Quantity</th>
                                    <td>{{$product->alert_quantity}}</td>
                                </tr>
                                <tr>
                                    <th>Purchase Value</th>
                                    <td>{{formatAmount($item_stock * $product->purchase_rate)}}/-</td>
                                </tr>
                                <tr>
                                    <th>Sale Value</th>
                                    <td>{{formatAmount($item_stock * $product->price_rate)}}/-</td>
                                </tr>
                                <tr>
                                    <th>MRP</th>
                                    <td>{{formatAmount($item_stock * $product->mrp_rate)}}/-</td>
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
        <h3 class="text-center">Stock Details</h3>
        @if ($store_data->isEmpty())
            <div class="alert text-danger text-center">
                <strong>There is no stock!</strong>
            </div>
        @else
            <table id="stock-table" class="product-data table table-striped">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Store/Warehouse Name</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-right">Weight</th>
                        <th class="text-right">Expire Date</th>
                        <th class="text-right">Purchase Value</th>
                        <th class="text-right">Sale Value</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $summary = [];
                    @endphp
                    @foreach($store_data as $key => $value)

                        @if ($value['qty'] == 0)
                            @continue
                        @endif
                        @php
                            $summary['qty'] = $summary['qty'] ?? 0;
                            $summary['qty'] += $value['qty'];
                            $summary['weight'] = $summary['weight'] ?? 0;
                            $summary['weight'] += $value['weight'];
                            $summary['purchase_tk'] = $summary['purchase_tk'] ?? 0;
                            $summary['purchase_tk'] += $value['price'];
                            $summary['sale_tk'] = $summary['sale_tk'] ?? 0;
                            $summary['sale_tk'] += $value['sale_value'];

                        @endphp

                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$value['name']}}</td>
                            <td class="text-center">{{formatAmount($value['qty'])}}
                                {{trans_choice('labels.' . $product->type, $value['qty'])}}</td>
                            <td class="text-right">{{formatAmount($value['weight'])}} MT</td>
                            <td class="text-right">{{ $product['alert_expire_date'] }}</td>
                            <td class="text-right">{{formatAmount($value['price'])}}/=</td>
                            <td class="text-right">{{formatAmount($value['sale_value'])}}/=</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <td></td>
                    <td></td>
                    <td class="text-center"><b>{{formatAmount($summary['qty'] ?? 0) }}
                            {{trans_choice('labels.' . $product->type, $summary['qty'] ?? 0)}}</b></td>
                    <td class="text-right"><b>{{formatAmount($summary['weight'] ?? 0)}} MT</b></td>
                    <td></td>
                    <td class="text-right"><b>{{formatAmount($summary['purchase_tk'] ?? 0)}}/=</b></td>
                    <td class="text-right"><b>{{formatAmount($summary['sale_tk'] ?? 0)}}/=</b></td>
                </tfoot>
            </table>
        @endif
    </div>
@endsection