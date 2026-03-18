@section('page-title', 'Purchase Checkout')

<div class="container-fluid">
    <div class="row">
        <!-- Main Content: Supplier & Products -->
        <div class="col-lg-8 col-md-7 col-sm-12">
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
                    @endphp
                    @forelse (Cart::instance('purchase')->content() as $product)
                        @php
                            $total_purchase += $product->qty - $product->options->discount;
                            $product_discounts += $product->options->discount;
                            $total_qty += $product->qty;
                            $total_amount += ($product->qty - $product->options->discount) * $product->price;
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
                            <table class="table table-hover table-striped mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Code</th>
                                        <th>Product Name</th>
                                        <th>Purchase (Qty)</th>
                                        @if($product_discounts > 0)
                                            <th>Discount</th>
                                        @endif
                                        <th>Quantity</th>
                                        <th>Price Rate</th>
                                        <th>Sub Total</th>
                                        {{-- new table heads --}}
                                        <th>Single Discount</th>
                                        <th>Total Discount</th>
                                        <th>Net Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse (Cart::instance('purchase')->content() as $product)
                                        <tr>
                                            <td>
                                                @if($product->options->barcode)
                                                    <svg class="barcode-render" data-barcode="{{ $product->options->barcode }}"
                                                        style="height: 25px; max-width: 100%;"></svg>
                                                @endif
                                            </td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->qty - $product->options->discount }} {{ trans_choice($product->options->type, $product->qty - $product->options->discount) }}</td>
                                            @if($product_discounts > 0)
                                                <td>{{ $product->options->discount }} {{ trans_choice($product->options->type, $product->options->discount) }}</td>
                                            @endif
                                            <td>{{ $product->qty }} {{ trans_choice($product->options->type, $product->qty) }}</td>
                                            <td class="text-right">{{ number_format($product->price, 2) }}/=</td>
                                            <td class="text-right">{{ number_format(($product->qty - $product->options->discount) * $product->price, 2) }}/=</td>
                                            
                                            {{-- discount fields --}}
                                            @php
                                                $row_total_discount = $single_discount * $product->qty;
                                                $row_net_amount = (($product->qty - $product->options->discount) * $product->price) - $row_total_discount;
                                                $tfoot_total_discount += $row_total_discount;
                                                $tfoot_net_amount += $row_net_amount;
                                            @endphp
                                            <td class="text-right">{{ number_format($single_discount, 2) }}</td>
                                            <td class="text-right">{{ number_format($row_total_discount, 2) }}</td>
                                            <td class="text-right">{{ number_format($row_net_amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="font-weight-bold">
                                    <tr>
                                        <td><strong>{{ trans_choice('labels.items', $items) }}:</strong> {{ $items }}</td>
                                        <td></td>
                                        <td>
                                            @foreach($summary['total'] ?? [] as $key => $value)
                                                <span class="d-inline-block mr-2"><strong>{{ $value }}</strong> {{ trans_choice(strtolower($key), $value) }}</span>
                                            @endforeach
                                        </td>
                                        @if($product_discounts > 0)
                                            <td>
                                                @foreach($summary['discount'] ?? [] as $key => $value)
                                                    <span class="d-inline-block mr-2"><strong>{{ $value }}</strong> {{ trans_choice(strtolower($key), $value) }}</span>
                                                @endforeach
                                            </td>
                                        @endif
                                        <td>
                                            @foreach($summary['qty'] ?? [] as $key => $value)
                                                <span class="d-inline-block mr-2"><strong>{{ $value }}</strong> {{ trans_choice(strtolower($key), $value) }}</span>
                                            @endforeach
                                        </td>
                                        <td></td>
                                        <td class="text-right">{{ number_format($total_amount, 2) }}/=</td>
                                        <td class="text-right">{{ number_format($single_discount, 2) }}</td>
                                        <td class="text-right">{{ number_format($tfoot_total_discount, 2) }}</td>
                                        <td class="text-right">{{ number_format($tfoot_net_amount, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sticky Sidebar: Amount Calculation -->
        <div class="col-lg-4 col-md-5 col-sm-12">
            <div class="card shadow-sm sticky-top" style="top: 20px; z-index: 100;">
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
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th>Total Purchase</th>
                                <td class="text-right">{{ number_format($total_amount, 2) }}/=</td>
                            </tr>
                            <tr>
                                <th>
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
                                </th>
                                <td class="text-right">{{ number_format($total_discount, 2) }}/=</td>
                            </tr>
                            <tr>
                                <th>
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
                                </th>
                                <td class="text-right">{{ number_format($total_vat, 2) }}/=</td>
                            </tr>
                            <tr>
                                <th>Total Tk</th>
                                <td class="text-right">{{ number_format($total_tk, 2) }}/=</td>
                            </tr>
                            <tr>
                                <th>Previous Due</th>
                                <td class="text-right">{{ number_format($previous_due ?? 0, 2) }}/=</td>
                            </tr>
                            <tr>
                                <th>Current Due</th>
                                <td class="text-right">{{ number_format($total_tk + ($previous_due ?? 0), 2) }}/=</td>
                            </tr>
                            
                            <tr>
                                <th>Carrying</th>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="text" wire:model.lazy="carring" name="carring" class="form-control">
                                        <div class="input-group-append">
                                            <span class="input-group-text">/=</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Other Charge</th>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="text" wire:model.lazy="other_charge" name="other_charge" class="form-control">
                                        <div class="input-group-append">
                                            <span class="input-group-text">/=</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Payment Type</th>
                                <td>
                                    @if(isset($bank_title))
                                        <!-- bank title display -->
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
                                </td>
                            </tr>
                            <tr>
                                <th>Remarks</th>
                                <td>
                                    <input type="text" wire:model.lazy="payment_remarks" name="payment_remarks" class="form-control form-control-sm">
                                </td>
                            </tr>
                            <tr>
                                <th>Payment</th>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="text" wire:model.lazy="payment" name="payment" class="form-control">
                                        <div class="input-group-append">
                                            <span class="input-group-text">/=</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="font-weight-bold border-top">
                                <th>Total Payment</th>
                                <td class="text-right">{{ number_format($grand_total, 2) }}/=</td>
                            </tr>
                            <tr class="font-weight-bold">
                                <th>Due Amount</th>
                                <td class="text-right">{{ number_format($balance, 2) }}/=</td>
                            </tr>
                        </table>

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