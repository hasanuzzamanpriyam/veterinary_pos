
@section('page-title', 'Employee List')

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
                    {{cute_loader()}}
                    <div class="card-box table-responsive">

                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <th class="all">SL</th>
                                <th class="all">Name</th>
                                <th class="all">Address</th>
                                <th class="all">Mobile</th>
                                <th class="all">Designation</th>
                                <th class="all">Salary Tk</th>
                                <th class="all">Withdraw Tk</th>
                                <th class="all">Balance Tk</th>
                                <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $summary = [];
                                @endphp
                                @foreach($employees as $employee)
                                    @php
                                        $summary['salary'] = $summary['salary'] ?? 0;
                                        $summary['payment'] = $summary['payment'] ?? 0;
                                        $summary['balance'] = $summary['balance'] ?? 0;
                                        $salary = $transactions->where('employee_id', $employee->id)->sum('total_salary');
                                        $payment = $transactions->where('employee_id', $employee->id)->sum('total_payment');
                                        $summary['salary'] += $salary;
                                        $summary['payment'] += $payment;
                                        $summary['balance'] += $employee->balance;

                                        $currentPage = method_exists($employees, 'currentPage') ? $employees->currentPage() : 1;
                                        $perPage = method_exists($employees, 'perPage') ? $employees->perPage() : $employees->count(); // Fallback to total count
                                        $iteration = ($currentPage - 1) * $perPage + $loop->iteration;
                                    @endphp
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td class="text-left">{{$employee->name}}</td>
                                        <td class="text-left">{{$employee->address}}</td>
                                        <td>{{$employee->mobile}}</td>
                                        <td>{{$employee->designation}}</td>
                                        <td class="text-right">{{$salary ? formatAmount($salary) . '/=' : '-'}}</td>
                                        <td class="text-right">{{$payment ? formatAmount($payment) . '/=' : '-'}}</td>
                                        <td class="text-right">{{$employee->balance ? formatAmount($employee->balance) . '/=' : '-'}}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                    data-toggle="dropdown">
                                                    <i class="fa fa-list"></i> <span class="caret"></span></button>
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
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">{{isset($summary['salary']) && $summary['salary'] ? formatAmount($summary['salary']) . '/=' : '-'}}</td>
                                    <td class="text-right">{{isset($summary['payment']) && $summary['payment'] ? formatAmount($summary['payment']) . '/=' : '-'}}</td>
                                    <td class="text-right">{{isset($summary['balance']) && $summary['balance'] ? formatAmount($summary['balance']) . '/=' : '-'}}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
