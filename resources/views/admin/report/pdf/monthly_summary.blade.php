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
    .header-date-area {
        margin: 0px 20px;
    }

    .date-area {
        width: 48%;
        float: left
    }

    .table td,
    .table th {
        border: 1px solid #000;
        padding: 8px;
    }

    .table th {
        padding-top: 6px;
        padding-bottom: 6px;
        text-align: left;
        background-color: #ffffff;
        color: rgb(0, 0, 0);
    }
</style>

<body>
    @php
        $total_dokan = 0;
        $total_sales = 0;
        $total_purchase = 0;
        $total_collection = 0;
        $total_salary = 0;
        $total_payment = 0;
        $total_bank = 0;
        $total_labour = 0;
        $total_carring = 0;
    @endphp
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="container container-body">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="row">
                    <div class="due-list-area">

                        <div class="header-area">
                            <div class="banner-area">
                                <img src="{{ public_path('assets/images/firoz_header.jpg') }}" width="100%" height="120"
                                    alt="">
                            </div>
                            <h5 class="text-center text-dark">Challan/Cashmemo.</h5>
                        </div>
                        @if($month_name)
                            <div class="header-date-area">
                                <div class="date-area">
                                    <h6 class="text-left">Month: {{$month_name}}</h6>
                                </div>
                            </div>
                        @endif


                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="daily-summary-sales-report-area mt-4">
                                    <div class="x_title">
                                        <h5 class="text-center"><strong>Sales Report</strong></h5>
                                    </div>
                                    @if(!empty($sales))
                                        <table class="table ">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>Customer Name</th>
                                                    <th>Address</th>
                                                    <th>Mobile</th>
                                                    <th>Total Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @forelse ($sales as $report)
                                                    @php
                                                        $total_sales += $report->total_price;
                                                    @endphp
                                                    <tr>
                                                        <td class="text-center">{{$report->customer->name}}</td>
                                                        <td class="text-center">{{$report->customer->address}}</td>
                                                        <td class="text-center">{{$report->customer->mobile}}</td>
                                                        <td class="text-right">{{$report->total_price}}/=</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td class="text-center" colspan="4"> No Data Found?</td>
                                                    </tr>
                                                @endforelse
                                                <tr class="text-right">
                                                    <td colspan="4" class="text-right">Total Amount = {{$total_sales}} /=
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="daily-summary-sales-report-area mt-4">
                                    <div class="x_title">
                                        <h5 class="text-center"> <strong>Purchase Report</strong></h5>
                                    </div>
                                    @if(!empty($purchase))
                                        <table class="table ">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>Company Name</th>
                                                    <th>Address</th>
                                                    <th>Mobile</th>
                                                    <th>Total Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @forelse ($purchase as $report)
                                                    @php
                                                        $total_purchase += $report->total_price;
                                                    @endphp
                                                    <tr>
                                                        <td class="text-center">{{$report->supplier->company_name}}</td>
                                                        <td class="text-center">{{$report->supplier->address}}</td>
                                                        <td class="text-center">{{$report->supplier->mobile}}</td>
                                                        <td class="text-right">{{$report->total_price}}/=</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td class="text-center" colspan="4"> No Data Found?</td>
                                                    </tr>
                                                @endforelse
                                                <tr class="text-right">
                                                    <td colspan="4" class="text-right">Total Amount = {{$total_purchase}} /=
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="daily-summary-sales-report-area mt-4">
                                    <div class="x_title">
                                        <h5 class="text-center"> <strong>Collection Report</strong></h5>
                                    </div>
                                    @if(!empty($collection))
                                        <table class="table ">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>Customer Name</th>
                                                    <th>Address</th>
                                                    <th>Mobile</th>
                                                    <th>Total Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @forelse ($collection as $report)
                                                    @php
                                                        $total_collection += $report->payment;
                                                    @endphp
                                                    <tr>
                                                        <td class="text-center">{{$report->customer->name}}</td>
                                                        <td class="text-center">{{$report->customer->address}}</td>
                                                        <td class="text-center">{{$report->customer->mobile}}</td>
                                                        <td class="text-right">{{$report->payment}}/=</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td class="text-center" colspan="4"> No Data Found?</td>
                                                    </tr>
                                                @endforelse
                                                <tr class="text-right">
                                                    <td colspan="4" class="text-right">Total Amount = {{$total_collection}}
                                                        /=</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="daily-summary-sales-report-area mt-4">
                                    <div class="x_title">
                                        <h5 class="text-center"> <strong>Payment Report</strong></h5>
                                    </div>
                                    @if(!empty($payment))
                                        <table class="table ">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>Company Name</th>
                                                    <th>Address</th>
                                                    <th>Mobile</th>
                                                    <th>Total Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @forelse ($payment as $report)
                                                    @php
                                                        $total_payment += $report->payment;
                                                    @endphp
                                                    <tr>
                                                        <td class="text-center">{{$report->supplier->company_name}}</td>
                                                        <td class="text-center">{{$report->supplier->address}}</td>
                                                        <td class="text-center">{{$report->supplier->mobile}}</td>
                                                        <td class="text-right">{{$report->payment}}/=</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td class="text-center" colspan="4"> No Data Found?</td>
                                                    </tr>
                                                @endforelse
                                                <tr class="text-right">
                                                    <td colspan="4" class="text-right">Total Amount = {{$total_payment}} /=
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="daily-summary-sales-report-area mt-4">
                                        <div class="x_title">
                                            <h5 class="text-center"> <strong>Salary Expense Report</strong></h5>
                                        </div>
                                        @if(!empty($salary))
                                            <table class="table ">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>Employee Name</th>
                                                        <th>Address</th>
                                                        <th>Mobile</th>
                                                        <th>Payment Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @forelse ($salary as $report)
                                                        @php
                                                            $total_salary += $report->payment_amount;
                                                        @endphp
                                                        <tr>
                                                            <td class="text-center">{{$report->employee->name}}</td>
                                                            <td class="text-center">{{$report->employee->address}}</td>
                                                            <td class="text-center">{{$report->employee->mobile}}</td>
                                                            <td class="text-right">{{$report->payment_amount}}/=</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td class="text-center" colspan="4"> No Data Found?</td>
                                                        </tr>
                                                    @endforelse
                                                    <tr class="text-right">
                                                        <td colspan="4" class="text-right">Total Amount = {{$total_salary}}
                                                            /=</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="daily-summary-sales-report-area mt-4">
                                        <div class="x_title">
                                            <h5 class="text-center"> <strong>Bank Expense Report</strong></h5>
                                        </div>
                                        @if(!empty($bank))
                                            <table class="table ">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>Bank Name</th>
                                                        <th>Payment Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @forelse ($bank as $report)
                                                        @php
                                                            $total_bank += $report->payment_amount;
                                                        @endphp
                                                        <tr>
                                                            <td class="text-center">{{$report->bank->name}}</td>
                                                            <td class="text-right">{{$report->payment_amount}}/=</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td class="text-center" colspan="2"> No Data Found?</td>
                                                        </tr>
                                                    @endforelse
                                                    <tr class="text-right">
                                                        <td colspan="2" class="text-right">Total Amount = {{$total_bank}} /=
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="daily-summary-sales-report-area mt-4">
                                        <div class="x_title">
                                            <h5 class="text-center"> <strong>Labour Expense Report</strong></h5>
                                        </div>
                                        @if(!empty($labour))
                                            <table class="table ">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>Purpose</th>
                                                        <th>Payment Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @forelse($labour as $report)
                                                        @php
                                                            $total_labour += $report->payment_amount;
                                                        @endphp
                                                        <tr>
                                                            <td class="text-center">{{$report->purpose}}</td>
                                                            <td class="text-right">{{$report->payment_amount}}/=</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td class="text-center" colspan="2"> No Data Found?</td>
                                                        </tr>
                                                    @endforelse
                                                    <tr class="text-right">
                                                        <td colspan="2" class="text-right">Total Amount = {{$total_labour}}
                                                            /=</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="daily-summary-sales-report-area mt-4">
                                        <div class="x_title">
                                            <h5 class="text-center"> <strong>Carring Expense Report</strong></h5>
                                        </div>
                                        @if(!empty($carring))
                                            <table class="table ">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>Driver Name</th>
                                                        <th>Gari Number</th>
                                                        <th>Payment Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @forelse ($carring as $report)
                                                        @php
                                                            $total_carring += $report->payment_amount;
                                                        @endphp
                                                        <tr>
                                                            <td class="text-center">{{$report->purpose}}</td>
                                                            <td class="text-right">{{$report->payment_amount}}/=</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td class="text-center" colspan="3"> No Data Found?</td>
                                                        </tr>
                                                    @endforelse
                                                    <tr class="text-right">
                                                        <td colspan="3" class="text-right">Total Amount = {{$total_carring}}
                                                            /=</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="daily-summary-sales-report-area mt-4">
                                        <div class="x_title">
                                            <h5 class="text-center"> <strong>Dokan Expense Report</strong></h5>
                                        </div>
                                        @if(!empty($dokan))
                                            <table class="table ">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>Dokan Name</th>
                                                        <th>Rent Month</th>
                                                        <th>Payment Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @forelse ($dokan as $report)
                                                        @php
                                                            $total_dokan += $report->payment_amount;
                                                        @endphp
                                                        <tr>
                                                            <td class="text-center">{{$report->dokan_rent}}</td>
                                                            <td class="text-center">{{$report->rent_month}}</td>
                                                            <td class="text-right">{{$report->payment_amount}}/=</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td class="text-center" colspan="3"> No Data Found?</td>
                                                        </tr>
                                                    @endforelse
                                                    <tr class="text-right">
                                                        <td colspan="3" class="text-right">Total Amount = {{$total_dokan}}
                                                            /=</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 com-sm-12">
                                <h4 class="text-center">Total Amount</h4>
                                <table class="table ">
                                    <thead>
                                        <tr>
                                            <th>Total Sales</th>
                                            <th>Total Purchase</th>
                                            <th>Total Collection</th>
                                            <th>Total Payment</th>
                                            <th>Total Salary</th>
                                            <th>Total Bank</th>
                                            <th>Total Carring</th>
                                            <th>Total Labour</th>
                                            <th>Total Dokan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-right">{{$total_sales}}/=</td>
                                            <td class="text-right">{{$total_purchase}}/=</td>
                                            <td class="text-right">{{$total_collection}}/=</td>
                                            <td class="text-right">{{$total_payment}}/=</td>
                                            <td class="text-right">{{$total_salary}}/=</td>
                                            <td class="text-right">{{$total_bank}}/=</td>
                                            <td class="text-right">{{$total_carring}}/=</td>
                                            <td class="text-right">{{$total_labour}}/=</td>
                                            <td class="text-right">{{$total_dokan}}/=</td>
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
</body>

</html>