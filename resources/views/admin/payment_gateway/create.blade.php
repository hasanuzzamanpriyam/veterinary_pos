@extends('layouts.admin')

@section('page-title')
Payment Gateway Add
@endsection

@section('main-content')
{{-- Image plugin css --}}
<link rel="stylesheet" href="{{asset('assets/css/dropify.min.css')}}" />

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Add Payment Gateway</h2>
                <a href="{{route('payment-gateways.index')}}" class="mr-auto ml-3 cursor-pointer"><i class="fa fa-close"></i></a>
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
            <form action="{{route('payment-gateways.store')}}" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                @csrf
                <div class="row m-auto">
                    <div class="col-12">

                        <div class="item form-group">
                            <label class="col-form-label col-md-5 col-sm-5 label-align add_size_lebel" for="name">Gateway Name <span class=""></span>
                            </label>
                            <div class="col-md-7 col-sm-7">
                                <input type="text" id="name" name="name"  class="form-control">
                            </div>
                        </div>


                        <div class="item form-group">
                            <label class="col-form-label col-md-5 col-sm-5 label-align add_size_lebel" for="description">Description<span class=""></span>
                            </label>
                            <div class="col-md-7 col-sm-7">
                                <textarea type="text" name="description" id="description" cols="10" rows="1"  class="form-control"></textarea>
                            </div>
                        </div>


                        <div class="item form-group">
                            <label class="col-form-label col-md-5 col-sm-5 label-align add_size_lebel" for="remarks">Remarks<span class=""></span>
                            </label>
                            <div class="col-md-7 col-sm-7">
                                <textarea type="text" name="remarks" id="remarks" cols="10" rows="1"  class="form-control"></textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="ln_solid"></div>
                <div class="item form-group">
                    <div class="col-md-12 col-sm-12 text-center">
                        <a href="{{route('payment-gateways.index')}}" class="btn btn-danger" type="button">Cancel</a>
                        <button class="btn btn-warning" type="reset">Reset</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
