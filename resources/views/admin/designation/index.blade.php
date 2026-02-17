@extends('layouts.admin')

@section('page-title')
Designation List
@endsection

@section('main-content')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto">Designation List</h2>
                <a href="{{route('designation.create')}}" class="btn btn-md btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Designation</a>
            </div>

        </div>
        <div class="x_content p-3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    <div class="card-box table-responsive">
                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">ID</th>
                                    <th class="all">Designation Title</th>
                                    <th class="all">Designation Description</th>
                                    <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($designations as $designation)
                                    <tr>
                                        <td>{{$designation->id}}</td>
                                        <td>{{$designation->designation_title}}</td>
                                        <td>{{$designation->designation_desc}}</td>

                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                    data-toggle="dropdown">
                                                    <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li> <a href="{{ route('designation.edit',$designation->id)}}" class="btn btn-success"><i class="fa fa-edit" ></i></a></li>
                                                    <li><a href="{{ route('designation.delete',$designation->id)}}" class="btn btn-danger" id="delete"><i class="fa fa-trash" ></i></a></i></a></li>
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
