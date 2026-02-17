@extends('layouts.admin')

@section('page-title')
Product List
@endsection

@section('main-content')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2 ">
                <h2 class="mr-auto">Product Stock Report</h2>
                {{-- <a href="{{route('product.create')}}" class="btn btn-md btn-primary"> <i class="fa fa-plus" aria-hidden="true"></i> Add Product</a> --}}
            </div>

        </div>
        <div class="x_content p-3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    <div class="card-box table-responsive">
                        {{-- notification message --}}
                        @if(session()->has('msg'))
                            <div class="text-center alert alert-success">
                                {{session()->get('msg')}}
                            </div>
                        @endif
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap category_list_table" cellspacing="0" width="100%">
                            <thead>
                                <tr>

                                <th class="all">Code</th>
                                <th class="all">Name</th>
                                <th class="all">Brand</th>
                                <th class="all">Category</th>
                                <th class="all">Group</th>
                                <th class="all">Total Purchase Qty</th>
                                <th class="all">Total Sales Qty</th>


                                <th class="all">Stock Qty </th>
                                <th class="all">M/T</th>
                                <th class="all">Purchase Value</th>
                                <th class="all">Sales Value</th>
                                <th class="all">Present Value</th>
                                <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>

                                        <td>{{$product->code}}</td>
                                        <td>{{$product->name}}</td>
                                        @if(empty($product->brand_id))
                                            <td></td>
                                        @else
                                            <td>{{$product->brand->name}}</td>
                                        @endif
                                        @if(empty($product->category_id))
                                            <td></td>
                                        @else
                                            <td>{{$product->category->name}}</td>
                                        @endif
                                        @if(empty($product->group_id))
                                            <td></td>

                                        @else
                                            <td>{{$product->productGroup->name}}</td>
                                        @endif
                                        <td></td>
                                        <td></td>

                                            <td>{{$product->opening_stock }} Bags</td>
                                        @if(empty($product->size_id))
                                            <td></td>
                                        @else
                                            <td>{{$product->size->name*$product->opening_stock/1000}} {{$product->metric_ton}}</td>
                                        @endif
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>


                                        <td>
                                            <div class="btn-group btn-group-vertical customer_diplay_list">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                                    data-toggle="dropdown">
                                                    Action <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a href="#" class="btn btn-success"><i class="fa fa-edit" ></i>Edit</a></li>
                                                    <li><a href="#" class="btn btn-info"><i class="fa fa-eye" ></i> View</a></li>
                                                    <li> <a href="#" class="btn btn-danger" id="delete"><i class="fa fa-trash" ></i>Delete</a></li>

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

{{-- Single product view by modal --}}
 <!-- Large modal -->
 {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".product-view">Large modal</button> --}}

 <div class="modal fade product-view" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Product Info</h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Text in a modal</h4>
                    </div>
                    <div class="card-body">


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>
     </div>
</div>
@endsection
