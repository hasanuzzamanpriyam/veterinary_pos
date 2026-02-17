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
<body>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="row container container-body">
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
                                @if(!empty($reports))
                                {{-- <div class="x_title">
                                    <h3 class="text-center"> <strong>Customer Wise Collection Report</strong></h3>
                                </div> --}}
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Date</th>
                                                <th>Customer Name</th>
                                                <th>Company Name</th>
                                                <th>Address</th>
                                                <th>Mobile No.</th>
                                                <th>Invoice No.</th>
                                                <th>Previous Due</th>
                                                <th>Payment Amount</th>
                                                <th>Paying By</th>
                                                <th>Current Due</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @php
                                                $total_quantity = 0;
                                                $total_amount = 0;

                                            @endphp --}}
                                            @foreach ($reports as $report)
                                            {{-- @php
                                                $total_quantity += $report->total_qty;
                                                $total_amount += $report->total_price;
                                            @endphp --}}
                                            <tr>
                                                <td>{{date('d-m-Y', strtotime($report->date))}}</td>
                                                <td>{{$report->customer->name}}</td>
                                                <td>{{$report->customer->company_name}}</td>
                                                <td class="text-wrap">{{$report->customer->address}}</td>
                                                <td class="text-center">{{$report->customer->mobile}}</td>
                                                <td class="text-center">{{$report->invoice_no}}</td>
                                                <td class="text-right">{{$report->previous_due ?? -$report->previous_advance}}/=</td>
                                                <td class="text-right">{{$report->paid_amount}}/=</td>
                                                <td class="text-center">{{$report->payment_by}} : {{$report->bank_title}}</td>
                                                <td class="text-right">{{$report->current_due ?? -$report->current_advance}}/=</td>
                                                <td class="text-wrap">{{$report->purpose ?? $report->remarks}}</td>
                                            </tr>
                                            @endforeach
                                            {{-- <tr class="text-right">
                                                <td colspan="8">Total Quantity = {{$total_quantity}} Pcs/Bags</td>
                                                <td colspan="1">Total Amount = {{$total_amount}} /=</td>
                                            </tr> --}}
                                        </tbody>

                                    </table>
                                    @elseif(!empty($all_reports))
                                    {{-- <div class="x_title">

                                        <h3 class="text-center"> <strong>Total Collection Report</strong></h3>
                                    </div> --}}
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Date</th>
                                                <th>Customer Name</th>
                                                <th>Company Name</th>
                                                <th>Address</th>
                                                <th>Mobile No.</th>
                                                <th>Invoice No.</th>
                                                <th>Previous Due</th>
                                                <th>Payment Amount</th>
                                                <th>Paying By</th>
                                                <th>Current Due</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $total_quantity = 0;
                                            $total_amount = 0;

                                        @endphp
                                        @foreach ($all_reports as $report)
                                        {{-- @php
                                            $total_quantity += $report->total_qty;
                                            $total_amount += $report->total_price;
                                        @endphp --}}
                                        <tr>
                                            <td>{{date('d-m-Y', strtotime($report->date))}}</td>
                                            <td>{{$report->customer->name}}</td>
                                            <td>{{$report->customer->company_name}}</td>
                                            <td class="text-wrap">{{$report->customer->address}}</td>
                                            <td class="text-center">{{$report->customer->mobile}}</td>
                                            <td class="text-center">{{$report->invoice_no}}</td>
                                            <td class="text-right">{{$report->previous_due ?? -$report->previous_advance}}/=</td>
                                            <td class="text-right">{{$report->paid_amount}}/=</td>
                                            <td class="text-center">{{$report->payment_by}}: {{$report->bank_title}}</td>
                                            <td class="text-right">{{$report->current_due ?? -$report->current_advance}}/=</td>
                                            <td class="text-wrap">{{$report->purpose ?? $report->remarks}}</td>
                                        </tr>
                                        @endforeach
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
