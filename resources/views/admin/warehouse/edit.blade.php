@extends('layouts.admin')

@section('page-title')
Warehouse Update
@endsection

@section('main-content')
{{-- Image plugin css --}}
<link rel="stylesheet" href="{{asset('assets/css/dropify.min.css')}}" />

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2>Update Warehouse</h2>
                <a href="#" class="mr-auto ml-3 cursor-pointer" onclick="history.back()"><i class="fa fa-close"></i></a>
            </div>
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
            <form action="{{route('warehouse.update')}}" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                @csrf
                <div class="row m-auto">
                    <div class="col-12">

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="name">Warehouse Name <span class=""></span>
                            </label>
                            <div class="col-md-4 col-sm-6">
                                <input type="text" id="name" name="name" value="{{$warehouse->name}}" class="form-control">
                            </div>
                            <input type="hidden" name="id" id="id" value="{{$warehouse->id}}">
                            <input type="hidden" name="status" id="status" value="{{$warehouse->status}}">
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="address">Address <span class=""></span>
                            </label>
                            <div class="col-md-4 col-sm-6">
                                <textarea type="text" name="address" id="address" cols="10" rows="1"  class="form-control">{{$warehouse->address}}</textarea>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="mobile">Mobile<span class=""></span>
                            </label>
                            <div class="col-md-4 col-sm-6">
                                <input type="text" id="mobile" name="mobile"  value="{{$warehouse->mobile}}" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="phone">Phone<span class=""></span>
                            </label>
                            <div class="col-md-4 col-sm-6">
                                <input type="text" id="phone" name="phone"  value="{{$warehouse->phone}}" class="form-control">
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="name">Email <span class=""></span>
                            </label>
                            <div class="col-md-4 col-sm-6">
                                <input type="email" id="email" name="email"  value="{{$warehouse->email}}" class="form-control">
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="description">Description<span class=""></span>
                            </label>
                            <div class="col-md-4 col-sm-6">
                                <textarea type="text" name="description" id="description" cols="10" rows="1"  class="form-control">{{$warehouse->description}}</textarea>
                            </div>
                        </div>


                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="remarks">Remarks<span class=""></span>
                            </label>
                            <div class="col-md-4 col-sm-6">
                                <textarea type="text" name="remarks" id="remarks" cols="10" rows="1"  class="form-control">{{$warehouse->remarks}}</textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="ln_solid"></div>
                <div class="item form-group">
                    <div class="col-md-12 col-sm-12 text-center">
                        <a href="{{route('warehouse.index')}}" class="btn btn-danger" type="button">Cancel</a>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
