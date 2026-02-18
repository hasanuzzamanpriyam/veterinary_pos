@section('page-title', 'Purchase Return Checkout')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Purchase Return Checkout</h2>
                <a href="{{ route('purchase.return.index') }}" class="mr-auto ml-3 cursor-pointer"><i class="fa fa-close"></i></a>
            </div>
        </div>
        <div class="x_content p-3">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <form wire:submit.prevent="purchaseStore()" enctype="multipart/form-data"  data-parsley-validate class="form-horizontal form-label-left">
                        @csrf
                        <!--Start supplier area-->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <h5 class="x_title text-center">Supplier Info</h5>
                                @php
                                    $return_pre_due = 0;
                                @endphp
                                @if($supplier_info)
                                    @php
                                        $value = $supplier_info;
                                    @endphp
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Purchase Date</th>
                                                <th>Return Date </th>
                                                <th>Supplier Name</th>
                                                <th>Address</th>
                                                <th>Mobile No</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td>{{date('d-m-Y', strtotime($value['purchase_date']))}} </td>
                                                <td>{{date('d-m-Y', strtotime($value['return_date']))}} </td>
                                                <td>{{$value['supplier_name']}}</td>
                                                <td>{{$value['address']}}</td>
                                                <td>{{$value['mobile']}}</td>
                                            </tr>
                                        </tbody>
                                        <thead>
                                            <tr>
                                                <th>Invoice</th>
                                                <th>Store</th>
                                                <th>Warehouse</th>
                                                <th>Gari No/Delivery Man</th>
                                                <th>Remarks</th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td>{{$value['purchase_invoice_no']}}</td>
                                                <td>{{$value['product_store_name']}}</td>
                                                <td>{{$value['warehouse_name']}}</td>
                                                <td>{{$value['delivery_man']}}</td>
                                                <td class="text-wrap">{{$value['remarks']}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    {{-- @foreach ($supplier_info as $value)
                                    @endforeach --}}
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <!--End supplier area-->
                            {{--Start Purchase Total Amount Area --}}
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <h5 class="x_title text-center">Products List</h5>
                                <table  class="table table-striped table-bordered" cellspacing="0" width="100%" >
                                    <thead>
                                        <tr class="text-center">
                                            <th class="all">Code</th>
                                            <th class="all">Name</th>
                                            <th class="all">Purchase (Qty)</th>
                                            <th class="all">Return (Qty)</th>
                                            <th class="all">Price Rate</th>
                                            <th class="all">Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php

                                            $total_qty = 0;
                                            $total_amount = 0;
                                            $type = 0;
                                            $items = 0;
                                            $summary = [];

                                        @endphp
                                        @forelse (Cart::instance('purchase_return')->content() as $product)

                                            @php
                                                $items++;
                                                $total_qty+=$product->qty;
                                                $total_amount += ($product->qty-$product->options->discount)*$product->price;
                                                $type = $product->options->type;
                                                $summary['return'][$type] = $summary['return'][$type] ?? 0;
                                                $summary['purchase'][$type] = $summary['purchase'][$type] ?? 0;
                                                $summary['return'][$type] += $product->qty;
                                                $summary['purchase'][$type] += $product->options->purchased_qty;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column align-items-start">
                                                        <span>{{ $product->options->code }}</span>
                                                        @if($product->options->barcode)
                                                            <svg class="barcode-render" data-barcode="{{ $product->options->barcode }}"
                                                                style="height: 25px; margin-top: 4px; max-width: 100%;"></svg>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>{{$product->name}}</td>
                                                <td>{{formatAmount($product->options->purchased_qty)}} {{trans_choice('labels.'. $product->options->type, $product->options->purchased_qty)}}</td>
                                                <td>{{formatAmount($product->qty)}} {{trans_choice('labels.'. $product->options->type, $product->qty)}}</td>
                                                <td class="text-right">{{formatAmount($product->price)}}/=</td>
                                                <td class="text-right">{{formatAmount($product->qty*$product->price)}}/=</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td>No Product Found!</td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex justify-content-start">
                                                <span><strong>{{trans_choice('labels.items', $items)}}:</strong>
                                                    {{ $items }}</span>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td>
                                            <div>
                                                @if( isset($summary['purchase']) && $summary['purchase'] > 0)
                                                    @foreach ($summary['purchase'] as $key => $value)
                                                        <span class="d-inline-block"><strong>{{ formatAmount($value) }}</strong> <span class="ttl">{{trans_choice('labels.'.strtolower($key), $value)}}</span></span>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                @if( isset($summary['return']) && $summary['return'] > 0)
                                                    @foreach ($summary['return'] as $key => $value)
                                                        <span class="d-inline-block"><strong>{{ formatAmount($value) }}</strong> <span class="ttl">{{trans_choice('labels.'.strtolower($key), $value)}}</span></span>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </td>

                                        <td></td>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                <span><strong>TK:</strong>
                                                    {{ formatAmount($total_amount) }}/=</span>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            {{-- End Purchase Total Amount Area --}}
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="ln_solid"></div>
                                <div class="input-group justify-content-center d-flex" style="gap: 10px">
                                    <button type="button" class="btn btn-primary" wire:click="back" type="button">Back</button>
                                    <button type="button" class="btn btn-danger" wire:click="cancel" type="button">Cancel</button>
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="cart-total col-lg-4 col-md-4 col-sm-12">
                    <h5 class="x_title text-center">Amount Calculation</h5>
                        <div class="px-3">

                            <table class="table table-striped table-bordered">
                                <tr class="text-right"><th>Total Purchase<td>{{formatAmount(Cart::instance('purchase_return')->total()-Cart::instance('purchase_return')->tax())}}/=</td></th></tr>
                                <tr class="text-right has-input"><th>Carring<td> <input type="number" wire:model.lazy="carring" name="carring" class="form-control"></td></th></tr>
                                <tr class="text-right has-input"><th>Other Charge<td> <input type="number" wire:model.lazy="other_charge" name="other_charge" class="form-control"></td></th></tr>
                                <tr class="text-right"><th>Total<td>{{formatAmount($return_amount) . '/=' ?? ''}}</td></th></tr>
                            </table>
                            <input type="hidden" wire:model.lazy="purchase_invoice_no" name="purchase_invoice_no" class="form-control" value="{{$purchase_invoice_no}}">
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

