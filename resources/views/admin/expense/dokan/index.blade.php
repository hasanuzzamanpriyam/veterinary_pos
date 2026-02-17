@extends('layouts.admin')

@section('page-title')
Dokan Expenses List
@endsection

@section('main-content')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto">Dokan expenses List</h2>
                <a href="{{route('dokan.expense.create')}}" class="btn btn-md btn-primary"> <i class="fa fa-plus" aria-hidden="true"></i> Add Dokan Expense : </a>
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
                                <th class="all">Date</th>
                                <th class="all">Voucher No.</th>
                                <th class="all">Dokan Name</th>
                                <th class="all">Rent Amount</th>
                                <th class="all">Rent Month</th>
                                <th class="all">Paying By</th>
                                <th class="all">Receiving By</th>
                                <th class="all">Payment Amount</th>
                                <th class="all">remarks</th>
                                <th class="all">Created by</th>
                                <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach($dokan_expenses as $expense)
                                @php
                                 $total = $total+$expense->payment_amount;
                                @endphp
                                    <tr>
                                        <td>{{$expense->date}}</td>
                                        <td>{{$expense->voucher_no}}</td>
                                        <td>{{$expense->name}}</td>
                                        <td class="text-right">{{$expense->amount}}/-</td>
                                        <td>{{$expense->amount_month}}-{{$expense->year}}</td>
                                        <td>{{$expense->payment_by}}</td>
                                        <td>{{$expense->receiving_by}}</td>
                                        <td class="text-right">{{$expense->payment_amount}}/-</td>
                                        <td class="text-wrap">{{$expense->remarks}}</td>
                                        <td>{{$expense->created_by}}</td>
                                        <td> <a href="{{route('dokan.expense.edit',$expense->id)}}" class="btn btn-success"><i class="fa fa-edit" ></i></a> <a href="{{route('dokan.expense.delete',$expense->id)}}" class="btn btn-danger" id="delete"><i class="fa fa-trash" ></i></a></td>
                                    </tr>

                                @endforeach

                            </tbody>
                            {{-- <tr class="text-center">
                                <td colspan="9 h5"> <strong>Total = </strong>{{ $total}}/-</td>
                            </tr> --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
