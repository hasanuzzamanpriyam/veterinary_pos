@section('page-title', 'Product Checkout')
<div class="container">
    <div class="product-area single-product">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <h5 class="text-center single_p_title">Checkout</h5>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12">
                        <div class="back_button mb-2">
                            <a href="{{route('live.product.create')}}" class="btn btn-md btn-primary float-right"> <i
                                    class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                        </div>

                    </div>

                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="logo text-center my-3">
                            @if(empty($product['photo']))
                                <h4>No Image Found!</h4>
                            @else
                                <img src="{{asset(ltrim($product['photo'], '/'))}}" class="img-thumbnail img-responsive"
                                    alt="Logo" width="250" height="320">
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <table class="product-data table table-striped">
                            <tr>
                                <th>Product Name</th>
                                <td>{{$product['name']}}</td>
                            </tr>
                            @if($product['barcode'])
                                <tr>
                                    <th>Barcode</th>
                                    <td><svg class="barcode-render" data-barcode="{{$product['barcode']}}"></svg></td>
                                </tr>
                            @endif
                            <tr>
                                <th>Company</th>
                                <td>{{$brands->find($product['brand_id'])->name ?? ''}}</td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td>{{$categories->find($product['category_id'])->name ?? ''}}</td>
                            </tr>
                            <tr>
                                <th>Product Group</th>
                                <td>{{$product_groups->find($product['group_id'])->name ?? ''}}</td>
                            </tr>
                            <tr>
                                <th>Mode</th>
                                <td>{{ucfirst($product['type'])}}</td>
                            </tr>
                            <tr>
                                <th>Size</th>
                                <td>{{$sizes->find($product['size_id'])->description ?? ''}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <table class="product-data table table-striped">

                            <tr>
                                <th>SKU</th>
                                <td>{{$product['sku'] ?? ''}}</td>
                            </tr>
                            <tr>
                                <th>Alert Quantity</th>
                                <td>{{$product['alert_quantity'] ?? ''}}</td>
                            </tr>

                            <tr>
                                <th>TP Rate</th>
                                <td>{{$product['purchase_rate'] ? $product['purchase_rate'] : 0}}/=</td>
                            </tr>
                            <tr>
                                <th>MRP Rate</th>
                                <td>{{$product['mrp_rate'] ? $product['mrp_rate'] : 0}}/=</td>
                            </tr>
                            <tr>
                                <th>Sale Rate</th>
                                <td>{{$product['price_rate'] ? $product['price_rate'] : 0}}/=</td>
                            </tr>
                            <tr>
                                <th>Remarks</th>
                                <td>{{$product['remarks']}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="item form-group">
                            <div class="col-md-12 col-sm-12 text-center">
                                <a href="{{route('product.index')}}" class="btn btn-danger" type="button">Cancel</a>
                                <button class="btn btn-warning" type="reset" wire:click="cancel()">Reset</button>
                                <button type="submit" class="btn btn-success" wire:click="submit()">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>