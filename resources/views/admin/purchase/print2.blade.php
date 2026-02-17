<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Purchase Invoice</title>
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
    .customer-signature-area {
        width: 40%;
        float: left;
    }
    .supplier-signature-area {
        width: 40%;
        float: right;
    }
    @media print {
        #sales_print_button {
            display: none;
        }

        .blank-space {
            display: none
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
 <!----- container------->

 <div class="container">
    <div class="header-area">
        <div class="banner-area">
            {{-- <img src="{{ public_path('assets/images/firoz_header.jpg') }}" width="100%" height="120" alt=""> --}}
            <img src="{{ asset('assets/images/firoz_header.jpg') }}" width="100%" height="120" alt="">
        </div>
        <h5 class="text-center text-dark"><strong>Challan # {{ $supplier_info->id }}</strong></h5>
    </div><!---end header-area--->

    <div class="body-area">
        <div class="address-area">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="supplier-info-area">
                         <!----- address information table -area----->
                         <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Supplier Name</th>
                                    <th>Address</th>
                                    <th>Phone</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-left p-0 comon_column">{{date('d-m-Y', strtotime($supplier_info->date))}}</td>
                                    <td class="text-left p-0 comon_column">{{$supplier_info->supplier->company_name}}</td>
                                    <td class="text-left p-0 comon_column">{{$supplier_info->supplier->address}}</td>
                                    <td class="text-left p-0 comon_column">{{$supplier_info->supplier->mobile}}</td>

                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th>Warehouse</th>
                                    <th>Gari Number</th>
                                    <th>Delivery Men</th>
                                    <th>Remarks</th>

                                </tr>
                            </thead>

                            <tbody>

                                <tr>
                                    <td class="text-left p-0 comon_column">{{$supplier_info->warehouse->name}}</td>
                                    <td class="text-left p-0 comon_column">{{$supplier_info->transport_no}}</td>
                                    <td class="text-left p-0 comon_column">{{$supplier_info->delivery_man}}</td>
                                    <td class="text-left p-0 comon_column">{{$supplier_info->supplier_remarks}}</td>

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
                                    <th>Product Name</th>
                                    @if($supplier_info->product_discount > 0)
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Dis.(Qty)</th>
                                    @endif
                                    <th class="text-center">Purchase (Qty)</th>
                                    <th class="text-right">Price</th>
                                    <th class="text-right">Sub Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_summary = [
                                        'qty' => [],
                                        'dis_qty' => [],
                                        'purchase_qty' => [],
                                        'price' => 0,
                                        'sub_total' => 0
                                    ];
                                @endphp
                                @forelse ($products as $product)
                                    @php
                                        $total_summary['qty'][$product->product->type] = $total_summary['qty'][$product->product->type] ?? 0;
                                        $total_summary['qty'][$product->product->type] += $product->quantity;
                                        $total_summary['dis_qty'][$product->product->type] = $total_summary['dis_qty'][$product->product->type] ?? 0;
                                        $total_summary['dis_qty'][$product->product->type] += $product->discount_qty;
                                        $total_summary['purchase_qty'][$product->product->type] = $total_summary['purchase_qty'][$product->product->type] ?? 0;
                                        $total_summary['purchase_qty'][$product->product->type] += ($product->quantity - $product->discount_qty);
                                        $total_summary['price'] += $product->unit_price;
                                        $total_summary['sub_total'] += $product->total_price;
                                    @endphp
                                    <tr>

                                        <td  class="text-center p-1">{{$product->product_code}}</td>
                                        <td  class="text-left p-1">{{$product->product_name}}</td>
                                        @if($supplier_info->product_discount > 0)
                                        <td class="text-center p-1">{{$product->quantity}} {{ trans_choice('labels.'.$product->product->type, $product->quantity) }}</td>
                                        <td class="text-center p-1">{{$product->discount_qty}} {{ trans_choice('labels.'.$product->product->type, $product->discount_qty) }}</td>
                                        @endif
                                        <td class="text-center p-1">{{$product->quantity-$product->discount_qty}} {{ trans_choice('labels.'.$product->product->type, $product->quantity-$product->discount_qty) }} </td>
                                        <td class="text-right p-1">{{formatAmount($product->unit_price)}}/=</td>
                                        <td class="text-right p-1">{{formatAmount($product->total_price)}}/=</td>
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
                                        @if($supplier_info->product_discount > 0)
                                            <th class="text-center p-1 comon_column">
                                                @if ( count($total_summary['qty']) > 0)
                                                    @foreach ($total_summary['qty'] as $key => $value)
                                                        {{ $value }} {{ trans_choice('labels.'.$key, $value) }}
                                                    @endforeach
                                                @endif
                                            </th>
                                            <th class="text-center p-1 comon_column">
                                                @if ( count($total_summary['dis_qty']) > 0)
                                                    @foreach ($total_summary['dis_qty'] as $key => $value)
                                                        {{ $value }} {{ trans_choice('labels.'.$key, $value) }}
                                                    @endforeach
                                                @endif
                                            </th>
                                        @endif
                                        <th class="text-center p-1 comon_column">
                                            @if ( count($total_summary['purchase_qty']) > 0)
                                                @foreach ($total_summary['purchase_qty'] as $key => $value)
                                                    {{ $value }} {{ trans_choice('labels.'.$key, $value) }}
                                                @endforeach
                                            @endif
                                        </th>
                                        <th class="text-right p-1 comon_column"></th>
                                        <th class="text-right p-1 comon_column">{{formatAmount($total_summary['sub_total'])}}/=</th>
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
                                <!-- <h3 class="text-center text-dark">Billing Info</h3> -->
                               <!----- calculation-area----->
                                @php
                                    $gTotal = $supplier_info->total_price - $supplier_info->price_discount - $supplier_info->vat - $supplier_info->carring - $supplier_info->other_charge;
                                    $prev_balance = $supplier_info->balance + $supplier_info->payment - $gTotal;
                                @endphp
                                    <table class="calculation_below_table">
                                        <tr><th>Total Price</th><td>{{formatAmount($supplier_info->total_price)}}/=</td></tr>
                                        @if($supplier_info->price_discount > 0)
                                        <tr><th>Discount</th><td>{{formatAmount($supplier_info->price_discount ?? 0)}}/=</td></tr>
                                        @endif
                                        @if($supplier_info->vat > 0)
                                        <tr><th>Vat</th><td>{{formatAmount($supplier_info->vat ?? 0 )}}/=</td></tr>
                                        @endif
                                        @if($supplier_info->carring > 0)
                                        <tr><th>Carring</th><td>{{formatAmount($supplier_info->carring ?? 0)}}/=</td></tr>
                                        @endif
                                        @if($supplier_info->other_charge > 0)
                                        <tr><th>Others</th><td>{{formatAmount($supplier_info->other_charge ?? 0)}}/=</td></tr>
                                        @endif
                                        <tr><th>Grand Total</th><td class="grand-total"><strong>{{$gTotal}}/=</strong></td></tr>
                                        @if(abs($prev_balance) > 0)
                                        <tr><th>Previous Due</th><td>{{formatAmount($prev_balance ?? 0)}}/=</td></tr>
                                        @endif
                                        <tr><th>Total Due</th><td><strong>{{formatAmount($prev_balance + $gTotal)}}/=</strong></td></tr>
                                        <tr><th>Payment</th><td>{{formatAmount($supplier_info->payment ?? 0)}}/=</td></tr>
                                        <tr><th>Balance</th><td><strong>{{formatAmount($supplier_info->balance)}}/=</strong></td></tr>
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
                    <h6 class="text-left text-dark"><strong>In Words:  {{numberToWords($supplier_info->balance)}}</strong></h6>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="bottom-area">
                            <div class="customer-signature-area">
                                <h4 class="text-center text-dark">Supplier Signature</h4>
                            </div>
                            <div class="supplier-signature-area">
                                <h4 class="text-center text-dark">Authorized Signature</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <h5 class="text-center text-dark m-0"><small>Thank you for shopping with us</small></h5>
                    </div>
                </div>

            </div>
        </div>
    </div>
     <!---end footer-area--->

     <!---print page-area--->
     <div class="row">
        <div class="col-lg-12 md-12 col-sm-12">
            <div class="print-button-area d-flex justify-content-end">

                <button id="sales_print_button" onclick="window.print()" > <i class="fa fa-print text-danger"></i> Print</button>
            </div>
        </div>
     </div>
     <!---print page-area--->


</div> <!---end container--->

</body>
</html>
