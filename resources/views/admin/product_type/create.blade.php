@extends('layouts.admin')

@section('page-title')
    Product Type Add
@endsection

@section('main-content')

    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title p-3">
                <div class="header-title d-flex align-items-center gap-2">
                    <h2>Add New Product Type</h2>
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
                <form action="{{route('product_type.store')}}" method="post" id="demo-form2" data-parsley-validate
                    class="form-horizontal form-label-left">
                    @csrf
                    <div class="row m-auto">
                        <div class="col-lg-12 col-md-12 col-sm-12">

                            <div class="item form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align add_category_lebel"
                                    for="name">Product Type Name <span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <input type="text" id="name" name="name" class="form-control" required>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="item form-group">
                        <div class="col-md-12 col-sm-12 text-center">
                            <a href="{{route('product_type.index')}}" class="btn btn-danger" type="button">Cancel</a>
                            <button class="btn btn-warning" type="reset">Reset</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection