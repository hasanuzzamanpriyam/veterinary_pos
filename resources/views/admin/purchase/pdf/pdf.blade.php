<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>


    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

<style>


body,html {
  font-family: sans-serif,Arial, Helvetica;
  border-collapse: collapse;
  width: 100%;
  font-weight: 300;
font-style: normal;
}
h5, h4, h3{
    text-align: center;
}
.table td, .table th
{
border: 1px solid #000;
padding: 8px;
}

.table th
{
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #ffffff;
  color: rgb(0, 0, 0);
}
.address-area,.address-table
{
    margin: 10px auto;
    width: 100%;
}

.product-list-area{
    width:68%;
    float: left;
}
.calculation-area{
    width:30%;
    float: right;
}

.billing-table td, .table th
{
border: 1px solid #000;
padding: 8px;
}

.billing-table th
{
  padding-top: 6px;
  padding-bottom: 6px;
  text-align: left;
  background-color: #ececf6;
  color: rgb(0, 0, 0);
}
.in-word-area h6{
    font-size: 12px;
}
.in-word-area h6
{
   border-bottom: 1px dotted;

}
.bottom-area
{
    width: 100%;
    margin-top: 45px;

}
.bottom-area
{
    width: 100%;
    margin-top: 35px;

}
.customer-signature-area
{
    width:30%;
    float: left;
}

.customer-signature-area
{
    display: inline;
    border-top: 1px solid #000;
}

.thanks-area
{
    width:40%;
    float: left;
}

.supplier-signature-area{
    width:30%;
    float: left;
}
.supplier-signature-area
{
    border-top: 1px solid #000;
}
</style>

<body>
    <div class="container">


        <div class="header-area">
            <div class="banner-area">
                <img src="{{ public_path('assets/images/firoz_header.jpg') }}" width="100%" height="120" alt="">
            </div>
            <h5 class="text-center text-dark">Challan/Cashmemo.</h5>
        </div>

        <div class="body-area">
            <div class="address-area">
                <h4 class="text-center text-dark">Supplier Info</h4>
                <table class="table table-striped table-bordered address-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Supplier Name</th>
                            <th>Address</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{date('d-m-Y', strtotime($supplier_info->date))}}</td>
                            <td>{{$supplier_info->supplier->company_name}}</td>
                            <td>{{$supplier_info->supplier->address}}</td>

                        </tr>
                    </tbody>
                    <thead>
                        <tr>
                            <th>Invoice No.</th>
                            <th>Warehouse</th>
                            <th>Gari Number - Delivery Men</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>{{$supplier_info->invoice_no}}</td>
                            <td>{{$supplier_info->warehouse->name}}</td>
                            <td>{{$supplier_info->transport_no}} : {{$supplier_info->delivery_man}}</td>

                        </tr>
                    </tbody>
                </table>
             </div>

            <div class="product-area">
                <div class="product-list-area">
                    <h4 class="text-center text-dark">Products List</h4>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Discount(Qty)</th>
                                <th>Purchase(Qty)</th>
                                <th>Price</th>
                                <th>Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $quantity = 0;
                                $discount = 0;
                                $purchase_qty = 0;
                                $total = 0;
                                $type = 0;
                            @endphp
                            @forelse ($products as $product)
                            @php
                            $quantity += $product->product_quantity;
                            $discount += $product->product_discount;
                            $purchase_qty += $product->product_quantity-$product->product_discount;
                            $total += $product->sub_total;
                            $type = $product->product->type;
                            @endphp
                            <tr>
                                <td  class="text-center p-0">{{$product->product_code}}</td>
                                <td  class="text-center p-0">{{$product->product_name}}</td>
                                <td class="text-right p-0">{{$product->product_quantity}} {{$product->product->type}}</td>
                                <td class="text-right p-0">{{$product->product_discount}} {{$product->product->type}}</td>
                                <td class="text-right p-0">{{$product->product_quantity-$product->product_discount}} {{$product->product->type}} </td>
                                <td class="text-right p-0">{{$product->product_price}}/=</td>
                                <td class="text-right p-0">{{$product->sub_total}}/=</td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        Not Found!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tr>
                            <td class="text-right" colspan="3">Total = {{$quantity}} {{ $type}}</td>
                            <td class="text-right" colspan="1">Total = {{$discount}} {{ $type}}</td>
                            <td class="text-right" colspan="1">Total = {{$purchase_qty}} {{ $type}}</td>
                            <td class="text-right" colspan="2">Total = {{$total}}/=</td>
                        </tr>
                    </table>
                </div>
                <div class="calculation-area">
                    <h4 class="text-center text-dark">Billing Info</h4>
                    <table class="table billing-table table-bordered">
                        <tr><th>Total Price</th><td>:{{$supplier_info->total_price}}/=</td></tr>
                        <tr><th>Discount</th><td>:{{$supplier_info->price_discount ?? 0}}/=</td></tr>
                        <tr><th>Vat</th><td>:{{$supplier_info->vat ?? 0}}/=</td></tr>
                        <tr><th>Carring</th><td>:{{$supplier_info->carring ?? 0}}/=</td></tr>
                        <tr><th>Others</th><td>:{{$supplier_info->other_charge ?? 0}}/=</td></tr>
                        <tr><th>Previous Due</th><td>:{{$supplier_info->old_due ?? 0}}/=</td></tr>
                        <tr><th>Grand Total</th><td>:{{$supplier_info->old_due+$supplier_info->total_price-($supplier_info->price_discount+$supplier_info->vat+$supplier_info->carring+$supplier_info->other_charge)}}/=</td></tr>
                        <tr><th>Payment Amount</th><td>:{{$supplier_info->payment ?? 0}}/=</td></tr>
                        <tr><th>Current Due</th><td>:{{$total = $supplier_info->total_price+$supplier_info->old_due-($supplier_info->price_discount+$supplier_info->vat+$supplier_info->carring+$supplier_info->other_charge+$supplier_info->payment)}}/=</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="footer-area">
            <div class="in-word-area">
                <h6 class="text-left text-dark">In Words: {{numberToWords($total)}}</h6>
            </div>

            <div class="bottom-area">
                <div class="customer-signature-area">
                    <h4 class="text-left text-dark">Customer Signature</h4>
                </div>
                <div class="thanks-area">
                    <h4 class="text-center text-dark">Thanks will come again</h4>
                </div>

                <div class="supplier-signature-area">
                    <h4 class="text-right text-dark">Supplier Signature</h4>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
