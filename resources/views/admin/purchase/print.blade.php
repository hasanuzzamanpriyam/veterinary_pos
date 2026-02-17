@extends('layouts.admin')

@section('page-title')
Purchase Invoice
@endsection

@section('main-content')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">
            {{-- <div class="header-title d-flex align-items-center gap-2">
                <h2>Checkout</h2>
                <a href="{{ route('purchase.index') }}" class="mr-auto ml-3 cursor-pointer"><i class="fa fa-close"></i></a>
                <a href="#" onclick="MyWindow=window.open('#','MyWindow','width=900,height=600'); return false;" class="btn btn-primary btn-sm p-2">Print <i class="fa fa-print text-white"></i></a>
            </div> --}}
        </div>

        <div class="x_content" style="max-width: 720px; margin: 0 auto; float: unset;">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h2 class="text-center text-dark">Invoice #{{$supplier_info->id}}</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 product_list_table">
                    <div class="product-list-area">
                        <table class="table table-striped table-bordered table-sm">
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
                                    <td>{{date('d-m-Y', strtotime($supplier_info->date))}}</td>
                                    <td>{{$supplier_info->supplier->company_name}}</td>
                                    <td>{{$supplier_info->supplier->address}}</td>
                                    <td>{{$supplier_info->supplier->mobile}}</td>

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
                                    <td>{{$supplier_info->warehouse->name}}</td>
                                    <td>{{$supplier_info->transport_no}}</td>
                                    <td>{{$supplier_info->delivery_man}}</td>
                                    <td>{{$supplier_info->supplier_remarks}}</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 product_list_table">
                    <div class="product-list-area">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    @if($supplier_info->product_discount > 0)
                                    <th>Dis.(Qty)</th>
                                    @endif
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

                                    <td  class="text-center p-1">{{$product->product_code}}</td>
                                    <td  class="text-left p-1">{{$product->product_name}}</td>
                                    <td class="text-right p-1">{{$product->product_quantity}} {{ trans_choice('labels.'.$product->product->type, $product->product_quantity) }}</td>
                                    @if($supplier_info->product_discount > 0)
                                    <td class="text-right p-1">{{$product->product_discount}} {{ trans_choice('labels.'.$product->product->type, $product->product_discount) }}</td>
                                    @endif
                                    <td class="text-right p-1">{{$product->product_quantity-$product->product_discount}} {{ trans_choice('labels.'.$product->product->type, $product->product_quantity-$product->product_discount) }} </td>
                                    <td class="text-right p-1">{{$product->product_price}}/=</td>
                                    <td class="text-right p-1">{{$product->sub_total}}/=</td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            Not Found!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="calculation-area d-flex justify-content-end">
                        <table class="calculation_below_table">
                            <tr><th>Total Price</th><td>{{$supplier_info->total_price}}/=</td></tr>
                            @if(!empty($supplier_info->price_discount))
                            <tr><th>Discount</th><td>{{$supplier_info->price_discount ?? 0}}/=</td></tr>
                            @endif
                            @if(!empty($supplier_info->vat))
                            <tr><th>Vat</th><td>{{$supplier_info->vat ?? 0}}/=</td></tr>
                            @endif
                            @if(!empty($supplier_info->carring))
                            <tr><th>Carring</th><td>{{$supplier_info->carring ?? 0}}/=</td></tr>
                            @endif
                            @if(!empty($supplier_info->other_charge))
                            <tr><th>Others</th><td>{{$supplier_info->other_charge ?? 0}}/=</td></tr>
                            @endif
                            @if(!empty($supplier_info->old_due))
                            <tr><th>Previous Due</th><td>{{$supplier_info->old_due ?? 0}}/=</td></tr>
                            @endif
                            {{-- @dump($supplier_info) --}}
                            <tr><th>Grand Total</th><td class="grand-total">{{$supplier_info->old_due+$supplier_info->total_price-($supplier_info->price_discount+$supplier_info->vat+$supplier_info->carring+$supplier_info->other_charge)}}/=</td></tr>
                            <tr><th>Previous Due</th><td class="previous-due">{{$supplier_info->previous_due}}/=</td></tr>
                            <tr><th>Total Due</th><td>{{$supplier_info->previous_due + $supplier_info->total_price}}/=</td></tr>
                            <tr><th>Payment Amount</th><td>{{$supplier_info->payment ?? 0}}/=</td></tr>
                            <tr><th>Due Amount</th><td>{{$total = $supplier_info->current_due}}/=</td></tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="in-word-area py-3">
                        <h4 class="text-left text-dark">In Words: {{numberToWords($total)}}</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="bottom-area py-3">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="customer-signature-area">
                                <h4 class="text-left text-dark">Customer Signature</h4>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="thanks-area">
                                <h5 class="text-center text-dark">Thanks will come again</h5>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="supplier-signature-area">
                                <h4 class="text-center text-dark">Supplier Signature</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
