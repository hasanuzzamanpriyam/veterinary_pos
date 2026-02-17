<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <style>
        @page {
            size: a4 landscape;
            margin: 10mm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .w-100 {
            width: 100%;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .bold {
            font-weight: bold;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .mt-10 {
            margin-top: 10px;
        }

        /* Header Styles */
        .header-area {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 5px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0;
            text-transform: uppercase;
        }

        .report-title {
            font-size: 12px;
            font-weight: bold;
            margin-top: 5px;
            text-decoration: underline;
        }

        /* Info Section */
        .info-table {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 2px;
            border: none;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            width: 70px;
            display: inline-block;
        }

        /* Main Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #777;
            padding: 3px;
            vertical-align: top;
            word-wrap: break-word;
        }

        .data-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        /* Footer */
        .footer-row td {
            background-color: #f9f9f9;
            font-weight: bold;
        }
    </style>
</head>

<body>

                    <div class="header-area">
                        <div class="banner-area">
                            <img src="{{ public_path('assets/images/firoz_header.jpg') }}" width="100%" height="120" alt="">
                        </div>
                        <h5 class="text-center text-dark">Challan/Cashmemo.</h5>
                    </div>
                    {{-- <div class="x_title">
                        @if(!empty($reports))
                        <h3 class="text-center"> <strong>Customer Wise Sales Report</strong></h3>
                        @elseif(!empty($all_reports))
                        <h3 class="text-center"> <strong>Total Sales Report</strong></h3>
                        @endif
                    </div> --}}

                    <div class="customer-info-area">
                        @if(!empty($customer_info))
                        <div class="row">
                            <div class="customer-info">
                                <p class="border">ID: {{ $customer_info->id }}</p>
                            </div>
                            <div class="customer-info">
                                <p class="border">Name: {{$customer_info->name }}</p>
                            </div>
                            <div class="customer-info">
                                <p class="border">Address: {{ $customer_info->address }}</p>
                            </div>
                            <div class="customer-info">
                                <p class="border">Mobile: {{ $customer_info->mobile}}</p>
                            </div>
                        </div>
                        @endif
                        @if($start_date && $end_date)
                        <div class="row">
                            <div class="date-area">
                                <h6 class="text-left">From: {{date('d-m-Y', strtotime($start_date))}}</h6>
                            </div>
                            <div class="date-area">
                                <h6 class="text-right">To: {{date('d-m-Y', strtotime($end_date))}}</h6>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if(!empty($reports))
                    <table class="table">
                        <thead>
                            <tr class="text-center">
                                <th>Date</th>
                                <th>Invoice No.</th>
                                <th>From Delivery</th>
                                <th class="text-center">Description</th>
                                <th>Product Quantity</th>
                                <th>Sales Amount</th>
                                <th>Discount</th>
                                <th>Vat.</th>
                                <th>Carring</th>
                                <th>Others</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $total_qty = 0;
                            $total_sales = 0;
                            $total_discount = 0;
                            $total_vat = 0;
                            $total_carring = 0;
                            $total_others = 0;
                            $total_amount = 0;
                            @endphp
                            @foreach ($reports as $report)
                            @php
                            $total_qty +=$report->total_qty;
                            $total_sales += $report->total_price;
                            $total_discount += $report->price_discount;
                            $total_vat += $report->vat;
                            $total_carring +=$report->carring;
                            $total_others += $report->other_charge;
                            $total_amount += $report->total_price -$report->price_discount+$report->vat+$report->carring+$report->other_charge;
                            @endphp
                            <tr>
                                <td>{{date('d-m-Y', strtotime($report->date))}}</td>
                                <td class="text-center">{{$report->invoice_no}}</td>
                                <td>{{ $report->store->name ?? '' }}</td>
                                <td>
                                    <table class="table">

                                        <tbody>
                                            @php
                                            $type = 0;
                                            @endphp
                                            @foreach ($products as $product)
                                            @php
                                            $type = optional($product->product)->type ?: $type;
                                            @endphp
                                            @if($product->invoice_no == $report->invoice_no)
                                            <tr>
                                                <td><small>{{ optional($product->product)->name ?? '' }}</small></td>
                                                <td><small>{{ $product->product_quantity }} {{ optional($product->product)->type ?? '' }}</small></td>
                                                <td class="text-right"><small>{{ $product->product_price }} /=</small></td>
                                                <td class="text-right"><small>{{ $product->sub_total }} /=</small></td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                                <td class="text-center">{{$report->total_qty}} {{$type}}</td>
                                <td class="text-right">{{$report->total_price}}/=</td>
                                <td class="text-right">{{$report->price_discount}}/=</td>
                                <td class="text-right">{{$report->vat}}/=</td>
                                <td class="text-right">{{$report->carring}}/=</td>
                                <td class="text-right">{{$report->other_charge}}/=</td>
                                <td class="text-right">{{$report->total_price-$report->price_discount+$report->vat+$report->carring+$report->other_charge }}/=</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tr>
                            <td colspan="5" class="text-right">Total= {{$total_qty}} {{$type}}</td>
                            <td colspan="1" class="p-0">Total= {{$total_sales}}/=</td>
                            <td colspan="1" class="p-0">Total= {{$total_discount}}/=</td>
                            <td colspan="1" class="p-0">Total= {{$total_vat}}/=</td>
                            <td colspan="1" class="p-0">Total= {{$total_carring}}/=</td>
                            <td colspan="1" class="p-0">Total= {{$total_others}}/=</td>
                            <td colspan="1" class="p-0">Total= {{$total_amount}}/=</td>
                        </tr>
                    </table>
                    @elseif(!empty($all_reports))

                    <table class="table">
                        <thead>
                            <tr class="text-center">
                                <th>Date</th>
                                <th>Invoice No.</th>
                                <th>From Delivery</th>
                                <th class="text-center">Description</th>
                                <th>Product Quantity</th>
                                <th>Sales Amount</th>
                                <th>Discount</th>
                                <th>Vat.</th>
                                <th>Carring</th>
                                <th>Others</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $total_qty = 0;
                            $total_sales = 0;
                            $total_discount = 0;
                            $total_vat = 0;
                            $total_carring = 0;
                            $total_others = 0;
                            $total_amount = 0;
                            @endphp
                            @foreach ($all_reports as $report)
                            @php
                            $total_qty +=$report->total_qty;
                            $total_sales += $report->total_price;
                            $total_discount += $report->price_discount;
                            $total_vat += $report->vat;
                            $total_carring +=$report->carring;
                            $total_others += $report->other_charge;
                            $total_amount += $report->total_price -$report->price_discount+$report->vat+$report->carring+$report->other_charge;
                            @endphp
                            <tr>
                                <td>{{date('d-m-Y', strtotime($report->date))}}</td>
                                <td class="text-center">{{$report->invoice_no}}</td>
                                <td>{{ $report->store->name ?? '' }}</td>
                                <td>
                                    <table class="table">

                                        <tbody>
                                            @php
                                            $type = 0;
                                            @endphp
                                            @foreach ($products as $product)
                                            @php
                                            $type = optional($product->product)->type ?: $type;
                                            @endphp
                                            @if($product->invoice_no == $report->invoice_no)
                                            <tr>
                                                <td><small>{{ optional($product->product)->name ?? '' }}</small></td>
                                                <td><small>{{ $product->product_quantity }} {{ optional($product->product)->type ?? '' }}</small></td>
                                                <td class="text-right"><small>{{ $product->product_price }} /=</small></td>
                                                <td class="text-right"><small>{{ $product->sub_total }} /=</small></td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                                <td class="text-center">{{$report->total_qty}} {{$type}}</td>
                                <td class="text-right">{{$report->total_price}}/=</td>
                                <td class="text-right">{{$report->price_discount}}/=</td>
                                <td class="text-right">{{$report->vat}}/=</td>
                                <td class="text-right">{{$report->carring}}/=</td>
                                <td class="text-right">{{$report->other_charge}}/=</td>
                                <td class="text-right">{{$report->total_price-$report->price_discount+$report->vat+$report->carring+$report->other_charge }}/=</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tr>
                            <td colspan="5" class="text-right p-0">Total= {{$total_qty}} {{$type}}</td>
                            <td colspan="1" class="p-0">Total= {{$total_sales}}/=</td>
                            <td colspan="1" class="p-0">Total= {{$total_discount}}/=</td>
                            <td colspan="1" class="p-0">Total= {{$total_vat}}/=</td>
                            <td colspan="1" class="p-0">Total= {{$total_carring}}/=</td>
                            <td colspan="1" class="p-0">Total= {{$total_others}}/=</td>
                            <td colspan="1" class="p-0">Total= {{$total_amount}}/=</td>
                        </tr>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>

</html>
