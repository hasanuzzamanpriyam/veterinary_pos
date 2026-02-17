@extends('layouts.admin')

@section('page-title')
Designation Update
@endsection

@section('main-content')
{{-- Image plugin css --}}
<link rel="stylesheet" href="{{asset('assets/css/dropify.min.css')}}" />

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Update Designation</h2>
                <a href="#" class="mr-auto ml-3 cursor-pointer" onclick="history.back()"><i class="fa fa-close"></i></a>
            </div>

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
            <form action="{{route('designation.update')}}" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                @csrf
                <div class="row m-auto">

                    <div class="col-12">

                        <div class="item form-group d-flex justify-content-center">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="name">Designation Name <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="name" name="designation_title" value="{{$designation->designation_title}}" class="form-control">
                                <input type="hidden" name="id" id="id" value="{{$designation->id}}">
                            </div>
                        </div>

                        <div class="item form-group d-flex justify-content-center">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="father_name">Description <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <textarea type="text" name="designation_desc" id="designation_desc" cols="10" rows="1" class="form-control">{{$designation->designation_desc}}</textarea>
                            </div>
                        </div>


                    </div>


                </div>

                <div class="ln_solid"></div>
                <div class="item form-group">
                    <div class="col-md-12 col-sm-12 text-center">
                        <a href="{{route('designation.index')}}" class="btn btn-danger" type="button">Cancel</a>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
