@extends('layouts.admin')

@section('page-title')
Store List
@endsection

@section('main-content')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto">Store List</h2>
                <a href="{{route('store.create')}}" class="btn btn-md btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Store</a>
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
                                    <th class="all">S.N.</th>
                                    <th class="all">ID</th>
                                    <th class="all">Store Name</th>
                                    <th class="all">Address</th>
                                    <th class="all">Mobile</th>
                                    <th class="all">Phone</th>
                                    <th class="all">Email</th>
                                    <th class="all">Description</th>
                                    <th class="all">Remarks</th>
                                    <th class="all">Status</th>
                                    <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stores as $store)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$store->id}}</td>
                                        <td>{{$store->name}}</td>
                                        <td>{{$store->address}}</td>
                                        <td>{{$store->mobile}}</td>
                                        <td>{{$store->phone}}</td>
                                        <td>{{$store->email}}</td>
                                        <td class="text-wrap">{{$store->description}}</td>
                                        <td class="text-wrap">{{$store->remarks}}</td>
                                        <td class="text-center">{!!$store->status == 1 ? '<a href="'.route('store.status',[$store->id,$store->status]).'"><i class="fa fa-check-circle text-success" style="font-size:24px"></i></a>  ':'<a href="'.route('store.status',[$store->id,$store->status]).'"><i class="fa fa-times-circle text-danger" style="font-size:24px"></i></a> ' !!}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                                Action <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                  <li><a href="{{route('store.edit',$store->id)}}" class="btn btn-success"><i class="fa fa-edit" ></i></a> </li>
                                                  <li><a href="{{route('store.delete',$store->id)}}" class="btn btn-danger" id="delete"><i class="fa fa-trash" ></i></a></li>
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
@endsection
