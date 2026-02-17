@extends('layouts.admin')

@section('page-title')
Salary Expenses List
@endsection

@section('main-content')
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <div class="x_title p-3">
                <div class="header-title d-flex align-items-center gap-2">
                    <h2 class="mr-auto">Salary Expenses List</h2>
                    <a href="{{route('salary.expense.create')}}" class="btn btn-sm btn-primary" target="_blank"> <i class="fa fa-plus" aria-hidden="true"></i> Add Salary Expense</a>
                </div>
            </div>
        </div>
        <div class="x_content p-3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    <div class="card-box table-responsive">
                        <table id="expenseList" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">SL</th>
                                    <th class="all">Date</th>
                                    <th class="all">Voucher</th>
                                    <th class="all">Employee Name</th>
                                    <th class="all">Address</th>
                                    <th class="all">Mobile</th>
                                    <th class="all">Remarks</th>
                                    <th class="all">Salary Tk</th>
                                    <th class="all">Others Tk</th>
                                    <th class="all">Total Tk</th>
                                    <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                    $total_salary = 0;
                                    $total_others = 0;
                                @endphp
                                @foreach($salary_expenses as $expense)
                                    @php
                                    $total_salary += $expense->amount;
                                    $total_others += $expense->other_charge;
                                    $line_total = $expense->amount + $expense->other_charge;
                                    $total += $line_total;

                                    $currentPage = method_exists($salary_expenses, 'currentPage') ? $salary_expenses->currentPage() : 1;
                                    // $perPage = method_exists($salary_expenses, 'perPage') ? $salary_expenses->perPage() : $salary_expenses->count(); // Fallback to total count
                                    $iteration = ($currentPage - 1) * $perpage + $loop->iteration;
                                    @endphp
                                    <tr>
                                        <td>{{$iteration}}</td>
                                        <td>{{date('d-m-Y', strtotime($expense->date))}}</td>
                                        <td>{{$expense->id}}</td>
                                        <td class="text-left">{{$expense->name}}</td>
                                        <td class="text-left">{{$expense->address}}</td>
                                        <td>{{$expense->mobile}}</td>
                                        <td class="text-wrap">{{$expense->remarks}}</td>
                                        <td class="text-right">{{$expense->amount ? formatAmount($expense->amount) . '/=' : '-'}}</td>
                                        <td class="text-right">{{$expense->other_charge ? formatAmount($expense->other_charge) . '/=' : '-'}}</td>
                                        <td class="text-right">{{$line_total ? formatAmount($line_total) . '/=' : '-'}}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                data-toggle="dropdown">
                                                <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a href="{{route('salary.expense.edit', $expense->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a></li>
                                                    <li><a href="{{route('salary.expense.delete', $expense->id)}}" class="btn btn-danger" id="delete"><i class="fa fa-trash" ></i></a></li>
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
                                    <td></td>
                                    <td></td>
                                    <td class="text-right"><b>{{$total_salary ? formatAmount($total_salary) . '/=' : '-'}}</b></td>
                                    <td class="text-right"><b>{{$total_others ? formatAmount($total_others) . '/=' : '-'}}</b></td>
                                    <td class="text-right"><b>{{$total ? formatAmount($total) . '/=' : '-'}}</b></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @if (method_exists($salary_expenses, 'links'))
                        <div class="d-flex mt-2 justify-content-center">
                            <div>
                                {{ $salary_expenses->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
