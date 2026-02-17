@extends('layouts.admin')

@section('page-title')
Product Add to Price Group
@endsection

@section('main-content')
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">

          <div class="row">
            <div class="col-lg-8 md-8 sm-8">
                <div class="header-title d-flex align-items-center gap-2">
                    <h2 class="mr-auto update-price-title">Update Price Rate</h2>
                </div>
               </div>
          </div>

        </div>
        <div class="x_content p-3">
            <br />
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{route('price_group.store.product')}}" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left update-price-rate-from">
                @csrf
                <div class="row m-auto">

                </div>
                <div class="row">

                    {{-- @dump($price_group) --}}

                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center">
                            <div class="form-group">

                                <input type="text" id="name" name="name" value="{{$price_group->name}}" class="form-control update-price-rate">
                                <input type="hidden" name="price_group_id" value="{{$price_group->id}}">
                            </div>
                    </div>


                    <div class="col-lg-12 col-md-12 col-sm-12">
                        {{-- @dump($products) --}}
                        <table id="PriceGroupList" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Select</th>
                                    <th>Code</th>
                                    <th style="width:185px">Name</th>
                                    <th style="width: 90px">Brand</th>
                                    <th style="width: 90px" >Category</th>
                                    <th style="width: 90px">Group</th>
                                    <th>Size</th>
                                    <th>Type</th>
                                    <th>Prev Rate</th>
                                    <th>Present Rate</th>
                                    <th>Update Rate</th>
                                </tr>
                            </thead>
                            <tbody>



                                @foreach ($products as $product)
                                <tr>
                                    <td><input type="checkbox" name="product_id[]" value="{{$product->id}}" class="form-control btn btn-primary"></td>
                                    <td>{{ $product->code}}</td>
                                    <td class="text-left">{{ $product->name}}</td>
                                    <td class="text-left">{{ $product->brand->name}}</td>
                                    <td class="text-left">{{ $product->category->name ?? " "}}</td>
                                    <td class="text-left">{{ $product->productGroup->name}}</td>
                                    <td>{{ $product->size->description}}</td>
                                    <td>{{ ucfirst($product->type)}}</td>
                                    <td>{{ $product->price_rate}}/=</td>

                                    @php

                                        $price_group_products = DB::table('price_group_products')->where('price_group_id', $price_group->id)->where('product_id',$product->id)->first();

                                        // var_dump($price_group_products);
                                    @endphp

                                    @if (!empty($price_group_products))
                                    <td class="text-right">{{ $price_group_products->price_group_rate}}/=</td>
                                    @else
                                    <td class="text-right">{{ $product->price_rate}}/=</td>
                                    @endif

                                    <td><input type="number" name="price_group_rate[]" class="form-control"></td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>




                    </div>
                </div>

                <div class="ln_solid"></div>
                <div class="item form-group">
                    <div class="col-md-12 col-sm-12 text-center">
                        <a href="{{route('price_group.index')}}" class="btn btn-danger" type="button">Cancel</a>

                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>

    $(document).ready(function () {
        $("#PriceGroupList").DataTable({
            ordering: false,
        });
    });

</script>
@endpush
