@extends('layouts.admin')

@section('page-title')
    Product Type List
@endsection

@section('main-content')

    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title p-3">
                <div class="header-title d-flex align-items-center gap-2">
                    <h2 class="mr-auto">Product Type List</h2>
                    <a href="{{route('product_type.create')}}" class="btn btn-md btn-primary"><i class="fa fa-plus"
                            aria-hidden="true"></i> Add Product Type</a>
                </div>
            </div>
            <div class="x_content p-3">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 ">
                        <div class="card-box table-responsive">
                            @if(session()->has('success'))
                                <div class="text-center alert alert-success">
                                    {{session()->get('success')}}
                                </div>
                            @endif
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="all">S.N.</th>
                                        <th class="all">ID</th>
                                        <th class="all">Product Type Name</th>
                                        <th class="all">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product_types as $product_type)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$product_type->id}}</td>
                                            <td>{{$product_type->name}}</td>
                                            <td>
                                                <a href="{{route('product_type.edit', $product_type->id)}}"
                                                    class="btn btn-success"><i class="fa fa-edit"></i></a>
                                                <a href="{{route('product_type.delete', $product_type->id)}}"
                                                    class="btn btn-danger" id="delete"><i class="fa fa-trash"></i></a>
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
@endsection