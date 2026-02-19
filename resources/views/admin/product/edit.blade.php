@extends('layouts.admin')

@section('page-title')
    Product Update
@endsection

@section('main-content')
{{-- Image plugin css --}}
<link rel="stylesheet" href="{{asset('assets/css/dropify.min.css')}}" />

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title">
            <div class="header-title d-flex align-items-center gap-2 p-3">
                <h2>Update Product</h2>
                <a href="#" class="mr-auto ml-3 cursor-pointer" onclick="history.back()"><i class="fa fa-close"></i></a>
            </div>
        </div>
        <div class="x_content">
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
            <form action="{{route('product.update')}}" method="post" enctype="multipart/form-data" id="demo-form2"
                data-parsley-validate class="form-horizontal form-label-left">
                @csrf
                <div class="row m-auto">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align sp_update_title"
                                for="code">Product Code <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="code" name="code" value="{{$product->code}}"
                                    class="form-control">
                                <input type="hidden" id="id" name="id" value="{{$product->id}}">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align sp_update_title"
                                for="name">Product Name <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="name" name="name" value="{{$product->name}}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align sp_update_title"
                                for="brand_id">Brand<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <select name="brand_id" id="brand_id" class="form-control">
                                    <option value="">Select Option</option>
                                    @foreach($brands as $brand)
                                        <option value="{{$brand->id}}" @if($brand->id == $product->brand_id) selected=""
                                        @endif>{{$brand->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align sp_update_title"
                                for="group">Group<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <select name="group_id" id="group_id" class="form-control">
                                    @foreach($product_groups as $product_group)
                                        <option value="{{$product_group->id}}" @if($product->group_id == $product_group->id)
                                        selected="" @endif>{{$product_group->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align sp_update_title"
                                for="size_id">Size<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <select name="size_id" id="size_id" class="form-control">
                                    <option value="">Select Option</option>
                                    @foreach($sizes as $size)
                                        <option value="{{$size->id}}" @if($size->id == $product->size_id) selected="" @endif>
                                            {{$size->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align sp_update_title"
                                for="type">Type<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                @php($types = array(
                                    'bag' => 'Bag',
                                    'kg' => 'Kg',
                                    'dozen' => 'Dozen',
                                    'pc' => 'Pc',
                                ))
                                <select name="type" id="type" class="form-control">
                                    <option value="">Select Type</option>
                                    @foreach($types as $key => $type)
                                        <option value="{{$key}}" @if($key == $product->type) selected="" @endif>{{$type}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="item form-group ">
                            <label class="col-form-label col-md-4 col-sm-4 label-align sp_update_title"
                                for="photo">Product Photo <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="file" id="photo" name="photo" class="dropify form-control">
                                @if ($product->photo)
                                    <img src="{{asset($product->photo)}}" alt="Photo" width="30px" height="40px">
                                @endif
                                <input type="hidden" id="old_photo" name="old_photo" value="{{$product->photo}}">
                            </div>
                        </div>

                    </div>
                    {{-- @dump($product) --}}
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align sp_update_title"
                                for="barcode">Barcode<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="barcode" name="barcode" value="{{$product->barcode}}"
                                    class="form-control">
                                <div class="mt-2 text-center">
                                    @if($product->barcode)
                                        <svg class="barcode-render" data-barcode="{{$product->barcode}}"></svg>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align sp_update_title"
                                for="category_id">Category<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">No Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}" @if($product->category_id == $category->id)
                                        selected="" @endif>{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align sp_update_title"
                                for="purchase_rate">Purchase Rate<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="number" step="0.01" id="purchase_rate" name="purchase_rate"
                                    value="{{$product->purchase_rate}}" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align sp_update_title"
                                for="mrp_rate">MRP Rate<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="number" step="0.01" id="mrp_rate" name="mrp_rate"
                                    value="{{$product->mrp_rate}}" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align sp_update_title"
                                for="price_rate">Price Rate<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="number" step="0.01" id="price_rate" name="price_rate"
                                    value="{{$product->price_rate}}" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align sp_update_title"
                                for="alert_quantity">Alert Quantity<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="number" id="alert_quantity" name="alert_quantity"
                                    value="{{$product->alert_quantity}}" class="form-control">
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align sp_update_title"
                                for="remarks">Remarks<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <textarea type="text" name="remarks" id="remarks" cols="10" rows="1"
                                    class="form-control">{{$product->remarks}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ln_solid"></div>
                <div class="item form-group">
                    <div class="col-md-12 col-sm-12 text-center">
                        <a href="{{route('product.index')}}" class="btn btn-danger" type="button">Cancel</a>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- image plugin js--}}
<script src="{{asset('assets/js/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/dropify.min.js')}}"></script>
<script type="text/javascript">

    $('.dropify').dropify({
        messages: {
            'default': 'Drag and drop',
            'remove': 'Remove',
        }
    });
</script>
@endsection