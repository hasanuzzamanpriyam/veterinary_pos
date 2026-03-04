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
                                    <img src="{{ $photoUrl }}" class="img-thumbnail img-responsive" alt="Logo"
                                        width="250" height="320">
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
                    <tr>
                        <td class="text-center">1</td>
                        <td class="text-center">2024-01-15</td>
                        <td class="text-center">Main Store</td>
                        <td class="text-center">100 Pcs</td>
                        <td class="text-right">500.00</td>
                        <td class="text-right">50,000.00</td>
                        <td class="text-right">650.00</td>
                        <td class="text-right">65,000.00</td>
                        <td class="text-center">2023-12-01</td>
                        <td class="text-center">2025-12-01</td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td class="text-center">2024-02-10</td>
                        <td class="text-center">Warehouse A</td>
                        <td class="text-center">50 Pcs</td>
                        <td class="text-right">510.00</td>
                        <td class="text-right">25,500.00</td>
                        <td class="text-right">660.00</td>
                        <td class="text-right">33,000.00</td>
                        <td class="text-center">2024-01-05</td>
                        <td class="text-center">2026-01-05</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="font-weight-bold">
                        <td colspan="3" class="text-right">Total:</td>
                        <td class="text-center">150 Pcs</td>
                        <td></td>
                        <td class="text-right">75,500.00</td>
                        <td></td>
                        <td class="text-right">98,000.00</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection