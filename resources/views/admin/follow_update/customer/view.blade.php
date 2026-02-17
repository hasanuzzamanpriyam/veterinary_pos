@extends('layouts.admin')

@section('page-title')
Customer Follow Update View
@endsection

@section('main-content')

<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="back_button mb-2">
                    <a href="{{route('customer.follow.index')}}" class="btn btn-md btn-primary float-right"> <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                </div>
                <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h2 class="customer_follow_update_info_title">Customer Follow Update Information : </h2>
                    <div class="customer_area py-4">
                        <div class="title text-justify">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-12 offset-1">
                                    <h4 class="x_title text-center">Customer Info</h4>

                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            @if($customer->customer->company_name != null)
                                                <tr><th class="text-left">Company Name </th><td class="text-left">{{$customer->customer->company_name}}</td></tr>
                                            @else
                                                <tr><th class="text-left">Name </th><td class="text-left">{{$customer->customer->name}}</td></tr>
                                            @endif
                                            <tr><th class="text-left">Mobile </th><td class="text-left">{{$customer->customer->mobile}}</td></tr>
                                            <tr><th class="text-left">Address </th><td class="text-left text-wrap">{{$customer->customer->address}}</td></tr>
                                            <tr><th class="text-left">Due Amount</th><td class="text-right">{{formatAmount($customer->previous_due)}}/=</td></tr>
                                        </tbody>
                                    </table>

                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 offset-1">
                                    <h4 class="x_title text-center">Follow Update Status</h4>
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            <tr><th class="text-left">Collect Tk</th><td class="text-right">{{formatAmount($customer->payment)}}/=</td></tr>
                                            <tr><th class="text-left">Prev Date</th><td class="text-right">{{date('d-m-Y', strtotime($customer->prev_date))}}</td></tr>

                                            <tr><th class="text-left">Collect Date </th><td class="text-right">{{date('d-m-Y', strtotime($customer->next_date))}}</td></tr>
                                            <tr><th class="text-left">Remarks </th><td class="text-left text-wrap">{{$customer->remarks}}</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
            </div>
        </div>
    </div>
</div>


@endsection
