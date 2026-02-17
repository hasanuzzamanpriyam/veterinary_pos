@extends('layouts.admin')

@section('page-title')
Customer Gallery
@endsection

@section('main-content')
<div class="">
    <div class="page-title p-3">
        <div class="title_left">
            <h3 class="product_gallery_title"> Customer Gallery : </h3>
        </div>
        <div class="title_right">
            <div class="col-md-5 col-sm-5   form-group pull-right top_search">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search for...">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button">Go!</button>
                </span>
            </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="header-title d-flex align-items-center gap-2 p-3">
                        {{-- <h2>Customer Gallery</h2>
                        <a href="#" class="mr-auto ml-3 cursor-pointer" onclick="history.back()"><i class="fa fa-close"></i></a> --}}
                    </div>
                </div>
                <div class="x_content">
                    <div class="row">
                        @foreach($customers as $customer)
                            <div class="col-lg-2 col-md-55 col-sm-12">
                                <div class="thumbnail">
                                    <a href="{{route('customer.view',$customer->id)}}" target="_blank">
                                        <div class="image view view-first">
                                            @if(empty($customer->photo))
                                            <h5 my-auto>Opps No Image Found!</h5>
                                            @else
                                            <img style="width: 100%; display: block;" src="{{asset($customer->photo)}}" alt="image" />
                                            @endif
                                            <div class="mask">
                                                @if(empty($customer->id))
                                                @else
                                                    <p>{{$customer->name}}</p>
                                                @endif
                                                <div class="tools tools-bottom">
                                                    {{-- <a href="#"><i class="fa fa-link"></i></a>
                                                    <a href="#"><i class="fa fa-pencil"></i></a>
                                                    <a href="#"><i class="fa fa-times"></i></a> --}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="caption">
                                            <p>{{$customer->name}}</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
