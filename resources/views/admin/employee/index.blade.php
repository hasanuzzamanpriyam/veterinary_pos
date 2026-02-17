@extends('layouts.admin')

@section('page-title')
Employee List
@endsection

@section('main-content')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto">Employee List</h2>
                <a href="{{route('employee.create')}}" class="btn btn-md btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Employee</a>
            </div>

        </div>
        <div class="x_content p-3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    <div class="card-box table-responsive">
                        {{-- notification message --}}
                        @if(session()->has('msg'))
                            <div class="text-center alert alert-success">
                                {{session()->get('msg')}}
                            </div>
                        @endif
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap category_list_table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <th class="all">S.N.</th>
                                <th class="all">ID</th>
                                <th class="all">Employee Name</th>
                                <th class="all">Father Name</th>
                                <th class="all">Address</th>
                                <th class="all">Mobile</th>
                                <th class="all">Joining Date</th>
                                <th class="all">Designation</th>
                                <th class="all">Salary Amount</th>
                                <th class="all">Bonus Amount</th>
                                <th class="all">Others Amount</th>
                                <th class="all">Security</th>
                                <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $employee)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$employee->id}}</td>
                                        <td>{{$employee->name}}</td>
                                        <td>{{$employee->father_name}}</td>
                                        <td>{{$employee->mobile}}</td>
                                        <td>{{$employee->address}}</td>
                                        <td>{{date('d-m-Y',strtotime($employee->joining_date))}}</td>
                                        <td>{{$employee->designation}}</td>
                                        @if(empty($employee->salary_amount))
                                        <td></td>
                                        @else
                                        <td class="text-right">{{$employee->salary_amount}}/-</td>
                                        @endif
                                        @if(empty($employee->bonus_amount))
                                        <td></td>
                                        @else
                                        <td class="text-right">{{$employee->bonus_amount}}/-</td>
                                        @endif
                                        @if(empty($employee->others_amount))
                                        <td></td>
                                        @else
                                        <td class="text-right">{{$employee->others_amount}}/-</td>
                                        @endif
                                        @if(empty($employee->security))
                                        <td></td>
                                        @else
                                        <td>{{$employee->security}}</td>
                                        @endif
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                                Action <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li> <a href="{{route('employee.edit',$employee->id)}}" class="btn btn-success"><i class="fa fa-edit" ></i></a></li>
                                                    <li><a href="{{route('employee.delete',$employee->id)}}" class="btn btn-danger" id="delete"><i class="fa fa-trash" ></i></a></i></a></li>
                                                    <li><a href="{{route('employee.view',$employee->id)}}" class="btn btn-info"><i class="fa fa-eye" ></i></a></li>
                                                    <li><a href="{{route('employee.ledger',$employee->id)}}" class="btn btn-warning"><i class="fa fa-list" ></i></a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
