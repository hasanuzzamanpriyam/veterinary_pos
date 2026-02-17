@extends('layouts.admin')

@section('page-title')
Category List
@endsection

@section('main-content')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">


            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto">Category List</h2>
                <a href="{{route('category.create')}}" class="btn btn-md btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Category</a>
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
                                <th class="all">Category Name</th>
                                <th class="all">Description</th>
                                <th class="all">Remarks</th>
                                <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$category->id}}</td>
                                        <td>{{$category->name}}</td>
                                        <td class="text-wrap">{{$category->description}}</td>
                                        <td class="text-wrap">{{$category->remarks}}</td>
                                        <td> <a href="{{route('category.edit',$category->id)}}" class="btn btn-success"><i class="fa fa-edit" ></i></a> <a href="{{route('category.delete',$category->id)}}" class="btn btn-danger" id="delete"><i class="fa fa-trash" ></i></a></td>
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
