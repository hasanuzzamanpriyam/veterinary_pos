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
                    <input type="hidden" name="id" value="{{$product->id}}">
                    <div class="row m-auto">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="item mb-2">
                                <div class="d-flex align-items-start col-md-4 p-0">
                                    <label class="col-form-label add_product_lebel px-3 py-2 w-100" for="barcode">
                                        Barcode
                                    </label>
                                </div>
                                <div class="col-md-8 col-sm-8">
                                    <div class="d-flex justify-content-center align-items-start flex-column">
                                        <div class="w-100">
                                            <input type="text" id="barcode" name="barcode" value="{{$product->barcode}}"
                                                class="form-control">
                                        </div>
                                        <div class="mt-2 text-center w-100">
                                            @if($product->barcode)
                                                <svg class="barcode-render" data-barcode="{{$product->barcode}}"></svg>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="item mb-2">
                                <div class="d-flex align-items-start col-md-4 p-0">
                                    <label class="col-form-label add_product_lebel px-2 py-2 w-100" for="name">Product Name
                                    </label>
                                </div>
                                <div class="col-md-8 col-sm-8">
                                    <div class="d-flex justify-content-center align-items-start flex-column">
                                        <div class="w-100">
                                            <input type="text" id="name" name="name" value="{{$product->name}}"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="item mb-2">
                                <div class="d-flex align-items-start col-md-4 p-0">
                                    <label class="col-form-label add_product_lebel px-2 py-2 w-100"
                                        for="brand_id">Company
                                    </label>
                                </div>
                                <div class="col-md-8 col-sm-8">
                                    <div class="d-flex justify-content-center align-items-start flex-column">
                                        <div class="w-100">
                                            <select name="brand_id" id="brand_id" class="form-control">
                                                <option value="">Select Option</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{$brand->id}}" @if($brand->id == $product->brand_id) selected=""
                                                    @endif>{{$brand->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="item mb-2">
                                <div class="d-flex align-items-start col-md-4 p-0">
                                    <label class="col-form-label add_product_lebel px-2 py-2 w-100"
                                        for="category_id">Category<span class=""></span>
                                    </label>
                                </div>
                                <div class="col-md-8 col-sm-8">
                                    <div class="d-flex justify-content-center align-items-start flex-column">
                                        <div class="w-100">
                                            <select name="category_id" id="category_id" class="form-control">
                                                <option value="">No Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{$category->id}}" @if($product->category_id == $category->id)
                                                    selected="" @endif>{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="item mb-2">
                                <div class="d-flex align-items-start col-md-4 p-0">
                                    <label class="col-form-label add_product_lebel px-2 py-2 w-100"
                                        for="group_id">Product Group<span class=""></span>
                                    </label>
                                </div>
                                <div class="col-md-8 col-sm-8">
                                    <div class="d-flex justify-content-center align-items-start flex-column">
                                        <div class="w-100">
                                            <select name="group_id" id="group_id" class="form-control">
                                                @foreach($product_groups as $product_group)
                                                    <option value="{{$product_group->id}}" @if($product->group_id == $product_group->id)
                                                    selected="" @endif>{{$product_group->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="item mb-2">
                                <div class="d-flex align-items-start col-md-4 p-0">
                                    <label class="col-form-label add_product_lebel px-2 py-2 w-100"
                                        for="size_id">Size<span class=""></span>
                                    </label>
                                </div>
                                <div class="col-md-8 col-sm-8">
                                    <div class="d-flex justify-content-center align-items-start flex-column">
                                        <div class="w-100">
                                            <select name="size_id" id="size_id" class="form-control">
                                                <option value="">Select Option</option>
                                                @foreach($sizes as $size)
                                                    <option value="{{$size->id}}" @if($size->id == $product->size_id) selected="" @endif>
                                                        {{$size->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="item mb-2">
                                <div class="d-flex align-items-start col-md-4 p-0">
                                    <label class="col-form-label add_product_lebel px-2 py-2 w-100"
                                        for="product_type_id">Product Type<span class=""></span>
                                    </label>
                                </div>
                                <div class="col-md-8 col-sm-8">
                                    <div class="d-flex justify-content-center align-items-start flex-column">
                                        <div class="w-100">
                                            <select name="product_type_id" id="product_type_id" class="form-control">
                                                <option value="">Select Product Type</option>
                                                @foreach($product_types as $ptype)
                                                    <option value="{{$ptype->id}}" @if($ptype->id == $product->product_type_id)
                                                    selected="" @endif>{{$ptype->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="item mb-2">
                                <div class="d-flex align-items-start col-md-4 p-0">
                                    <label class="col-form-label add_product_lebel px-2 py-2 w-100"
                                        for="alternative_product_ids">Alternative Products <span class=""></span></label>
                                </div>
                                <div class="col-md-8 col-sm-8">
                                    <div class="d-flex justify-content-center align-items-start flex-column">
                                        <div class="w-100" x-data="{ search: '' }">
                                            <input type="text" x-model="search" class="form-control mb-2"
                                                placeholder="Search alternative products...">

                                            <div class="border rounded p-2"
                                                style="max-height: 200px; overflow-y: auto; background: #fff;">
                                                @foreach($all_products as $alt_product)
                                                    <div class="form-check"
                                                        x-show="search === '' || '{{ strtolower(addslashes($alt_product->name)) }}'.includes(search.toLowerCase()) || '{{ strtolower(addslashes($alt_product->code ?? '')) }}'.includes(search.toLowerCase())">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="alternative_product_ids[]" value="{{ $alt_product->id }}"
                                                            id="alt_product_{{ $alt_product->id }}"
                                                            @if(in_array($alt_product->id, $existing_alternative_ids)) checked @endif>
                                                        <label class="form-check-label" for="alt_product_{{ $alt_product->id }}" style="background: transparent; color: inherit; font-size: inherit;">
                                                            {{ $alt_product->name }} @if($alt_product->code) ({{ $alt_product->code }})
                                                            @endif
                                                        </label>
                                                    </div>
                                                @endforeach
                                                @if(count($all_products) == 0)
                                                    <div class="text-muted text-center py-2"><small>No products
                                                            available</small></div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        {{-- @dump($product) --}}
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="item">
                                <div class="d-flex align-items-start col-md-4 p-0">
                                    <label class="col-form-label add_product_lebel px-2 py-2 w-100"
                                        for="photo">Product Photo <span class=""></span>
                                    </label>
                                </div>
                                <div class="col-md-8 col-sm-8">
                                    <div class="d-flex justify-content-center align-items-start flex-column">
                                        @if ($product->photo)
                                            <div class="mb-2">
                                                <img src="{{asset($product->photo)}}" alt="Photo"
                                                    style="width: 75px; height: 75px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd;">
                                            </div>
                                        @endif
                                        <input type="file" id="photo" name="photo" class="form-control">
                                        <input type="hidden" id="old_photo" name="old_photo" value="{{$product->photo}}">
                                    </div>
                                </div>
                            </div>

                            <div class="item mb-2">
                                <div class="d-flex align-items-start col-md-4 p-0">
                                    <label class="col-form-label add_product_lebel px-2 py-2 w-100"
                                        for="alert_quantity">Alert Quantity<span class=""></span>
                                    </label>
                                </div>
                                <div class="col-md-8 col-sm-8">
                                    <div class="d-flex justify-content-center align-items-start flex-column">
                                        <div class="w-100">
                                            <input type="number" id="alert_quantity" name="alert_quantity"
                                                value="{{$product->alert_quantity}}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="item mb-2">
                                <div class="d-flex align-items-start col-md-4 p-0">
                                    <label class="col-form-label add_product_lebel px-2 py-2 w-100"
                                        for="purchase_rate">TP Rate<span class=""></span>
                                    </label>
                                </div>
                                <div class="col-md-8 col-sm-8">
                                    <div class="d-flex justify-content-center align-items-start flex-column">
                                        <div class="w-100">
                                            <input type="number" step="0.01" id="purchase_rate" name="purchase_rate"
                                                value="{{$product->purchase_rate}}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="item mb-2">
                                <div class="d-flex align-items-start col-md-4 p-0">
                                    <label class="col-form-label add_product_lebel px-2 py-2 w-100"
                                        for="mrp_rate">MRP Rate<span class=""></span>
                                    </label>
                                </div>
                                <div class="col-md-8 col-sm-8">
                                    <div class="d-flex justify-content-center align-items-start flex-column">
                                        <div class="w-100">
                                            <input type="number" step="0.01" id="mrp_rate" name="mrp_rate"
                                                value="{{$product->mrp_rate}}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="item mb-2">
                                <div class="d-flex align-items-start col-md-4 p-0">
                                    <label class="col-form-label add_product_lebel px-2 py-2 w-100"
                                        for="price_rate">Sale Rate<span class=""></span>
                                    </label>
                                </div>
                                <div class="col-md-8 col-sm-8">
                                    <div class="d-flex justify-content-center align-items-start flex-column">
                                        <div class="w-100">
                                            <input type="number" step="0.01" id="price_rate" name="price_rate"
                                                value="{{$product->price_rate}}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="item mb-2">
                                <div class="d-flex align-items-start col-md-4 p-0">
                                    <label class="col-form-label add_product_lebel px-2 py-2 w-100"
                                        for="remarks">Remarks<span class=""></span>
                                    </label>
                                </div>
                                <div class="col-md-8 col-sm-8">
                                    <div class="d-flex justify-content-center align-items-start flex-column">
                                        <div class="w-100">
                                            <textarea type="text" name="remarks" id="remarks" cols="10" rows="1"
                                                class="form-control">{{$product->remarks}}</textarea>
                                        </div>
                                    </div>
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
    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#brand_id, #category_id, #group_id, #size_id, #product_type_id').select2({
                width: '100%'
            });
        });
    </script>
    @endpush
@endsection