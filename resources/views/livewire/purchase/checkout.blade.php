@section('page-title', 'Purchase Checkout')

<div class="container-fluid">
    <div class="row">
        <!-- Supplier & Products: Full Width -->
        <div class="col-12">
            <!-- Supplier Info Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Supplier Info</h5>
                </div>
                <div class="card-body">
                    @if($supplier)
                        @php
                            $value = $supplier;
                            $previous_due = $value['balance'];
                        @endphp
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Date:</strong> {{ date('d-m-Y', strtotime($value['date'])) }}</p>
                                <p><strong>Supplier:</strong> {{ $value['supplier_name'] }}</p>
                                <p><strong>Address:</strong> {{ $value['address'] }} {{ $value['mobile'] }}</p>
                                <p><strong>Warehouse:</strong> {{ $value['warehouse_name'] }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Store:</strong> {{ $value['product_store_name'] }}</p>
                                <p><strong>Gari Number:</strong> {{ $value['transport_no'] }}</p>
                                <p><strong>Delivery Men:</strong> {{ $value['delivery_man'] }}</p>
                                <p><strong>Remarks:</strong> {{ $value['supplier_remarks'] }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Products Table Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Products Info</h5>
                </div>
                <div class="card-body p-0">
                    @php
                        $product_discounts = 0;
                        $total_qty = 0;
                        $total_purchase = 0;
                        $total_amount = 0;
                        $items = 0;
                        $summary = [];
                        $tfoot_total_discount = 0;
                        $tfoot_net_amount = 0;
                        $total_line_value = 0;
                        $total_line_discount = 0;
                        $total_line_vat = 0;
                    @endphp
                    @forelse (Cart::instance('purchase')->content() as $product)
                        @php
                            $total_purchase += $product->qty - $product->options->discount;
                            $product_discounts += $product->options->discount;
                            $total_qty += $product->qty;
                            
                            $line_val = ($product->qty - $product->options->discount) * $product->price;
                            $line_dis = $product->options->item_discount ?: 0;
                            $line_vat = $product->options->item_vat ?: 0;
                            $total_line_value += $line_val;
                            $total_line_discount += $line_dis;
                            $total_line_vat += $line_vat;
                            $total_amount += $line_val - $line_dis + $line_vat;

                            $type = $product->options->type;
                            $items++;
                            $summary['qty'][$type] = ($summary['qty'][$type] ?? 0) + $product->qty;
                            $summary['discount'][$type] = ($summary['discount'][$type] ?? 0) + $product->options->discount;
                            $summary['total'][$type] = ($summary['total'][$type] ?? 0) + ($product->qty - $product->options->discount);
                        @endphp
                    @empty
                        <div class="p-3">No Product Found!</div>
                    @endforelse

                    @php
                        $single_discount = $total_qty > 0 ? ($total_discount / $total_qty) : 0;
                    @endphp

                    @if(count(Cart::instance('purchase')->content()) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped mb-0" style="table-layout: fixed; width: 100%; border-collapse: collapse; text-align: center;">
                                <colgroup>
                                    <col style="width: 110px;">
                                    <col>
                                    <col style="width: 140px;">
                                    @if($product_discounts > 0)
                                        <col style="width: 120px;">
                                    @endif
                                    <col style="width: 120px;">
                                    <col style="width: 130px;">
                                    <col style="width: 120px;">
                                    <col style="width: 120px;">
                                    <col style="width: 100px;">
                                    <col style="width: 130px;">
                                </colgroup>
                                <thead class="thead-light">
                                    <tr>
                                        <th style="white-space: nowrap; vertical-align: middle; text-align: center;">Code</th>
                                        <th style="white-space: nowrap; vertical-align: middle; text-align: center;">Product Name</th>
                                        <th style="white-space: nowrap; vertical-align: middle; text-align: center;">Purchase (Qty)</th>
                                        @if($product_discounts > 0)
                                            <th style="white-space: nowrap; vertical-align: middle; text-align: center;">Discount</th>
                                        @endif
                                        <th style="white-space: nowrap; vertical-align: middle; text-align: center;">Quantity</th>
                                        <th style="white-space: nowrap; vertical-align: middle; text-align: center;">Price Rate</th>
                                        <th style="white-space: nowrap; vertical-align: middle; text-align: center;">Value</th>
                                        <th style="white-space: nowrap; vertical-align: middle; text-align: center;">Discount</th>
                                        <th style="white-space: nowrap; vertical-align: middle; text-align: center;">VAT</th>
                                        <th style="white-space: nowrap; vertical-align: middle; text-align: center;">Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse (Cart::instance('purchase')->content() as $product)
                                        <tr>
                                            <td style="vertical-align: middle; text-align: center; overflow: hidden;">
                                                @if($product->options->barcode)
                                                    <svg class="barcode-render" data-barcode="{{ $product->options->barcode }}"
                                                        style="height: 25px; width: 100px; display: inline-block;"></svg>
                                                @endif
                                            </td>
                                            <td style="vertical-align: middle; text-align: center;">{{ $product->name }}</td>
                                            <td style="vertical-align: middle; text-align: center; white-space: nowrap;">{{ $product->qty - $product->options->discount }} {{ trans_choice($product->options->type, $product->qty - $product->options->discount) }}</td>
                                            @if($product_discounts > 0)
                                                <td style="vertical-align: middle; text-align: center; white-space: nowrap;">{{ $product->options->discount }} {{ trans_choice($product->options->type, $product->options->discount) }}</td>
                                            @endif
                                            <td style="vertical-align: middle; text-align: center; white-space: nowrap;">{{ $product->qty }} {{ trans_choice($product->options->type, $product->qty) }}</td>
                                            <td style="vertical-align: middle; text-align: center; white-space: nowrap;">{{ number_format($product->price, 2) }}/=</td>
                                            @php
                                                $line_val = ($product->qty - $product->options->discount) * $product->price;
                                                $line_dis = $product->options->item_discount ?: 0;
                                                $line_vat = $product->options->item_vat ?: 0;
                                                $line_subtotal = $line_val - $line_dis + $line_vat;
                                            @endphp
                                            <td style="vertical-align: middle; text-align: center; white-space: nowrap;">{{ number_format($line_val, 2) }}</td>
                                            <td style="vertical-align: middle; text-align: center; white-space: nowrap;">{{ number_format($line_dis, 2) }}</td>
                                            <td style="vertical-align: middle; text-align: center; white-space: nowrap;">{{ number_format($line_vat, 2) }}</td>
                                            <td style="vertical-align: middle; text-align: center; white-space: nowrap;">{{ number_format($line_subtotal, 2) }}/=</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="font-weight-bold">
                                    <tr>
                                        <td style="text-align: center; vertical-align: middle;"><strong>{{ trans_choice('labels.items', $items) }}:</strong> {{ $items }}</td>
                                        <td style="text-align: center; vertical-align: middle;"></td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            @foreach($summary['total'] ?? [] as $key => $value)
                                                <span class="d-inline-block mr-2"><strong>{{ $value }}</strong> {{ trans_choice(strtolower($key), $value) }}</span>
                                            @endforeach
                                        </td>
                                        @if($product_discounts > 0)
                                            <td style="text-align: center; vertical-align: middle;">
                                                @foreach($summary['discount'] ?? [] as $key => $value)
                                                    <span class="d-inline-block mr-2"><strong>{{ $value }}</strong> {{ trans_choice(strtolower($key), $value) }}</span>
                                                @endforeach
                                            </td>
                                        @endif
                                        <td style="text-align: center; vertical-align: middle;">
                                            @foreach($summary['qty'] ?? [] as $key => $value)
                                                <span class="d-inline-block mr-2"><strong>{{ $value }}</strong> {{ trans_choice(strtolower($key), $value) }}</span>
                                            @endforeach
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;"></td>
                                        <td style="text-align: center; vertical-align: middle;"><strong>{{ number_format($total_line_value, 2) }}</strong></td>
                                        <td style="text-align: center; vertical-align: middle;"><strong>{{ number_format($total_line_discount, 2) }}</strong></td>
                                        <td style="text-align: center; vertical-align: middle;"><strong>{{ number_format($total_line_vat, 2) }}</strong></td>
                                        <td style="text-align: center; vertical-align: middle;">{{ number_format($total_amount, 2) }}/=</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Amount Calculation: Full Width -->
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Amount Calculation</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form wire:submit.prevent="purchaseStore()" enctype="multipart/form-data" data-parsley-validate>
                        @csrf
                        <div class="row">
                            {{-- Col 1: Totals --}}
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="d-flex justify-content-between border-bottom pb-1 mb-2">
                                    <strong>Total Purchase</strong>
                                    <span>{{ number_format($total_amount, 2) }}/=</span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom pb-1 mb-2">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0" data-toggle="dropdown">
                                            Discount <span class="caret"></span>
                                        </button>
                                        <div class="dropdown-menu small-dp-menu p-2" style="min-width: 200px;">
                                            <input type="text" wire:model.lazy="price_discount" class="form-control form-control-sm mb-2" placeholder="Discount" />
                                            <div class="d-flex justify-content-between">
                                                <label class="mb-0"><input type="radio" name="discount" wire:click="discountType(1)"> Fix</label>
                                                <label class="mb-0"><input type="radio" name="discount" wire:click="discountType(2)"> % Per</label>
                                            </div>
                                        </div>
                                    </div>
                                    <span>{{ number_format($total_discount, 2) }}/=</span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom pb-1 mb-2">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0" data-toggle="dropdown">
                                            VAT <span class="caret"></span>
                                        </button>
                                        <div class="dropdown-menu small-dp-menu p-2" style="min-width: 200px;">
                                            <input type="text" wire:model.lazy="vat_discount" class="form-control form-control-sm mb-2" placeholder="VAT" />
                                            <div class="d-flex justify-content-between">
                                                <label class="mb-0"><input type="radio" name="vat" wire:click="vatType(1)"> Fix</label>
                                                <label class="mb-0"><input type="radio" name="vat" wire:click="vatType(2)"> % Per</label>
                                            </div>
                                        </div>
                                    </div>
                                    <span>{{ number_format($total_vat, 2) }}/=</span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom pb-1 mb-2">
                                    <strong>Total Tk</strong>
                                    <span>{{ number_format($total_tk, 2) }}/=</span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom pb-1 mb-2">
                                    <strong>Previous Due</strong>
                                    <span>{{ number_format($previous_due ?? 0, 2) }}/=</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <strong>Current Due</strong>
                                    <span>{{ number_format($total_tk + ($previous_due ?? 0), 2) }}/=</span>
                                </div>
                            </div>

                            {{-- Col 2: Carrying & Other Charge --}}
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="form-group">
                                    <label><strong>Carrying</strong></label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" wire:model.lazy="carring" name="carring" class="form-control">
                                        <div class="input-group-append">
                                            <span class="input-group-text">/=</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><strong>Other Charge</strong></label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" wire:model.lazy="other_charge" name="other_charge" class="form-control">
                                        <div class="input-group-append">
                                            <span class="input-group-text">/=</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Col 3: Payment Type & Remarks --}}
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="form-group">
                                    <label><strong>Payment Type</strong></label>
                                    @if(isset($bank_title))
                                        {{-- bank title display --}}
                                    @else
                                        <select wire:model="payment_by" wire:change="paymentSearch($event.target.value)" name="payment_by" class="form-control form-control-sm">
                                            <option value="">Select Option</option>
                                            @foreach($payment_types as $payment_type)
                                                <option value="{{ $payment_type }}">{{ $payment_type }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                    @if(isset($bank_list))
                                        @if($bank_list == 1)
                                            <select wire:model="bank_title" wire:change="paymentSearch($event.target.value)" name="payment_by" class="form-control form-control-sm mt-1">
                                                <option value="">Select Option</option>
                                                @foreach($banks as $bank)
                                                    <option value="{{ $bank->title }}">{{ $bank->title }}</option>
                                                @endforeach
                                            </select>
                                        @elseif($bank_list == 2)
                                            <input type="text" wire:model="bank_title" class="form-control form-control-sm mt-1">
                                        @endif
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label><strong>Remarks</strong></label>
                                    <input type="text" wire:model.lazy="payment_remarks" name="payment_remarks" class="form-control form-control-sm">
                                </div>
                            </div>

                            {{-- Col 4: Payment & Totals --}}
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="form-group">
                                    <label><strong>Payment</strong></label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" wire:model.lazy="payment" name="payment" class="form-control">
                                        <div class="input-group-append">
                                            <span class="input-group-text">/=</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between border-top pt-2 mb-1 font-weight-bold">
                                    <span>Total Payment</span>
                                    <span>{{ number_format($grand_total, 2) }}/=</span>
                                </div>
                                <div class="d-flex justify-content-between font-weight-bold">
                                    <span>Due Amount</span>
                                    <span>{{ number_format($balance, 2) }}/=</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-secondary btn-sm" wire:click="back">Back</button>
                            <button type="button" class="btn btn-danger btn-sm" wire:click="cancel">Cancel</button>
                            <button type="submit" class="btn btn-success btn-sm">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
</div>