@section('page-title', 'Product Create')
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Add Product</h2>
                <a href="{{route('product.index')}}" class="mr-auto ml-3 cursor-pointer"><i class="fa fa-close"></i></a>
            </div>
        </div>
        <div class="x_content p-3">
            <br />
            @if(session()->has('msg'))
                <div class="text-center alert alert-danger">
                    {{session()->get('msg')}}
                </div>
            @endif

            <form wire:submit.prevent="sessionCreate()" enctype="multipart/form-data" id="demo-form2"
                data-parsley-validate class="form-horizontal form-label-left">

                @csrf
                <div class="row m-auto">
                    <div class="col-lg-6 col-md-6 col-sm-12">

                        <div class="item mb-2" x-data="{
        barcodeText: @entangle('barcode'),
        hasBarcode: false,
        updateBarcode() {
            $nextTick(() => {
                if (this.barcodeText) {
                    try {
                        JsBarcode('#barcode_image', this.barcodeText, {
                            format: 'CODE128',
                            width: 1.5,
                            height: 40,
                            displayValue: true,
                            fontSize: 14
                        });
                        this.hasBarcode = true;
                    } catch (e) {
                        this.hasBarcode = false;
                        document.getElementById('barcode_image').innerHTML = '';
                    }
                } else {
                    this.hasBarcode = false;
                    document.getElementById('barcode_image').innerHTML = '';
                }
            });
        }
    }" x-init="$watch('barcodeText', value => updateBarcode()); updateBarcode()" x-cloak>
                            <div class="d-flex align-items-start col-md-4 p-0">
                                <label class="col-form-label add_product_lebel px-3 py-2 w-100" for="barcode">
                                    Barcode
                                </label>
                            </div>

                            <div class="col-md-8 col-sm-8">
                                <div class="d-flex justify-content-center align-items-start flex-column">

                                    <div class="w-100">
                                        <input type="text" id="barcode" name="barcode"
                                            wire:model.debounce.300ms="barcode" class="form-control"
                                            placeholder="Enter barcode number">
                                    </div>

                                    <!-- Barcode SVG (Only show when generated) -->
                                    <div class="mt-2 text-center w-100" x-show="hasBarcode" x-transition wire:ignore>
                                        <svg id="barcode_image"></svg>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="item mb-2">
                            <div class="d-flex align-items-start col-md-4 p-0">
                                <label class="col-form-label add_product_lebel px-2 py-2 w-100" for="name">Product Name
                                    <span class=""></span></label>
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <div class="d-flex justify-content-center align-items-start flex-column">
                                    <div class="w-100">
                                        <input type="text" id="name" name="name" wire:model="name" class="form-control">
                                    </div>
                                    @error('name')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="item mb-2">
                            <div class="d-flex align-items-start col-md-4 p-0">
                                <label class="col-form-label add_product_lebel px-2 py-2 w-100" for="brand">Company<span
                                        class=""></span></label>
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <div class="d-flex justify-content-center align-items-start flex-column">
                                    <div class="w-100" wire:ignore>
                                        <select name="brand_id" id="brand_id" wire:model="brand_id"
                                            class="form-control">
                                            <option value="">Select Option</option>
                                            @foreach($brands as $brand)
                                                <option value="{{$brand->id}}">{{$brand->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('brand_id')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="item mb-2">
                            <div class="d-flex align-items-start col-md-4 p-0">
                                <label class="col-form-label add_product_lebel px-2 py-2 w-100"
                                    for="category_id">Category <span class=""></span></label>
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <div class="d-flex justify-content-center align-items-start flex-column">
                                    <div class="w-100" wire:ignore>
                                        <select name="category_id" id="category_id" wire:model="category_id"
                                            class="form-control">
                                            <option value="">No Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="item mb-2">
                            <div class="d-flex align-items-start col-md-4 p-0">
                                <label class="col-form-label add_product_lebel px-2 py-2 w-100" for="group_id">Product
                                    Group <span class=""></span></label>
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <div class="d-flex justify-content-center align-items-start flex-column">
                                    <div class="w-100" wire:ignore>
                                        <select name="group_id" id="group_id" wire:model="group_id"
                                            class="form-control">
                                            <option value="">Select Option</option>
                                            @foreach($product_groups as $product_group)
                                                <option value="{{$product_group->id}}">{{$product_group->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('group_id')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="item mb-2">
                            <div class="d-flex align-items-start col-md-4 p-0">
                                <label class="col-form-label add_product_lebel px-2 py-2 w-100" for="size">Size<span
                                        class=""></span></label>
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <div class="d-flex justify-content-center align-items-start flex-column">
                                    <div class="w-100" wire:ignore>
                                        <select name="size_id" id="size_id" wire:model="size_id" class="form-control">
                                            <option value="">Select Option</option>
                                            @foreach($sizes as $size)
                                                <option value="{{$size->id}}">{{$size->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('size_id')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="item mb-2">
                            <div class="d-flex align-items-start col-md-4 p-0">
                                <label class="col-form-label add_product_lebel px-2 py-2 w-100"
                                    for="product_type_id">Product Mode <span class=""></span></label>
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <div class="d-flex justify-content-center align-items-start flex-column">
                                    <div class="w-100" wire:ignore>
                                        <select name="product_type_id" id="product_type_id" wire:model="product_type_id"
                                            class="form-control">
                                            <option value="">Select Product Mode</option>
                                            @foreach($product_types as $ptype)
                                                <option value="{{$ptype->id}}">{{$ptype->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('product_type_id')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
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
                                            @foreach($all_products as $product)
                                                <div class="form-check"
                                                    x-show="search === '' || '{{ strtolower(addslashes($product->name)) }}'.includes(search.toLowerCase()) || '{{ strtolower(addslashes($product->code ?? '')) }}'.includes(search.toLowerCase())">
                                                    <input class="form-check-input" type="checkbox"
                                                        wire:model="alternative_product_ids" value="{{ $product->id }}"
                                                        id="alt_product_{{ $product->id }}">
                                                    <label class="form-check-label" for="alt_product_{{ $product->id }}">
                                                        {{ $product->name }} @if($product->code) ({{ $product->code }})
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
                                    @error('alternative_product_ids')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">


                        <div class="item">
                            <div class="d-flex align-items-start col-md-4 p-0">
                                <label class="col-form-label add_product_lebel px-2 py-2" for="photo">Product Photo
                                    <span class=""></span></label>
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <div class="d-flex justify-content-center align-items-start flex-column">
                                    <div>
                                        <img src="{{$photo ? $photo->temporaryUrl() : asset('assets/images/no-image.png')}}"
                                            width="75" height="75" class="wow fadeInLeft" alt="Photo" />
                                    </div>
                                    <div>
                                        <input type="file" id="photo" name="photo" wire:model="photo" class="">
                                    </div>
                                    @error('photo')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="item mb-2">
                            <div class="d-flex align-items-start col-md-4 p-0">
                                <label class="col-form-label add_product_lebel px-2 py-2 w-100" for="sku">SKU <span
                                        class=""></span></label>
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <div class="d-flex justify-content-center align-items-start flex-column">
                                    <div class="w-100">
                                        <input type="text" id="sku" name="sku" wire:model="sku" class="form-control">
                                    </div>
                                    @error('sku')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="item mb-2">
                            <div class="d-flex align-items-start col-md-4 p-0">
                                <label class="col-form-label add_product_lebel px-2 py-2 w-100"
                                    for="alert_quantity">Alert Quantity <span class=""></span></label>
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <div class="d-flex justify-content-center align-items-start flex-column">
                                    <div class="w-100">
                                        <input type="number" id="alert_quantity" name="alert_quantity"
                                            wire:model="alert_quantity" class="form-control">
                                    </div>
                                    @error('alert_quantity')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="item mb-2">
                            <div class="d-flex align-items-start col-md-4 p-0">
                                <label class="col-form-label add_product_lebel px-2 py-2 w-100" for="purches_rate">TP
                                    Rate <span class=""></span></label>
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <div class="d-flex justify-content-center align-items-start flex-column">
                                    <div class="w-100">
                                        <input type="number" step="0.01" id="purchase_rate" name="purchase_rate"
                                            wire:model="purchase_rate" class="form-control">
                                    </div>
                                    @error('purchase_rate')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="item mb-2">
                            <div class="d-flex align-items-start col-md-4 p-0">
                                <label class="col-form-label add_product_lebel px-2 py-2 w-100" for="mrp_rate">MRP Rate
                                    <span class=""></span></label>
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <div class="d-flex justify-content-center align-items-start flex-column">
                                    <div class="w-100">
                                        <input type="number" step="0.01" id="mrp_rate" name="mrp_rate"
                                            wire:model="mrp_rate" class="form-control">
                                    </div>
                                    @error('mrp_rate')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="item mb-2">
                            <div class="d-flex align-items-start col-md-4 p-0">
                                <label class="col-form-label add_product_lebel px-2 py-2 w-100" for="price_rate">Sale
                                    Rate <span class=""></span></label>
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <div class="d-flex justify-content-center align-items-start flex-column">
                                    <div class="w-100">
                                        <input type="number" step="0.01" id="price_rate" name="price_rate"
                                            wire:model="price_rate" class="form-control">
                                    </div>
                                    @error('price_rate')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="item mb-2">
                            <div class="d-flex align-items-start col-md-4 p-0">
                                <label class="col-form-label add_product_lebel px-2 py-2 w-100" for="remarks">Remarks
                                    <span class=""></span></label>
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <div class="d-flex justify-content-center align-items-start flex-column">
                                    <div class="w-100">
                                        <textarea type="text" name="remarks" id="remarks" wire:model="remarks" cols="10"
                                            rows="1" class="form-control"></textarea>
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
                        <button class="btn btn-warning" wire:click="cancel()">Reset</button>
                        <button type="submit" class="btn btn-success">Checkout</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        var selects = [
            { id: '#brand_id', model: 'brand_id' },
            { id: '#category_id', model: 'category_id' },
            { id: '#group_id', model: 'group_id' },
            { id: '#size_id', model: 'size_id' },
            { id: '#product_type_id', model: 'product_type_id' }
        ];

        selects.forEach(function(item) {
            $(item.id).select2({
                width: '100%'
            }).on('change', function (e) {
                @this.set(item.model, e.target.value);
            });
        });
    });
</script>
@endpush