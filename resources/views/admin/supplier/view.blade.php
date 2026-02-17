@extends('layouts.admin')

@section('page-title')
Supplier View
@endsection

@section('main-content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="back_button mb-2">
                    <a href="{{route('supplier.index')}}" class="btn btn-md btn-primary float-right supplier_view_back_btn"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <h2 class="text-center text-dark">Supplier Information</h2>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <div class="logo text-center mb-3">
                                        @if(empty($supplier->photo))
                                            <img src="{{asset('assets/images/user.png')}}" alt="Photo" width="100" height="100" class="rounded-circle">
                                        @else
                                            <img src="{{asset($supplier->photo)}}" alt="Photo" width="100" height="100" class="rounded-circle">
                                        @endif

                                        <h4 class="text-dark">{{$supplier->company_name}}</h4>
                                        @if(!empty($supplier->email))
                                            <h6 class="text-dark">{{$supplier->email}}</h6>
                                        @endif

                                    </div>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th class="w-25 text-center">Company Name</th>
                                    <th class="w-25 text-center">Owner Name</th>
                                    <th class="w-25 text-center">Address</th>
                                    <th class="w-25 text-center">Mobile</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">{{$supplier->company_name}}</td>
                                    <td class="text-center">{{$supplier->owner_name}}</td>
                                    <td class="text-center">{{$supplier->address}}</td>
                                    <td class="text-center">{{$supplier->mobile}}</td>
                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th class="w-25 text-center">Officer Name</th>
                                    <th class="w-25 text-center">Officer Number</th>
                                    <th class="w-25 text-center">Dealer Code</th>
                                    <th class="w-25 text-center">Dealer Area</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">{{$supplier->officer_name}}</td>
                                    <td class="text-center">{{$supplier->phone}}</td>
                                    <td class="text-center">{{$supplier->dealer_code}}</td>
                                    <td class="text-center">{{$supplier->dealer_area}}</td>
                                </tr>
                            </tbody>

                            <thead>
                                <tr>
                                    <th class="w-25 text-center">Security</th>
                                    <th class="w-25 text-center">Credit Limit</th>
                                    <th class="w-25 text-center">Starting Date</th>
                                    <th class="w-25 text-center">Condition</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">{{$supplier->security}}</td>
                                    <td class="text-center">{{$supplier->credit_limit ? $supplier->credit_limit . '/=' : ''}}</td>
                                    <td class="text-center">{{date('d-m-Y', strtotime($supplier->starting_date))}}</td>
                                    <td class="text-center">{{$supplier->condition}}/=</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row my-5">
                            <div class="col-md-12">
                                <div class="inner text-center">
                                    <a href="{{route('supplier.edit', $supplier->id)}}" class="btn btn-success">Edit</a>
                                    <a href="" class="btn btn-warning">Print</a>
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
