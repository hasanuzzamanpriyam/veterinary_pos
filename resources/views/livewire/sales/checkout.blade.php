@section('page-title', 'Sales Entry Checkout')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Checkout</h2>
                <a href="#" wire:click="cancel()" class="mr-auto ml-3 cursor-pointer"><i class="fa fa-close"></i></a>
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
            <form wire:submit.prevent="salesStore()" enctype="multipart/form-data" data-parsley-validate
                        class="form-horizontal form-label-left">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12">

                        @csrf
                        <!--Start supplier area-->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <h5 class="x_title text-center text-dark">Customer Info</h5>
                                @php
                                    // $balance = 0;
                                    // dd($customer);
                                    $current_customer_data = isset($customer[$customer_id]) ? $customer[$customer_id] : null;
                                @endphp
                                @if ($current_customer_data)
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Customer Name </th>
                                                <th>Address</th>
                                                <th>Mobile No.</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td>{{ date('d-m-Y', strtotime($current_customer_data['date'])) }}</td>
                                                <td>{{ $current_customer_data['customer_name'] }}</td>
                                                <td>{{ $current_customer_data['address'] }}</td>
                                                <td>{{ $current_customer_data['mobile'] }}</td>
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
                                                <td>{{ $current_customer_data['product_store_name'] }}</td>
                                                <td>{{ $current_customer_data['transport_no'] }}</td>
                                                <td>{{ $current_customer_data['delivery_man'] }}</td>
                                                <td>{{ $current_customer_data['remarks'] }}</td>
                                            </tr>
                                        </tbody>

                                    </table>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <!--End supplier area-->
                            {{-- Start Purchase Total Amount Area --}}

                            @php
                                $total_discounts = 0;
                            @endphp
                            @forelse (Cart::instance('sales')->content() as $product)
                                @php
                                    $total_discounts += $product->options->discount;
                                @endphp
                            @endforeach
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <h5 class="x_title text-center text-dark">Products Info</h5>
                                <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="all">Code</th>
                                            <th class="all">Name</th>
                                            <th class="all">Quantity</th>
                                            @if($total_discounts > 0)
                                            <th class="all">Discount</th>
                                            @endif
                                            <th class="all">Sales (Qty)</th>
                                            <th class="all">Price Rate</th>
                                            <th class="all">Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $type = 0;
                                            $total_amount = 0;
                                            $total_sales = 0;
                                            $total_qty = 0;
                                            $items = 0;
                                        @endphp
                                        @forelse (Cart::instance('sales')->content() as $product)
                                            @php
                                                $type = $product->options->type;

                                                $total_amount +=
                                                    ($product->qty - $product->options->discount) * $product->price;
                                                $total_sales += $product->qty - $product->options->discount;
                                                $total_qty += $product->qty;
                                                $items++;
                                                $summary['qty'][$type] = $summary['qty'][$type] ?? 0;
                                                $summary['discount'][$type] = $summary['discount'][$type] ?? 0;
                                                $summary['total'][$type] = $summary['total'][$type] ?? 0;
                                                $summary['qty'][$type] += $product->qty;
                                                $summary['discount'][$type] += $product->options->discount;
                                                $summary['total'][$type] += $product->qty - $product->options->discount;
                                            @endphp
                                            <tr>
                                                <td>{{ $product->options->code }}</td>
                                                <td>{{ $product->name }}</td>
                                                <td class="text-center">{{ $product->qty }} {{ trans_choice('labels.' . $product->options->type, $product->qty) }}</td>
                                                @if($total_discounts > 0)
                                                <td class="text-center">{{ $product->options->discount }} {{ trans_choice('labels.' . $product->options->type, $product->options->discount) }}</td>
                                                @endif
                                                <td class="text-center">{{ $product->qty - $product->options->discount }} {{ trans_choice('labels.' . $product->options->type, ($product->qty - $product->options->discount)) }}</td>
                                                <td class="text-right">{{ $product->price }}/=</td>
                                                <td class="text-right">{{ ($product->qty - $product->options->discount) * $product->price }}/=</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td>No Product Found!</td>
                                            </tr>
                                        @endforelse

                                    </tbody>
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
                                        @if($total_discounts > 0)
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
                    <h5 class="x_title text-center text-dark">Amount Calculation</h5>
                    <div class="px-3">
                        <table class="table table-striped table-bordered">
                            <tr class="text-right">
                                <th>Total Sales</th>
                                <td>{{ $total_amount }}/=</td>
                            </tr>
                            <tr class="text-right">
                                <th>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                            data-toggle="dropdown">
                                            Discount <span class="caret"></span></button>
                                        <div class="dropdown-menu small-dp-menu" role="menu">
                                            <div class="">
                                                <input type="number" wire:model.lazy="price_discount" class="form-control" placeholder="Discount" />

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
                                <td>{{ $total_discount }}/=</td>
                            </tr>
                            <tr class="text-right">
                                <th>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                            data-toggle="dropdown">
                                            VAT <span class="caret"></span></button>
                                        <div class="dropdown-menu small-dp-menu" role="menu">
                                            <div class="">
                                                <input type="number" wire:model.lazy="vat_discount" class="form-control" placeholder="VAT" />

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
                                <td>{{ $total_vat }}/=</td>
                            </tr>
                            <tr class="text-right has-input">
                                <th>Carring</th>
                                <td> <input type="number" wire:keyup="otherCharge('carring', $event.target.value)" wire:change="otherCharge('carring', $event.target.value)" name="carring"
                                        class="form-control">
                                </td>
                            </tr>
                            <tr class="text-right has-input">
                                <th>Other Charge</th>
                                <td> <input type="number" wire:keyup="otherCharge('other_charge', $event.target.value)" wire:change="otherCharge('other_charge', $event.target.value)" name="other_charge"
                                        class="form-control"></td>
                            </tr>
                            <tr class="text-right">
                                <th>Sub Total</th>
                                <td>{{ $grand_total . '/=' ?? '' }}</td>
                            </tr>

                            <tr class="text-right">
                                <th>@if ($balance >= 0)
                                    Previous Due

                                    @else
                                    Advance Collection
                                @endif</th>
                                <td>{{ $prev_balance . '/=' ?? '' }}</td>
                            </tr>
                            <tr class="text-right">
                                <th>Grand Total</th>
                                <td>{{ $grand_total + $prev_balance . '/=' ?? '' }}</td>
                            </tr>

                            {{-- <tr><th>Previous Due<td>= {{$previous_due  .'/=' ?? ''}}</td></th></tr> --}}
                            {{-- <tr><th>Advance Payment<td>= {{$advance_pay  .'/=' ?? ''}}</td></th></tr> --}}

                            <tr class="text-right has-input">
                                <th>Received By</th>
                                <td>
                                    @if (isset($bank_title))
                                    @else
                                        <select type="text" wire:model="payment_by"
                                            wire:change="paymentSearch($event.target.value)" name="payment_by"
                                            class="form-control">
                                            <option value="">Select Option</option>
                                            @foreach ($payment_types as $payment_type)
                                                <option value="{{ $payment_type }}">{{ $payment_type }}</option>
                                            @endforeach
                                        </select>

                                    @endif

                                    @if (isset($bank_list))
                                        @if ($bank_list == 1)
                                            <select type="text" wire:model="bank_title"
                                                wire:change="paymentSearch($event.target.value)" name="payment_by"
                                                class="form-control">
                                                <option value="">Select Option</option>
                                                @foreach ($banks as $bank)
                                                    <option value="{{ $bank->title }}">{{ $bank->title }}</option>
                                                @endforeach
                                            </select>
                                        @elseif($bank_list == 2)
                                            <input type="text" wire:model="bank_title" class="form-control">
                                        @else
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            {{-- <tr><th>Current Due<td>= </td></th></tr> --}}
                            <tr class="text-right has-input">
                                <th>Remarks</th>
                                <td><input type="text" wire:model.lazy="received_by" name="received_by" class="form-control">
                                </td>
                            </tr>
                            <tr class="text-right has-input">
                                <th>Collection</th>
                                <td><input type="number" wire:keyup="otherCharge('payment', $event.target.value)" wire:change="otherCharge('payment', $event.target.value)" name="payment" class="form-control">
                                </td>
                            </tr>
                            <tr class="text-right">
                                <th>@if ($balance >= 0)
                                    Due Amount

                                    @else
                                    Advance Collection
                                @endif</th>
                                <td>{{ $balance }}/=</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <div class="row">
                        <div class="ln_solid"></div>
                        <div class="form-group col-md-12">
                            <div class="input-group justify-content-center d-flex" style="gap: 10px">
                                <button type="button" wire:click="back" class="btn btn-primary btn-md">Back</button>
                                <button type="button" wire:click="cancel" class="btn btn-danger btn-md">Cancel</button>
                                <input type="submit" value="Submit" class="btn btn-success btn-md">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12"></div>
            </div>
            </form>
        </div>
    </div>
</div>
