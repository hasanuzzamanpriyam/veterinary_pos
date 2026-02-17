@extends('layouts.admin')

@section('page-title')
Sub Category Update
@endsection

@section('main-content')
{{-- Image plugin css --}}
<link rel="stylesheet" href="{{asset('assets/css/dropify.min.css')}}" />
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Update Sub Category</h2>
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
            <form action="{{route('subcategory.update')}}" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                @csrf
                <div class="row m-auto">
                    <div class="col-12">

                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="category_id">Category Name <span class=""></span>
                            </label>
                            <div class="col-md-6 col-sm-6">
                               <select name="category_id" id="category_id" class="form-control">
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}" @if($category->id == $subcategory->category_id) selected="" @endif>{{$category->name}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="name">Sub Category Name <span class=""></span>
                            </label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="name" id="name" value="{{$subcategory->name}}" class="form-control">
                            </div>
                            <input type="hidden" name="id" id="id" value="{{$subcategory->id}}">
                        </div>


                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="description">Description<span class=""></span>
                            </label>
                            <div class="col-md-6 col-sm-6">
                                <textarea type="text" name="description" id="description" cols="10" rows="1"  class="form-control">{{$subcategory->description}}</textarea>
                            </div>
                        </div>


                        <div class="item form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="remarks">Remarks<span class=""></span>
                            </label>
                            <div class="col-md-6 col-sm-6">
                                <textarea type="text" name="remarks" id="remarks" cols="10" rows="1"  class="form-control">{{$subcategory->remarks}}</textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="ln_solid"></div>
                <div class="item form-group">
                    <div class="col-md-12 col-sm-12 text-center">
                        <a href="{{route('subcategory.index')}}" class="btn btn-danger" type="button">Cancel</a>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
