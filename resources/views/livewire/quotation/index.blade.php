{{-- <div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Add Customer Quotation:</h2>
                <ul class="nav navbar-right panel_toolbox">
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
                <div class="clearfix"></div>
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
                <form wire:submit.prevent="storeQuotation()"  enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                    @csrf
                    <div class="col-lg-12 col-md-12 col-sm-12">

                        <div class="row">
                            <div class="collection-form-area">
                                <div class="col-lg-3 col-md-3 col-sm-12">
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



                                    <div class="form-group" wire:ignore>
                                        <label for="product_name">Product Name:</label>
                                        <select type="search"  name="product_search" id="quotation-product-search"   placeholder="search" class="form-control">
                                            <option value=""></option>
                                            @foreach ($products as $product)
                                                <option value="{{$product->id}}">
                                                    {{$product->name}} |
                                                    {{$product->brand->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12">

                                    <div class="form-group" wire:ignore>
                                        <label for="customer_search">Customer Name:</label>
                                        <select type="search"  name="customer_search" id="quotation-customer-search"   placeholder="search" class="form-control">
                                            <option value=""></option>
                                            @foreach ($customers as $customer)
                                                <option value="{{$customer->id}}">
                                                    {{$customer->name}} |
                                                    {{$customer->address}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>



                                    <div class="form-group">
                                        <label for="brand_name">Brand</label>
                                        <input type="text"  name="brand_name"  wire:model="brand_name" class="form-control">
                                    </div>

                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="supplier_name">Address:</label>
                                        <input type="text"  name="address"  wire:model="address" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="group_name">Group</label>
                                        <input type="text"  name="group_name"  wire:model="group_name" class="form-control">
                                    </div>

                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="supplier_name">Mobile:</label>
                                        <input type="text"  name="mobile"  wire:model="mobile" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="product_quantity">Quantity</label>
                                        <input type="text"  name="product_quantity"  wire:model="product_quantity" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label  for="prepare">Remarks:</label>
                                        <textarea type="text" name="remarks" wire:model="remarks" id="transport_no" class="form-control" cols="5" rows="2"></textarea>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="item form-group">
                            <div class="col-lg-12 col-md-12 col-sm-12 offset-3">
                                <a href="{{route('quotation.index')}}" class="btn btn-danger" type="button">Cancel</a>
                                <button class="btn btn-primary" type="reset">Reset</button>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        @include('livewire.quotation.modal-form')

        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto"> Quotation List</h2>
                <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target=".add-modal"><i class="fa fa-plus" aria-hidden="true"></i> Add Customer Quotation</button>
            </div>

                {{-- <h2 class="c_quoation_title"><button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#">Customer Quotation List:</button></h2>

                <ul class="nav navbar-right panel_toolbox">

                    <li>
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Settings 1</a>
                            <a class="dropdown-item" href="#">Settings 2</a>
                        </div>
                    </li>
                    <li>
                        <a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <h2 class="customer_quotation_btn"><button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target=".add-modal"><i class="fa fa-plus" aria-hidden="true"></i> Add Customer Quotation</button></h2>
            <div class="clearfix"></div> --}}
        </div>
        <div class="x_content p-3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    <div class="card-box table-responsive">
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <th class="all">SL No.</th>
                                <th class="all">Date</th>
                                <th class="all">Customer Name</th>
                                <th class="text-left">Address</th>
                                <th class="all">Mobile</th>
                                <th class="all">Product Name</th>
                                <th class="all">Brand</th>
                                <th class="all">Group</th>
                                <th class="all">Quantity</th>
                                <th class="all">Remarks</th>
                                <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quotations as $quotation)
                                    <tr>
                                        <td class="text-left">{{$loop->iteration}}</td>
                                        <td class="text-left">{{date('d-m-Y', strtotime($quotation->date))}}</td>
                                        <td class="text-left">{{$quotation->customer->name}}</td>
                                        <td class="text-left">{{$quotation->customer->address}}</td>
                                        <td class="text-left">{{$quotation->customer->mobile}}</td>
                                        <td class="text-left">{{$quotation->product->name}}</td>
                                        <td class="text-left">{{$quotation->brand->name}}</td>
                                        <td class="text-left">{{$quotation->productGroup->name}}</td>
                                        <td class="text-left">{{$quotation->quantity}}</td>
                                        <td class="text-left">{{$quotation->remarks}}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                                Action <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li> <a href="#" wire:click="edit('{{$quotation->id}}')" class="btn btn-success" data-toggle="modal" data-target=".update-modal"><i class="fa fa-edit" ></i></a></li>
                                                    <li> <button  wire:click="delete({{$quotation->id}})" class="btn btn-danger"><i class="fa fa-trash" ></i></button></li>
                                                    <li><a href="{{route('quotation.view',$quotation->id)}}" class="btn btn-info"><i class="fa fa-eye" ></i></a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- @push('scripts')
<script>

    $(document).ready(function () {

       $('#quotation-customer-search').select2({
        placeholder: 'Select Customer from here',
       });
       $('#quotation-customer-search').on('change', function (e) {
            @this.searchCustomer(e.target.value);
       });
    });

    $(document).ready(function () {
       $('#quotation-product-search').select2({
        placeholder: 'Select Product from here',
       });
       $('#quotation-product-search').on('change', function (e) {
            @this.searchProduct(e.target.value);
       });
    });
    </script>

@endpush --}}
