@extends('layouts.admin')

@section('page-title')
Employee View
@endsection

@section('main-content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="back_button mb-2">
                    <a href="{{route('employee.index')}}" class="btn btn-md btn-primary float-right employee_back_button"> <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 offset-lg-2 offset-md-3">

                        <div class="customer_area">
                            <div class="col-lg-8 col-md-6 col-sm-12">
                                <h2 class="text-center text-dark border">Employee Information</h2>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <div class="logo text-center mb-3">
                                                @if(empty($employee->photo))
                                                    <img src="{{asset('assets/images/user.png')}}" alt="Photo" width="100" height="100" class="rounded-circle">
                                                @else
                                                    <img src="{{asset($employee->photo)}}" alt="Photo" width="100" height="100" class="rounded-circle">
                                                @endif

                                                <h4 class="text-dark">{{$employee->name}}</h4>
                                                @if(!empty($employee->email))
                                                    <h6 class="text-dark">{{$employee->email}}</h6>
                                                @endif
                                            </div>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th class="text-center">Father Name</th>
                                            <th class="text-center">Address</th>
                                            <th class="text-center">NID Number</th>
                                            <th class="text-center">Date of Birth</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">{{$employee->father_name}}</td>
                                            <td class="text-center">{{$employee->address}}</td>
                                            <td class="text-center">{{$employee->nid}}</td>
                                            <td class="text-center">{{date('d-m-Y', strtotime($employee->birthday))}}</td>

                                        </tr>
                                    </tbody>
                                    <thead>
                                        <tr>
                                            <th class="text-center">Mobile</th>
                                            <th class="text-center">Joining Date</th>
                                            <th class="text-center">Designation</th>
                                            <th class="text-center">Security</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">{{$employee->mobile ?? ''}}</td>
                                            <td class="text-center">{{date('d-m-Y', strtotime($employee->joining_date))}}</td>
                                            <td class="text-center">{{$employee->designation ?? ''}}</td>
                                            <td class="text-center">{{$employee->security ?? ''}}</td>

                                        </tr>
                                    </tbody>
                                    <thead>
                                        <tr>
                                            <th class="text-center">Salary Amount</th>
                                            <th class="text-center">Bonus/Others Amount</th>
                                            <th class="text-center">Total Tk</th>
                                            <th class="text-center">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">{{$employee->salary_amount ? formatAmount($employee->salary_amount) . '/= ' : ''}}</td>
                                            <td class="text-center">{{$employee->bonus_amount ? formatAmount($employee->bonus_amount) . '/= ' : ''}}</td>
                                            <td class="text-center">
                                                @php
                                                    $total = $employee->salary_amount + $employee->bonus_amount;
                                                    echo formatAmount($total) . '/=';
                                                @endphp
                                            </td>
                                            <td class="text-center">{{$employee->remarks}}</td>
                                        </tr>
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

@endsection
