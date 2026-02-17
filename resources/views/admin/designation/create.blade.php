@extends('layouts.admin')

@section('page-title')
Designation Add
@endsection

@section('main-content')
{{-- Image plugin css --}}
<link rel="stylesheet" href="{{asset('assets/css/dropify.min.css')}}" />


    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <div class="header-title d-flex align-items-center gap-2 p-3">
                    <h2>Add New Designation</h2>
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
                <form action="{{route('designation.store')}}" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left employee_add_form">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">

                            <div class="item form-group d-flex justify-content-center">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" for="name">Designation Title <span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <input type="text" id="name" name="designation_title"  class="form-control">
                                </div>
                            </div>


                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="item form-group d-flex justify-content-center">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" for="father_name">Description <span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <textarea type="text" name="designation_desc" id="designation_desc" cols="10" rows="1" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="ln_solid"></div>
                    <div class="item form-group">
                        <div class="col-md-6 col-sm-12  text-center">
                            <a href="{{route('designation.index')}}" class="btn btn-danger" type="button">Cancel</a>
                            <button class="btn btn-warning" type="reset">Reset</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- image plugin js--}}
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/dropify.min.js')}}"></script>
        <script type="text/javascript">

        $('.dropify').dropify({
                    messages: {
                        'default': 'Drag and drop',
                        'remove':  'Remove',
                    }
                });
        </script>

@endsection
