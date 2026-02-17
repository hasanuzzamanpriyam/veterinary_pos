<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Purchase Return Invoice</title>
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
        <h5 class="text-center text-dark"><strong>Challan # {{ $supplier_ledger->id }}</strong></h5>
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
                                    <th>Return Date</th>
                                    <th>Supplier Name</th>
                                    <th>Address</th>
                                    <th>Mobile No</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-left p-0 comon_column">{{date('d-m-Y', strtotime($supplier_ledger->date))}}</td>
                                    <td class="text-left p-0 comon_column">{{$supplier_ledger->supplier->company_name}}</td>
                                    <td class="text-left p-0 comon_column">{{$supplier_ledger->supplier->address}}</td>
                                    <td class="text-left p-0 comon_column">{{$supplier_ledger->supplier->mobile ?? $supplier_ledger->supplier->phone ?? ''}}</td>

                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th>Store</th>
                                    <th>Warehouse</th>
                                    <th>Gari No/Delivery Man</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td class="text-left p-0 comon_column">{{$supplier_ledger->store->name}}</td>
                                    <td class="text-left p-0 comon_column">{{$supplier_ledger->warehouse->name}}</td>
                                    <td class="text-left p-0 comon_column">{{$supplier_ledger->transport_no}}{{$supplier_ledger->delivery_man ? ", " . $supplier_ledger->delivery_man : ''}}</td>
                                    <td class="text-left p-0 comon_column">{{$supplier_ledger->supplier_remarks}}</td>
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
                                    <th class="text-center">Return (Qty)</th>
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

                                    <td class="text-center p-1">{{$product->product_code}}</td>
                                    <td class="text-left p-1">{{$product->product_name}}</td>
                                    <td class="text-center p-1">{{$product->quantity}} {{ trans_choice('labels.'.$product->product->type, $product->quantity) }}</td>
                                    <td class="text-right p-1">{{number_format($product->unit_price)}}/=</td>
                                    <td class="text-right p-1">{{number_format($product->total_price)}}/=</td>
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
                                <!-- <h3 class="text-center text-dark">Billing Info</h3> -->
                               <!----- calculation-area----->
                               <table class="calculation_below_table">
                                    <tr><th>Total Price</th><td>{{number_format($supplier_ledger->total_price)}}/=</td></tr>
                                    @if($supplier_ledger->carring)
                                        <tr><th>Carring</th><td>{{number_format($supplier_ledger->carring)}}/=</td></tr>
                                    @endif
                                    @if ($supplier_ledger->other_charge)
                                        <tr><th>Others</th><td>{{number_format($supplier_ledger->other_charge)}}/=</td></tr>
                                    @endif
                                    @php
                                        $total = $supplier_ledger->total_price - ($supplier_ledger->carring+$supplier_ledger->other_charge);
                                    @endphp
                                    <tr><th>Grand Total</th><td><strong>{{number_format($total)}}/=</strong></td></tr>
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
                    <h6 class="text-left text-dark">In Words:  {{numberToWords($total)}}</h6>
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
                        <div class="thanks-area">
                            <h5 class="text-center text-dark text-center m-0"><small>Thank you for shopping with us</small></h5>
                        </div>
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
