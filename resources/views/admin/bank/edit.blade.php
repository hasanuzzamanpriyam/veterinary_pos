@extends('layouts.admin')

@section('page-title')
Bank Update
@endsection

@section('main-content')
{{-- Image plugin css --}}
<link rel="stylesheet" href="{{asset('assets/css/dropify.min.css')}}" />

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2> Update Bank</h2>
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
            <form action="{{route('bank.update')}}" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                @csrf
                <div class="row m-auto">
                    <div class="col-12">

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_bank_lebel" for="name">Bank Name <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="name" name="name" value="{{$bank->name}}" class="form-control" >
                            </div>
                            <input type="hidden" name="id" id="id" value="{{$bank->id}}">
                        </div>


                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_bank_lebel" for="branch">Branch Name<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="branch" name="branch"  value="{{$bank->branch}}" class="form-control" >
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_bank_lebel" for="account_no">Account No.<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="account_no" name="account_no"  value="{{$bank->account_no}}"class="form-control" >
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_bank_lebel" for="code">Routing No.<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="code" name="code"  value="{{$bank->code}}" class="form-control" >
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_bank_lebel" for="title">Bank Title <span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="title" name="title" value="{{$bank->title}}" class="form-control">

                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_bank_lebel" for="code">AC Mode<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" id="ac_mode" name="ac_mode"  value="{{ $bank->ac_mode }}" class="form-control" >
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align add_bank_lebel" for="remarks">Remarks<span class=""></span>
                            </label>
                            <div class="col-md-8 col-sm-8">
                                <textarea type="text" name="remarks" id="remarks" cols="10" rows="1"  class="form-control">{{$bank->remarks}}</textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="ln_solid"></div>
                <div class="item form-group">
                    <div class="col-md-12 col-sm-12 text-center">
                        <a href="{{route('bank.index')}}" class="btn btn-danger" type="button">Cancel</a>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
