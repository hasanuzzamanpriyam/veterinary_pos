@extends('layouts.admin')

@section('page-title')
Unit Update
@endsection

@section('main-content')
{{-- Image plugin css --}}
<link rel="stylesheet" href="{{asset('assets/css/dropify.min.css')}}" />
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2>Update Unit</h2>
                <a href="#" class="mr-auto ml-3 cursor-pointer" onclick="history.back()"><i class="fa fa-close"></i></a>
            </div>

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
            <form action="{{route('unit.update')}}" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                @csrf
                <div class="row m-auto">
                    <div class="col-12 ">

                        <div class="item form-group">
                            <label class="col-form-label col-md-5 col-sm-5 label-align " for="name">Unit Name <span class=""></span>
                            </label>
                            <div class="col-md-7 col-sm-7 ">
                                <input type="text" id="name" name="name" value="{{$unit->name}}" class="form-control">
                            </div>
                            <input type="hidden" name="id" id="id" value="{{$unit->id}}">
                        </div>


                        <div class="item form-group">
                            <label class="col-form-label col-md-5 col-sm-5 label-align " for="description">Description<span class=""></span>
                            </label>
                            <div class="col-md-7 col-sm-7 text-center">
                                <textarea type="text" name="description" id="description" cols="10" rows="1"  class="form-control">{{$unit->description}}</textarea>
                            </div>
                        </div>


                        <div class="item form-group">
                            <label class="col-form-label col-md-5 col-sm-5 label-align " for="remarks">Remarks<span class=""></span>
                            </label>
                            <div class="col-md-7 col-sm-7 ">
                                <textarea type="text" name="remarks" id="remarks" cols="10" rows="1"  class="form-control">{{$unit->remarks}}</textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="ln_solid"></div>
                <div class="item form-group">
                    <div class="col-md-12 col-sm-12 text-center">
                        <a href="{{route('unit.index')}}" class="btn btn-danger" type="button">Cancel</a>
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
