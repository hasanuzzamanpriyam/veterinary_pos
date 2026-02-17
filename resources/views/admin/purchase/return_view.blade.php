@extends('layouts.admin')

@section('page-title')
Purchase Return View
@endsection

@section('main-content')
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Purchase Return Checkout</h2>
                <a href="{{ route('purchase.return.index') }}" class="mr-auto ml-3 cursor-pointer"><i class="fa fa-close"></i></a>
                <a href="{{ route('purchase.return.print', $supplier_info->id) }}"
                    onClick="MyWindow=window.open('{{ route('purchase.return.print', $supplier_info->id) }}','MyWindow','width=900,height=700'); return false;"
                    class="btn btn-primary btn-sm p-2">
                    Print <i class="fa fa-print text-white"></i>
                </a>
            </div>
        </div>

        <div class="x_content p-3" style="max-width: 720px; margin: 0 auto; float: unset;">
            <div class="row">
                <div class="col-lg-12 col-md-21 col-sm-12">
                    <h2 class="text-center text-dark">Invoice #{{$supplier_info->id}}</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="supplier-info-area">
                        <table class="table table-striped table-bordered table-sm">
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
                                    <td>{{date('d-m-Y', strtotime($supplier_info->date))}}</td>
                                    <td>{{$supplier_info->supplier->company_name}}</td>
                                    <td>{{$supplier_info->supplier->address}}</td>
                                    <td>{{$supplier_info->supplier->mobile ?? $supplier_info->supplier->phone ?? ''}}</td>
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
                                    <td>{{$supplier_info->store->name}}</td>
                                    <td>{{$supplier_info->warehouse->name}}</td>
                                    <td>{{$supplier_info->transport_no}}{{$supplier_info->delivery_man ? ", " . $supplier_info->delivery_man : ''}}</td>
                                    <td>{{$supplier_info->supplier_remarks}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="product-list-area">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th class="text-left p-1">Name</th>
                                    <th>Return (Qty)</th>
                                    <th class="text-right p-1">Price</th>
                                    <th class="text-right p-1">Sub Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $quantity = 0;
                                    $total = 0;
                                    $type = 0;
                                @endphp
                                @forelse ($products as $product)
                                @php
                                    $quantity +=$product->quantity;
                                    $total += $product->product_price*$product->quantity;
                                    $type =$product->product->type;
                                @endphp
                                <tr>
                                    <td class="text-center p-1">{{$product->product_code}}</td>
                                    <td class="text-left p-1">{{$product->product_name}}</td>
                                    <td class="text-center p-1">{{$product->quantity}} {{trans_choice('labels.'.$product->product->type, $product->quantity)}}</td>
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
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="calculation-area d-flex justify-content-end">
                        {{-- @dump($supplier_info) --}}
                        <table class="calculation_below_table">
                            <tr><th>Total Price</th><td>{{formatAmount($supplier_info->total_price)}}/=</td></tr>
                            @if($supplier_info->carring > 0)
                                <tr><th>Carring</th><td>{{formatAmount($supplier_info->carring)}}/=</td></tr>
                            @endif
                            @if ($supplier_info->other_charge > 0)
                                <tr><th>Others</th><td>{{formatAmount($supplier_info->other_charge)}}/=</td></tr>
                            @endif
                            @php
                                $total = $supplier_info->total_price - ($supplier_info->carring+$supplier_info->other_charge);
                            @endphp
                            <tr><th>Grand Total</th><td>{{formatAmount($total)}}/=</td></tr>

                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
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
                                        <h4 class="text-center text-dark">Supplier Signature</h4>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <div class="supplier-signature-area">
                                        <h4 class="text-center text-dark">Authorized Signature</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="bottom-area">
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <div class="thanks-area">
                                        <h5 class="text-center text-dark"><small>Thank you for shopping with us</small></h5>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
