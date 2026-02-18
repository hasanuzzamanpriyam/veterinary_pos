@section('page-title', 'Purchase Checkout')
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Checkout</h2>
                <a href="{{route('purchase.index', ['view' => 'v1'])}}" class="mr-auto ml-3 cursor-pointer"><i class="fa fa-close"></i></a>
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

            <form wire:submit.prevent="purchaseStore()" enctype="multipart/form-data"  data-parsley-validate class="form-horizontal form-label-left">
                @csrf
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-12">
                            <!--Start supplier area-->
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <h5 class="x_title text-center">Supplier Info</h5>
                                    @php
                                        $previous_due = 0;
                                        //$advance_pay = 0;
                                    @endphp
                                    @if($supplier)
                                        @php
                                            $value = $supplier;
                                            $previous_due = $value['balance'];
                                        @endphp
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Supplier Name</th>
                                                    <th>Address</th>
                                                    <th>Warehouse</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td>{{date('d-m-Y', strtotime($value['date']))}}</td>
                                                    <td>{{$value['supplier_name']}}</td>
                                                    <td>{{$value['address']}} {{$value['mobile']}}</td>
                                                    <td>{{$value['warehouse_name']}}</td>
                                                </tr>
                                            </tbody>
                                            <thead>
                                                <tr>
                                                    <th>Store</th>
                                                    <th>Gari Number</th>
                                                    <th>Delivery Men</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td>{{$value['product_store_name']}}</td>
                                                    <td>{{$value['transport_no']}}</td>
                                                    <td>{{$value['delivery_man']}}</td>
                                                    <td>{{$value['supplier_remarks']}}</td>
                                                </tr>
                                            </tbody>


                                            {{-- <tr><th>Total Quantity<td>= {{$value->address}}</td></th></tr> --}}

                                        </table>
                                        {{-- @foreach (Session::get('supplier') as $value)


                                        @endforeach --}}
                                    @endif


                                </div>
                            </div>

                            @php
                                $product_discounts = 0;
                            @endphp
                            @forelse (Cart::instance('purchase')->content() as $product)
                                @php
                                    $product_discounts+=$product->options->discount;
                                @endphp
                            @endforeach
                            <div class="row">
                                <!--End supplier area-->
                                {{--Start Purchase Total Amount Area --}}
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <h5 class="x_title text-center">Products Info</h5>
                                    <table  class="table table-striped table-bordered" cellspacing="0" width="100%" >
                                        <thead>
                                            <tr class="text-center">
                                                <th class="all">Code</th>
                                                <th class="all">Name</th>
                                                <th class="all">Quantity</th>
                                                @if($product_discounts > 0)
                                                <th class="all">Discount</th>
                                                @endif
                                                <th class="all">Purchase (Qty)</th>
                                                <th class="all">Price Rate</th>
                                                <th class="all">Sub Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $product_discounts = 0;
                                                $total_qty = 0;
                                                $total_purchase = 0;
                                                $total_amount = 0;
                                                $type = 0;
                                                $items = 0;
                                            @endphp
                                            @forelse (Cart::instance('purchase')->content() as $product)
                                                @php
                                                    $total_purchase+=$product->qty-$product->options->discount;
                                                    $product_discounts+=$product->options->discount;
                                                    $total_qty+=$product->qty;
                                                    $total_amount += ($product->qty-$product->options->discount)*$product->price;
                                                    $type = $product->options->type;
                                                    $items++;
                                                    $summary['qty'][$type] = $summary['qty'][$type] ?? 0;
                                                    $summary['discount'][$type] = $summary['discount'][$type] ?? 0;
                                                    $summary['total'][$type] = $summary['total'][$type] ?? 0;
                                                    $summary['qty'][$type] += $product->qty;
                                                    $summary['discount'][$type] += $product->options->discount;
                                                    $summary['total'][$type] += $product->qty - $product->options->discount;
                                                @endphp
                                                <tr class="text-right">
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
                                                    <td>{{$product->qty}} {{trans_choice('labels.'. $product->options->type, $product->qty)}}</td>
                                                    @if($product_discounts > 0)
                                                    <td>{{$product->options->discount}} {{trans_choice('labels.'. $product->options->type, $product->options->discount)}}</td>
                                                    @endif
                                                    <td>{{$product->qty - $product->options->discount}} {{trans_choice('labels.'. $product->options->type, ( $product->qty - $product->options->discount ))}}</td>
                                                    <td class="text-right">{{$product->price}}/=</td>
                                                    <td class="text-right">{{($product->qty-$product->options->discount)*$product->price}}/=</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td>No Product Found!</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        {{-- <tr>
                                            <td colspan="3" class="text-right font-weight-bold">Total = {{$total_qty}} {{trans_choice('labels.'. $type, $total_qty)}}</td>
                                            <td colspan="1" class="text-right font-weight-bold">Total = {{$product_discounts}} {{trans_choice('labels.'. $type, $product_discounts)}}</td>
                                            <td colspan="1" class="text-right font-weight-bold">Total = {{$total_purchase}} {{trans_choice('labels.'. $type, $total_purchase)}}</td>
                                            <td colspan="2" class="text-right font-weight-bold">Total = {{$total_amount}}/=</td>
                                        </tr> --}}
                                        <tr class="font-weight-bold">
                                            <td>
                                                <div class="d-flex justify-content-start">
                                                    <span><strong>{{trans_choice('labels.items', $items)}}:</strong>
                                                        {{ $items }}</span>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td>
                                                <div>
                                                    @if( isset($summary['qty']) && $summary['qty'] > 0)
                                                        @foreach ($summary['qty'] as $key => $value)
                                                            <span class="d-inline-block"><strong>{{ $value }}</strong> <span class="ttl">{{trans_choice('labels.'.strtolower($key), $value)}}</span></span>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                            @if($product_discounts > 0)
                                            <td>
                                                <div>
                                                    @if( isset($summary['discount']) && $summary['discount'] > 0)
                                                        @foreach ($summary['discount'] as $key => $value)
                                                            <span class="d-inline-block"><strong>{{ $value }}</strong> <span class="ttl">{{trans_choice('labels.'.strtolower($key), $value)}}</span></span>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                            @endif
                                            <td>
                                                <div>
                                                    @if( isset($summary['total']) && $summary['total'] > 0)
                                                        @foreach ($summary['total'] as $key => $value)
                                                            <span class="d-inline-block"><strong>{{ $value }}</strong> <span class="ttl">{{trans_choice('labels.'.strtolower($key), $value)}}</span></span>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                            <td></td>
                                            <td colspan="1" class="text-right">{{ $total_amount }}/=</td>
                                        </tr>
                                    </table>
                                </div>
                                {{-- End Purchase Total Amount Area --}}
                            </div>
                    </div>
                    <div class="cart-total col-lg-4 col-md-4 col-sm-12">
                        <h5 class="x_title text-center">Amount Calculation</h5>
                        <div class="px-3">
                            <table class="table table-striped table-bordered">
                                <tr class="text-right"><th class="text-left">Total Purchase</th><td class="text-right">{{formatAmount($total_amount)}}/=</td></tr>
                                {{-- <tr><th>Total Quantity<td>= {{$total_qty}}</td></th></tr> --}}
                                <tr class="text-right">
                                    <th class="text-left">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0"
                                                data-toggle="dropdown">
                                                Discount <span class="caret"></span></button>
                                            <div class="dropdown-menu small-dp-menu" role="menu">
                                                <div class="">
                                                    <input type="text" wire:model.lazy="price_discount" class="form-control" placeholder="Discount" />

                                                    <div class="input-group-text justify-content-between">
                                                        <label>
                                                            <input type="radio" name="discount" wire:click="discountType(1)"><span>Fix</span>
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="discount" wire:click="discountType(2)"><span>% Per</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                    <td class="text-right">{{formatAmount($total_discount)}}/=</td>
                                </tr>
                                <tr class="text-right">
                                    <th class="text-left">Total Tk</th>
                                    <td class="text-right"> {{formatAmount($total_tk) . '/=' ?? ''}}</td>
                                </tr>
                                <tr class="text-right">
                                    <th class="text-left">Previous Due</th>
                                    <td class="text-right">{{formatAmount($previous_due) ?? 0}}/=</td>
                                </tr>
                                <tr class="text-right">
                                    <th class="text-left">Current Due</th>
                                    <td class="text-right">{{formatAmount($total_tk+$previous_due)  .'/=' ?? ''}}</td>
                                </tr>
                                <tr class="text-right">
                                    <th class="text-left">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0"
                                                data-toggle="dropdown">
                                                VAT <span class="caret"></span></button>
                                            <div class="dropdown-menu small-dp-menu" role="menu">
                                                <div class="">
                                                    <input type="text" wire:model.lazy="vat_discount" class="form-control" placeholder="VAT" />

                                                    <div class="input-group-text justify-content-between">
                                                        <label>
                                                            <input type="radio" name="vat" wire:click="vatType(1)"><span>Fix</span>
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="vat" wire:click="vatType(2)"><span>% Per</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                    <td class="text-right">{{formatAmount($total_vat)}}/=</td>
                                </tr>
                                <tr class="text-right has-input">
                                    <th class="text-left">Carring</th>
                                    <td class="text-right">
                                        <div class="input-group">
                                            <input type="text" wire:model.lazy="carring" name="carring" class="form-control pr-2 py-0">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text py-0 px-2 fs-14">/=</div>
                                              </div>
                                          </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="text-right has-input">
                                    <th class="text-left">Other Charge</th>
                                    <td class="text-right">
                                        <div class="input-group">
                                            <input type="text" wire:model.lazy="other_charge" name="other_charge" class="form-control pr-2 py-0">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text py-0 px-2 fs-14">/=</div>
                                              </div>
                                          </div>
                                        </div>
                                    </td>
                                </tr>

                                {{-- <tr><th>Current Due<td>= {{$grand_total+$previous_due  .'/=' ?? ''}}</td></th></tr> --}}
                                <tr class="text-right has-input">
                                    <th class="text-left">Payment Type</th>
                                    <td class="text-right">
                                        @if(isset($bank_title))
                                        @else
                                            <select type="text" wire:model="payment_by" wire:change="paymentSearch($event.target.value)"  name="payment_by"  class="form-control">
                                                <option value="">Select Option</option>
                                                    @foreach($payment_types as $payment_type)
                                                        <option value="{{$payment_type}}">{{$payment_type}}</option>
                                                    @endforeach
                                            </select>
                                        @endif
                                            @if(isset($bank_list))
                                                @if($bank_list == 1)
                                                    <select type="text" wire:model="bank_title" wire:change="paymentSearch($event.target.value)"  name="payment_by"  class="form-control">
                                                        <option value="">Select Option</option>
                                                            @foreach($banks as $bank)
                                                                <option value="{{$bank->title}}">{{$bank->title}}</option>
                                                            @endforeach
                                                    </select>
                                                @elseif($bank_list == 2)
                                                    <input type="text" wire:model="bank_title" class="form-control">
                                                @else
                                            @endif
                                        @endif
                                    </td>

                                </tr>

                                <tr class="text-left has-input">
                                    <th class="text-left">Remarks</th>
                                    <td class="text-left"><input type="text"  wire:model.lazy="payment_remarks" name="payment_remarks" class="form-control"></td>
                                </tr>

                                <tr class="text-right has-input">
                                    <th class="text-left">Payment</th>
                                    <td class="text-right">
                                        <div class="input-group">
                                            <input type="text" wire:model.lazy="payment" name="payment" class="form-control pr-2 py-0">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text py-0 px-2 fs-14">/=</div>
                                              </div>
                                          </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="text-right">
                                    <th class="text-left">Total Payment</th>
                                    <td class="text-right">{{formatAmount($grand_total)}}/=</td>
                                </tr>
                                <tr class="text-right">
                                    <th class="text-left">Due Amount</th>
                                    <td class="text-right">{{formatAmount($balance)}}/=</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-12">
                        <div class="row">
                            <div class="item form-group col-md-12">
                                <div class="input-group justify-content-center d-flex" style="gap: 10px">
                                    <button type="button" class="btn btn-primary" wire:click="back" type="button">Back</button>
                                    <button type="button" class="btn btn-danger" wire:click="cancel" type="button">Cancel</button>
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

