<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Sales Invoice</title>
</head>


<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

<style>
    .table>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
        padding: 0px 8px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #ddd;
    }
    .product-list-area {
        border-top: 1px solid #8f8f8f;
    }
    .in-word-area h6 {
        font-size: 12px;
    }

    .in-word-area h6 {
        border-bottom: 1px dotted;

    }

    .bottom-area {
        margin-top: 45px;

    }

    .customer-signature-area {
        border-top: 1px solid #000;
    }

    .supplier-signature-area {
        border-top: 1px solid #000;
    }

    div.header-area h5 {
        text-align: center;
    }


    .body-area .address-area .customer_invoice_header {
        display: flex;
        justify-content: space-between;
    }

    .body-area .address-area .customer_invoice_header .invoice_header_left,
    .body-area .address-area .customer_invoice_header .invoice_header_right {
        padding-left: 0px;
        float: left;
        width: 50%;
    }

    ul.invoice_header_right {
        text-align: right;
    }

    .body-area .address-area .customer_invoice_header ul {
        list-style-type: none;
    }

    table.calculation_below_table {
        float: right;
    }

    .col-lg-12.col-md-12.col-sm-12.offset-2.product_list_table {
        padding: 4px 0px;
    }

    table.calculation_below_table tbody tr th {
        text-align: left;
        padding-right: 28px;
    }


    table.calculation_below_table tbody tr td {
        text-align: right;
    }
    table.calculation_below_table tbody tr th,
    table.calculation_below_table tbody tr td {
        border: 1px solid #8f8f8f;
        padding: 0 8px;
    }

    .product-list-area table {

        width: 100%;
        border-collapse: collapse;
    }

    .product-list-area table tbody tr td,
    .product-list-area table>thead>tr>th {
        border-right: 1px solid #8f8f8f;
        border-bottom: 1px solid #8f8f8f;
        border-left: 1px solid #8f8f8f;
    }
    .product-list-area table>tfoot>tr>th {
        border-right: 1px solid #8f8f8f;
        border-bottom: 1px solid #8f8f8f;
        border-left: 1px solid #8f8f8f;
    }

    .product-list-area table>thead>tr>th {
        border-top: 1px solid #8f8f8f;
    }

    .product-list-area table tbody tr td:nth-child(1) {
        border-left: 1px solid #8f8f8f;
    }


    .print-button-area {

        display: flex;
        justify-content: center;
        padding-top: 10px;
        padding-bottom: 30px;
    }

    @media print {
        #sales_print_button {
            display: none;
        }
        .customer-signature-area {
            width: 40%;
            float: left;
        }
        .blank-space {
            display: none
        }
        .supplier-signature-area {
            width: 40%;
            float: right;
        }
        .bottom-area::after {
            content: "";
            clear: both;
            display: table;
        }
        .in-word-area h6 {
            border-bottom: none;

        }

    }
</style>

<body>

    <!---modified container--->
    <div class="container">
        <div class="header-area">
            <div class="banner-area">
                {{-- <img src="{{ public_path('assets/images/firoz_header.jpg') }}" width="100%" height="120" alt="">
                --}}
                <img src="{{ asset('assets/images/firoz_header.jpg') }}" width="100%" height="120" alt="">
            </div>
            <h5 class="text-center text-dark"><strong>Challan # {{ $customer_info->id }}</strong></h5>
        </div>
        <!---end header-area--->

        <div class="body-area">
            <div class="address-area">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="supplier-info-area">
                            {{-- @dump($customer_info) --}}
                            <!----- address information table -area----->
                            <table class="table table-bordered table-striped">

                                <tbody>

                                    <tr>
                                        <th>Return Date</th>
                                        <th>Customer Name</th>
                                        <th>Address</th>
                                    </tr>

                                    <tr>
                                        <td class="text-left p-0 comon_column">{{date('d-m-Y',
                                            strtotime($customer_info->date))}}</td>
                                        <td class="text-left p-0 memo_product_title"> {{$customer_info->customer->name}}
                                        </td>
                                        <td class="text-left p-0 comon_column">{{$customer_info->customer->address}} - {{$customer_info->customer->mobile}}
                                        </td>

                                    </tr>
                                    <tr>
                                        <th>Return Invoice No</th>
                                        <th>Store/Warehouse</th>
                                        <th>Remarks</th>
                                    </tr>
                                    <tr>
                                        <td class="text-left p-0 comon_column">{{$customer_info->id}} </td>
                                        <td class="text-left p-0 comon_column">{{$customer_info->store->name}}</td>
                                        <td class="text-left p-0 comon_column">{{$customer_info->remarks}}</td>
                                    </tr>

                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>
            </div>

            <div class="product-area">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="product-list-area">

                            <!----- product information table -area----->
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Code</th>
                                        <th>Name</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-right">Price</th>
                                        <th class="text-right">Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_summary = [
                                            'qty' => [],
                                            'price' => 0,
                                            'sub_total' => 0
                                        ];
                                    @endphp
                                    @forelse ($products as $product)

                                        @php
                                            $total_summary['qty'][$product->product->type] = $total_summary['qty'][$product->product->type] ?? 0;
                                            $total_summary['qty'][$product->product->type] += $product->quantity;
                                            $total_summary['price'] += $product->unit_price;
                                            $total_summary['sub_total'] += $product->total_price;
                                        @endphp
                                        <tr>
                                            <td class="text-center p-0 comon_column">{{$product->product_code}}</td>
                                            <td class="text-left p-0 memo_product_title">{{$product->product_name}}</td>
                                            <td class="text-center p-0 comon_column">
                                                {{$product->quantity}} {{
                                                trans_choice('labels.'.$product->product->type, $product->quantity)
                                                }}
                                            </td>
                                            <td class="text-right p-0 comon_column">{{number_format($product->unit_price)}}/=</td>
                                            <td class="text-right p-0 comon_column">{{number_format($product->total_price)}}/=</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                Not Found!
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if (count($products) > 0)
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th class="text-center p-1 comon_column">
                                            @if ( count($total_summary['qty']) > 0)
                                                @foreach ($total_summary['qty'] as $key => $value)
                                                    {{ $value }} {{ trans_choice('labels.'.$key, $value) }}
                                                @endforeach
                                            @endif
                                        </th>

                                        <th class="text-right p-1 comon_column"></th>
                                        <th class="text-right p-1 comon_column">{{number_format($total_summary['sub_total'])}}/=</th>
                                    </tr>
                                </tfoot>
                                @endif

                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="calculation-area">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="calculation-area d-flex justify-content-end">

                            @php
                            $gTotal = $customer_info->previous_due + $customer_info->total_price -
                            $customer_info->price_discount + $customer_info->vat + $customer_info->carring +
                            $customer_info->other_charge;
                            $prev_balance = $customer_info->balance + $customer_info->payment - $gTotal;
                            @endphp

                            <!----- calculation-area----->
                            <table class="calculation_below_table">
                                <tr>
                                    <th>Total Price</th>
                                    <td>{{number_format($customer_info->total_price)}}/=</td>
                                </tr>

                                @if($customer_info->carring > 0)
                                    <tr>
                                        <th>Carring</th>
                                        <td>{{number_format($customer_info->carring ?? 0)}}/=</td>
                                    </tr>
                                @endif

                                @if($customer_info->other_charge > 0)
                                    <tr>
                                        <th>Others</th>
                                        <td>{{number_format($customer_info->other_charge ?? 0)}}/=</td>
                                    </tr>
                                @endif

                                <tr>
                                    <th> Total: </th>
                                    <td>
                                        @php
                                            $total = $customer_info->total_price + $customer_info->carring + $customer_info->other_charge;
                                        @endphp
                                        {{ number_format($total) }}/=
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="footer-area">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="in-word-area">
                        <h6 class="text-left text-dark">In Words: {{numberToWords($total)}}</h6>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="bottom-area py-3">
                        <div class="col-sm-4 customer-signature-area">
                            <h4 class="text-center text-dark">Customer Signature</h4>
                        </div>
                        <div class="col-sm-4 blank-space">
                        </div>
                        <div class="col-sm-4 supplier-signature-area">
                            <h4 class="text-center text-dark">Authorized Signature</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="col-sm-4">
                    </div>
                    <div class="col-sm-4">
                        <div class="thanks-area">
                            <h5 class="text-center text-dark"><small>Thank you for shopping with us</small></h5>
                        </div>
                    </div>
                    <div class="col-sm-4">
                </div>
            </div>
        </div>
        </div>
        <!---end footer-area--->

        <!---print page-area--->
        <div class="row">
            <div class="col-lg-12 md-12 col-sm-12">
                <div class="print-button-area d-flex justify-content-end">

                    <button id="sales_print_button" onclick="window.print()"> <i class="fa fa-print text-danger"></i>
                        Print</button>
                </div>
            </div>
        </div>
        <!---print page-area--->


    </div>
    <!---end container--->

</body>

</html>
