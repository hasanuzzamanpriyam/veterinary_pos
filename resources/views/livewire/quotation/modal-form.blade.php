{{-- insert modal --}}
<div wire:ignore.self class="modal fade add-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                {{-- <h4 class="modal-title" id="myModalLabel">Brand Add</h4> --}}
                <button type="button" class="close" wire:click="closeModal" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2 class="add_quotation_title"> Add Quotation</h2>
                                {{-- <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a class="dropdown-item" href="#">Settings 1</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#">Settings 2</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div> --}}
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
                                <form wire:submit.prevent="storeQuotation()"  enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left quoation_from">
                                    @csrf
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="row d-flex justify-content-center">
                                            <div class="collection-form-area">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="form-group ">
                                                        <label class="" for="date">Date:</label>
                                                            {{-- <input id="date" name="date" wire:model="date" class="date-picker form-control" placeholder="dd-mm-yyyy" type="text"  type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)">
                                                            <script>
                                                                function timeFunctionLong(input) {
                                                                    setTimeout(function() {
                                                                        input.type = 'text';
                                                                    }, 60000);
                                                                }
                                                            </script> --}}


                                                            <div class="input-group date" id="datepicker33">
                                                                <input name="date" type="text" class="form-control" wire:model="date" placeholder="dd-mm-yyyy">
                                                                <div class="input-group-addon">
                                                                    <span class="glyphicon glyphicon-th"></span>
                                                                </div>
                                                            </div>
                                                    </div>

                                                    <label for="product_search">Product Name:</label>
                                                    <div class="form-group" wire:ignore>
                                                        <select type="search"  name="product_search" id="quotation-product-add"   placeholder="search" class="form-control">
                                                            <option value=""></option>
                                                            @foreach ($products as $product)
                                                                <option value="{{$product->id}}">
                                                                    {{$product->name}} -
                                                                    {{$product->brand->name}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>


                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <label for="customer_search">Customer Name:</label>
                                                    <div class="form-group" wire:ignore>
                                                        <select type="search"  name="customer_search" id="quotation-customer-add"   placeholder="search" class="form-control">
                                                            <option value=""></option>
                                                            @foreach ($customers as $customer)
                                                                <option value="{{$customer->id}}">
                                                                    {{$customer->name}} -
                                                                    {{$customer->address}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="brand_name">Brand:</label>
                                                        <input type="text"  name="brand_name"  wire:model="brand_name" class="form-control">
                                                    </div>

                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="supplier_name">Address:</label>
                                                        <textarea type="text"  name="address"  wire:model="address" class="form-control" cols="5" rows="1"></textarea>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="group_name">Group:</label>
                                                        <input type="text"  name="group_name"  wire:model="group_name" class="form-control">
                                                    </div>

                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="supplier_name">Mobile:</label>
                                                        <input type="text"  name="mobile"  wire:model="mobile" class="form-control">
                                                    </div>

                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12">

                                                    <div class="form-group">
                                                        <label for="product_quantity">Quantity:</label>
                                                        <input type="text"  name="product_quantity"  wire:model="product_quantity" class="form-control">
                                                    </div>

                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12">

                                                    <div class="form-group">
                                                        <label  for="prepare">Remarks:</label>
                                                        <textarea type="text" name="remarks" wire:model="remarks" id="remarks" class="form-control" cols="5" rows="1"></textarea>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                        <div class="ln_solid"></div>
                                        <div class="item form-group">
                                            <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                                                <a href="{{route('quotation.create')}}" class="btn btn-danger" type="button">Cancel</a>
                                                <button class="btn btn-warning" type="reset">Reset</button>
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- update modal --}}
<div wire:ignore.self class="modal fade update-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                {{-- <h4 class="modal-title" id="myModalLabel">Brand Add</h4> --}}
                <button type="button" class="close" wire:click="closeModal" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 ">
                        <div class="x_panel">
                            <div class="x_title p-3">
                                <h2 class="update_quoation">Update Quotation :</h2>
                                {{-- <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a class="dropdown-item" href="#">Settings 1</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#">Settings 2</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div> --}}
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
                                <form wire:submit.prevent="updateQuotation()"  enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                                    @csrf
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="row d-flex justify-content-center">
                                            <div class="collection-form-area">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="form-group ">
                                                        <label class="" for="date">Date:</label>
                                                            <input id="date" name="date" wire:model="date" class="date-picker form-control" placeholder="dd-mm-yyyy" type="text"  type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)">
                                                            <script>
                                                                function timeFunctionLong(input) {
                                                                    setTimeout(function() {
                                                                        input.type = 'text';
                                                                    }, 60000);
                                                                }
                                                            </script>
                                                    </div>
                                                    <div class="product-dropdown-area">
                                                        <label  for="product_name">Product Name:</label>

                                                        <div class="form-group">
                                                            <select type="search" name="product_search" id="quotation-product-edit"   placeholder="search" class="form-control">
                                                                @foreach ($products as $product)
                                                                    <option value="{{$product->id}}" @if($product_id == $product->id) selected @endif>
                                                                        {{$product->name}} |
                                                                        {{$product->brand->name}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>



                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="customer-dropdown-area">
                                                        <label for="customer_search">Customer Name:</label>
                                                        <div class="form-group">
                                                            <select type="search" name="customer_search" id="quotation-customer-edit" class="form-control">
                                                                @foreach ($customers as $customer)
                                                                    <option value="{{$customer->id}}" @if($customer->id == $customer_id) selected  @endif>
                                                                        {{$customer->name}} |
                                                                        {{$customer->address}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>


                                                    <div class="form-group">
                                                        <label for="brand_name">Brand</label>
                                                        <input type="text"  name="brand_name"  wire:model="brand_name" class="form-control">
                                                    </div>

                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="supplier_name">Address:</label>
                                                        <textarea type="text"  name="address"  wire:model="address" class="form-control" cols="5" rows="1"></textarea>

                                                    </div>

                                                    <div class="form-group">
                                                        <label for="group_name">Group</label>
                                                        <input type="text"  name="group_name"  wire:model="group_name" class="form-control">
                                                    </div>

                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="supplier_name">Mobile:</label>
                                                        <input type="text"  name="mobile"  wire:model="mobile" class="form-control">
                                                    </div>

                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12">

                                                    <div class="form-group">
                                                        <label for="product_quantity">Quantity</label>
                                                        <input type="text"  name="product_quantity"  wire:model="product_quantity" class="form-control">
                                                    </div>

                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12">

                                                    <div class="form-group">
                                                        <label  for="prepare">Remarks:</label>
                                                        <textarea type="text" name="remarks" wire:model="remarks" id="remarks" class="form-control" cols="5" rows="1"></textarea>
                                                    </div>

                                                </div>


                                            </div>
                                        </div>
                                        <div class="ln_solid"></div>
                                        <div class="item form-group">
                                            <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                                                <a href="{{route('quotation.create')}}" class="btn btn-danger" type="button">Cancel</a>
                                                <button class="btn btn-warning" type="reset" >Reset</button>
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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


            $(document).ready(function () {

                $('#quotation-customer-add').select2({
                    placeholder: 'Select Customer from here',
                });
                $('#quotation-customer-add').on('change', function (e) {
                        @this.searchCustomer(e.target.value);
                });

                $('#quotation-product-add').select2({
                    placeholder: 'Select Product from here',
                });
                $('#quotation-product-add').on('change', function (e) {
                        @this.searchProduct(e.target.value);
                });

                $('#quotation-customer-edit').select2({

                placeholder: 'Select Customer from here',
                });

                $('#quotation-customer-edit').on('change', function (e) {
                @this.searchCustomer(e.target.value);
                });

                $('#quotation-product-edit').select2({
                placeholder: 'Select Product from here',
                });
                $('#quotation-product-edit').on('change', function (e) {
                    @this.searchProduct(e.target.value);
                });

                $("#quotation-product-add").select2({
                    placeholder: 'Select Product from here',
                    width: '100%'
                });

                $("#quotation-customer-add").select2({
                    placeholder: 'Select Customer from here',
                    width: '100%'
                });

                // datepicker section

                $('#datepicker33').datepicker({
                    format: 'dd-mm-yyyy',
                    autoclose: true,
                    todayHighlight: true
                });

            // $("#datepicker33 input[name=date]").on('change', function (e) {
            //     @this.set('date', e.target.value);
            // });

            });
    </script>

@endpush
