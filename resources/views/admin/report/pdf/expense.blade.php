<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<style>
/* page size customize here */
/* .container-body{
   width: 80%;
   font-size: 12px;
} */
.header-date-area
{
    margin: 0px 20px;
}
.date-area
{
    width: 48%;
    float:left
}
.table td, .table th
{
border: 1px solid #000;
padding: 8px;
}
.table th
{
  padding-top: 6px;
  padding-bottom: 6px;
  text-align: left;
  background-color: #ffffff;
  color: rgb(0, 0, 0);
}
</style>
<body >

    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="container container-body">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="due-list-area">

                    <div class="header-area">
                        <div class="banner-area">
                            <img src="{{ public_path('assets/images/firoz_header.jpg') }}" width="100%" height="120" alt="">
                        </div>
                        <h5 class="text-center text-dark">Challan/Cashmemo.</h5>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="due-list-area mt-4">

                                @if(!empty($labours))
                                    <div class="x_title">
                                        <h3 class="text-center"> <strong>{{$expense_name}} Expense Report</strong></h3>
                                    </div>
                                @elseif(!empty($salaries))
                                    <div class="x_title">
                                        <h3 class="text-center"> <strong>{{$expense_name}} Expense Report</strong></h3>
                                    </div>
                                @elseif(!empty($banks))
                                    <div class="x_title">
                                        <h3 class="text-center"> <strong>{{$expense_name}} Expense Report</strong></h3>
                                    </div>
                                @elseif(!empty($carrings))
                                    <div class="x_title">
                                        <h3 class="text-center"> <strong>{{$expense_name}} Expense Report</strong></h3>
                                    </div>
                                @elseif(!empty($dokans))
                                    <div class="x_title">
                                        <h3 class="text-center"> <strong>{{$expense_name}} Expense Report</strong></h3>
                                    </div>
                                @endif
                                @if($start_date && $end_date)
                                    <div class="header-date-area">
                                        <div class="date-area">
                                            <h6 class="text-left">From: {{date('d-m-Y', strtotime($start_date))}}</h6>
                                        </div>
                                        <div class="date-area">
                                            <h6 class="text-right">To: {{date('d-m-Y', strtotime($end_date))}}</h6>
                                        </div>
                                    </div>
                                @endif
                                @if(!empty($labours))

                                    <table class="table">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Date</th>
                                                <th>Voucher No.</th>
                                                <th>Expense Amount</th>
                                                <th>Payment Amount</th>
                                                <th>Paying By</th>
                                                <th>Purpose</th>
                                                <th>Receive</th>
                                                <th>Remarks</th>
                                                <th>Created by</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $total_quantity = 0;
                                                $total_amount = 0;

                                            @endphp
                                            @forelse ($labours as $report )


                                            <tr>
                                                <td>{{date('d-m-Y', strtotime($report->date))}}</td>
                                                <td>{{$report->voucher_no}}</td>
                                                <td class="text-right">{{$report->amount}}/=</td>
                                                <td class="text-right">{{$report->payment_amount}}/=</td>
                                                <td class="text-center">{{$report->payment_by}}</td>
                                                <td class="text-wrap">{{$report->purpose }}</td>
                                                <td class="text-wrap">{{$report->receive}}</td>
                                                <td class="text-wrap">{{$report->remarks}}</td>
                                                <td class="text-center">{{$report->created_by}}</td>

                                            </tr>
                                            @empty
                                            <tr>
                                                <td class="text-center h4" colspan="10">No data found!</td>
                                            </tr>
                                            @endforelse

                                        </tbody>
                                    </table>
                                    @elseif(!empty($salaries))

                                    <table class="table">
                                        <thead>
                                            <tr class="text-center">
                                                <th>SL. No.</th>
                                                <th>Date</th>
                                                <th>Voucher No.</th>
                                                <th>Employee Name</th>
                                                <th>Salary Amount</th>
                                                <th>Salary Month</th>
                                                <th>Payment Anount</th>
                                                <th>Payment Type</th>
                                                <th>Remarks</th>
                                                <th>Created by</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $total_quantity = 0;
                                                $total_amount = 0;

                                            @endphp
                                            @forelse ($salaries as $report )

                                            {{-- @php
                                                $total_quantity += $report->total_qty;
                                                $total_amount += $report->total_price;
                                            @endphp --}}
                                            <tr>
                                                <td class="text-center">{{$loop->iteration}}</td>
                                                <td class="text-center">{{date('d-m-Y', strtotime($report->date))}}</td>
                                                <td class="text-center">{{$report->voucher_no}}</td>
                                                <td class="text-center">{{$report->employee->name}}</td>
                                                <td class="text-right">{{$report->salary_amount}}/=</td>
                                                <td class="text-center">{{$report->salary_month}}</td>
                                                <td class="text-right">{{$report->payment_amount}}/=</td>
                                                <td class="text-center">{{$report->payment_by}}</td>
                                                <td class="text-wrap">{{$report->remarks}}</td>
                                                <td class="text-center">{{$report->created_by}}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td class="text-center h4" colspan="10">No data found!</td>
                                            </tr>
                                            @endforelse
                                            {{-- <tr class="text-right">
                                                <td colspan="8">Total Quantity = {{$total_quantity}} Pcs/Bags</td>
                                                <td colspan="1">Total Amount = {{$total_amount}} /=</td>
                                            </tr> --}}
                                        </tbody>

                                    </table>
                                    @elseif(!empty($banks))

                                    <table class="table">
                                        <thead>
                                            <tr class="text-center">
                                                <th>SL. No.</th>
                                                <th>Date</th>
                                                <th>Voucher No.</th>
                                                <th>Bank Name</th>
                                                <th>Profit Amount</th>
                                                <th>Profit Month</th>
                                                <th>Other Charge</th>
                                                <th>Payment Anount</th>
                                                <th>Payment Type</th>
                                                <th>Remarks</th>
                                                <th>Created by</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $total_quantity = 0;
                                                $total_amount = 0;

                                            @endphp
                                            @forelse ($banks as $report )

                                            {{-- @php
                                                $total_quantity += $report->total_qty;
                                                $total_amount += $report->total_price;
                                            @endphp --}}
                                            <tr>
                                                <td class="text-center">{{$loop->iteration}}</td>
                                                <td class="text-center">{{date('d-m-Y', strtotime($report->date))}}</td>
                                                <td class="text-center">{{$report->voucher_no}}</td>
                                                <td class="text-center">{{$report->bank->name}}</td>
                                                <td class="text-right">{{$report->profit_amount}}/=</td>
                                                <td class="text-center">{{$report->profit_month}}</td>
                                                <td class="text-center">{{$report->other_charge}}=/</td>
                                                <td class="text-right">{{$report->payment_amount}}/=</td>
                                                <td class="text-center">{{$report->payment_by}}</td>
                                                <td class="text-wrap">{{$report->remarks}}</td>
                                                <td class="text-center">{{$report->created_by}}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td class="text-center h4" colspan="10">No data found!</td>
                                            </tr>
                                            @endforelse
                                            {{-- <tr class="text-right">
                                                <td colspan="8">Total Quantity = {{$total_quantity}} Pcs/Bags</td>
                                                <td colspan="1">Total Amount = {{$total_amount}} /=</td>
                                            </tr> --}}
                                        </tbody>
                                    </table>
                                    @elseif(!empty($carrings))
                                    <table class="table">
                                        <thead>
                                            <tr class="text-center">
                                                <th>SL. No.</th>
                                                <th>Date</th>
                                                <th>Voucher No.</th>
                                                <th>Driver Name</th>
                                                <th>Gari Number</th>
                                                <th>Load Point</th>
                                                <th>Delivery Point</th>
                                                <th>Payment Anount</th>
                                                <th>Remarks</th>
                                                <th>Created by</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $total_quantity = 0;
                                                $total_amount = 0;

                                            @endphp
                                            @forelse ($carrings as $report )

                                            {{-- @php
                                                $total_quantity += $report->total_qty;
                                                $total_amount += $report->total_price;
                                            @endphp --}}
                                            <tr>
                                                <td class="text-center">{{$loop->iteration}}</td>
                                                <td class="text-center">{{date('d-m-Y', strtotime($report->date))}}</td>
                                                <td class="text-center">{{$report->voucher_no}}</td>
                                                <td class="text-center">{{$report->driver_name}}</td>
                                                <td class="text-center">{{$report->gary_number}}</td>
                                                <td class="text-right">{{$report->load_point}}/=</td>
                                                <td class="text-right">{{$report->delivery_point}}</td>
                                                <td class="text-right">{{$report->payment_amount}}/=</td>
                                                <td class="text-wrap">{{$report->remarks}}</td>
                                                <td class="text-center">{{$report->created_by}}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td class="text-center h4" colspan="10">No data found!</td>
                                            </tr>
                                            @endforelse
                                            {{-- <tr class="text-right">
                                                <td colspan="8">Total Quantity = {{$total_quantity}} Pcs/Bags</td>
                                                <td colspan="1">Total Amount = {{$total_amount}} /=</td>
                                            </tr> --}}
                                        </tbody>
                                    </table>
                                    @elseif(!empty($dokans))

                                    <table class="table">
                                        <thead>
                                            <tr class="text-center">
                                                <th>SL. No.</th>
                                                <th>Date</th>
                                                <th>Voucher No.</th>
                                                <th>Dokan Rent</th>
                                                <th>Rent Amount</th>
                                                <th>Rent Month</th>
                                                <th>Payment By</th>
                                                <th>Receive By</th>
                                                <th>Remarks</th>
                                                <th>Created by</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $total_quantity = 0;
                                                $total_amount = 0;

                                            @endphp
                                            @forelse ($dokans as $report )

                                            {{-- @php
                                                $total_quantity += $report->total_qty;
                                                $total_amount += $report->total_price;
                                            @endphp --}}
                                            <tr>
                                                <td class="text-center">{{$loop->iteration}}</td>
                                                <td class="text-center">{{date('d-m-Y', strtotime($report->date))}}</td>
                                                <td class="text-center">{{$report->voucher_no}}</td>
                                                <td class="text-center">{{$report->dokan_rent}}/=</td>
                                                <td class="text-center">{{$report->rent_amount}}/=</td>
                                                <td class="text-right">{{$report->rent_month}}</td>
                                                <td class="text-right">{{$report->payment_by}}</td>
                                                <td class="text-right">{{$report->receiving_by}}</td>
                                                <td class="text-wrap">{{$report->remarks}}</td>
                                                <td class="text-center">{{$report->created_by}}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td class="text-center h4" colspan="10">No data found!</td>
                                            </tr>
                                            @endforelse
                                            {{-- <tr class="text-right">
                                                <td colspan="8">Total Quantity = {{$total_quantity}} Pcs/Bags</td>
                                                <td colspan="1">Total Amount = {{$total_amount}} /=</td>
                                            </tr> --}}
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
