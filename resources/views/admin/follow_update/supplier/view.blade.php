@extends('layouts.admin')

@section('page-title')
Supplier Following Date View
@endsection

@section('main-content')

<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="back_button mb-2">
                    <a href="{{route('supplier.follow.index')}}" class="btn btn-md btn-primary float-right"> <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                </div>
                <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h2 class="supplier_update_info_title">Supplier Follow Update Information :</h2>
                    <div class="customer_area py-4">
                        <div class="title text-justify">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-12 offset-1">
                                    <h4 class="x_title text-center">Supplier Info</h4>

                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            <tr><th class="text-left">Company Name</th><td class="text-left">{{$supplier->supplier->company_name}}</td></tr>
                                            <tr><th class="text-left">Mobile</th><td class="text-left">{{$supplier->supplier->mobile}}</td></tr>
                                            <tr><th class="text-left">Address</th><td class="text-left text-wrap">{{$supplier->supplier->address}}</td></tr>
                                            <tr><th class="text-left">Remarks</th><td class="text-left text-wrap">{{$supplier->remarks}}</td></tr>
                                        </tbody>
                                    </table>

                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 offset-1">
                                    <h4 class="x_title text-center">Follow Update Status</h4>
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            <tr><th class="text-left">Date</th><td class="text-right">{{date('d-m-Y', strtotime($supplier->prev_date))}}</td></tr>
                                            <tr><th class="text-left">Total Due</th><td class="text-right">{{formatAmount($supplier->previous_due)}}/=</td></tr>
                                            <tr><th class="text-left">Paid Amount</th><td class="text-right">{{formatAmount($supplier->payment)}}/=</td></tr>
                                            <tr><th class="text-left">Next Date</th><td class="text-right">{{date('d-m-Y', strtotime($supplier->next_date))}}</td></tr>
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
