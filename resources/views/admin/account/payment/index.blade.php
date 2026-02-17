@extends('layouts.admin')

@section('page-title')
Payment List
@endsection

@section('main-content')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto">Payment List</h2>
                <a href="{{route('payment.create')}}" class="btn btn-md btn-primary"> <i class="fa fa-plus"
                        aria-hidden="true"></i> Create New Payment</a>
            </div>
        </div>

        <div class="x_content p-3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    <div class="card-box table-responsive">
                        <table id="" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <th class="all">Date</th>
                                <th class="all">Invoice</th>
                                <th class="all">Supplier Name</th>
                                <th class="all">Address</th>
                                <th class="all">Mobile</th>
                                <th class="all">Paying By</th>
                                <th class="all">Remarks</th>
                                <th class="all">Amount</th>
                                <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach($payment_list as $payment)
                                    {{-- @dump($payment) --}}
                                    @php
                                        $total += $payment->paid_amount;
                                    @endphp
                                    <tr>
                                        <td class="text-left">{{date('d-m-Y', strtotime($payment->date))}}</td>
                                        <td class="text-left">{{ $payment->id }} ({{$payment->type}})</td>
                                        <td class="text-left">{{$payment->supplier->company_name}}</td>
                                        <td class="text-left">{{$payment->supplier->address}}</td>
                                        <td class="text-left">{{$payment->supplier->mobile}}</td>
                                        <td class="text-left">{{$payment->payment_by}} {{$payment->bank_title ? ' : ' . $payment->bank_title : ''}}</td>
                                        <td class="text-left">{{$payment->payment_remarks}}</td>
                                        <td class="text-right">{{$payment->payment}}/=</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                                Action <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a href="{{ route('purchase.delete', ['invoice' => $payment->id, 'view' => 'payment']) }}" class="btn btn-danger" id="delete"><i class="fa fa-trash" ></i></a></i></a></li>
                                                    <li><a href="{{ route('purchase.view', ['invoice' => $payment->id, 'view' => $payment->type === 'purchase' ? 'purchase' : 'payment']) }}" class="btn btn-info"><i class="fa fa-eye" ></i></a></li>

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
