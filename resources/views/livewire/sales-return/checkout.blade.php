@section('page-title', 'Sales Return Checkout')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Sales Return Checkout</h2>
                <a href="{{ route('sales.return.index') }}" class="mr-auto ml-3 cursor-pointer"><i class="fa fa-close"></i></a>
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
                    <form wire:submit.prevent="salesStore()" enctype="multipart/form-data"  data-parsley-validate class="form-horizontal form-label-left">
                        @csrf
                        <!--Start supplier area-->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <h5 class="x_title text-center">Customer Info</h5>
                                @php
                                    $previous_due = 0;
                                @endphp
                                @if(session()->has('return_customer'))
                                    @php
                                        $value = Session::get('return_customer');
                                    @endphp
                                    @php
                                        $previous_due = $value['balance'];
                                    @endphp


                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Customer Name</th>
                                                <th>Address</th>
                                                <th>Mobile</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td>{{$value['customer_name']}}</td>
                                                <td>{{$value['address']}}</td>
                                                <td>{{$value['mobile']}}</td>
                                            </tr>
                                        </tbody>
                                        <thead>
                                            <tr>
                                                <th>Return Date</th>
                                                <th>Store</th>
                                                <th>Remarks</th>


                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td>{{date('d-m-Y', strtotime($value['return_date']))}}</td>
                                                <td>{{$value['product_store_name']}}</td>
                                                <td>{{$value['remarks']}}</td>
                                            </tr>
                                        </tbody>

                                    </table>
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
                                            <th class="all">Sale Qty</th>
                                            <th class="all">Return Qty</th>
                                            <th class="all">Rate</th>
                                            <th class="all">Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $type = 0;
                                            $total_amount = 0;
                                            $total_qty = 0;
                                            $qty_summary = [];
                                        @endphp
                                        @forelse (Cart::instance('sales_return')->content() as $product)
                                        {{-- @dump($product) --}}

                                        @php
                                            $type=$product->options->type;
                                            $qty_summary['sale'][$type] = $qty_summary['sale'][$type] ?? 0;
                                            $qty_summary['total'][$type] = $qty_summary['total'][$type] ?? 0;
                                            $qty_summary['total'][$type] += $product->qty;
                                            $qty_summary['sale'][$type] += $product->options->sale_qty;
                                            $total_amount += $product->price * $product->qty;
                                        @endphp
                                        <tr>
                                            <td>{{$product->options->code}}</td>
                                            <td class="text-center">{{$product->name}}</td>
                                            <td class="text-center">{{$product->options->sale_qty}}  {{ trans_choice('labels.'.$type, $product->options->sale_qty) }}</td>
                                            <td class="text-center">{{$product->qty}}  {{ trans_choice('labels.'.$type, $product->qty) }}</td>
                                            <td class="text-right">{{number_format($product->price)}}/=</td>
                                            <td class="text-right">
                                                @php
                                                    $total = $product->qty*$product->price;
                                                @endphp
                                                {{number_format($total)}}/=
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td>No Product Found!</td>
                                        </tr>
                                        @endforelse

                                    </tbody>
                                    <tfoot>
                                        <tr class="font-weight-bold">
                                            <td>Total</td>
                                            <td></td>
                                            <td class="text-center">
                                                @if (isset($qty_summary['sale']))
                                                    @foreach ($qty_summary['sale'] as $type => $qty)
                                                        {{$qty > 0 ? $qty . ' ' . trans_choice('labels.'.$type, $qty) : ''}}
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (isset($qty_summary['total']))
                                                    @foreach ($qty_summary['total'] as $type => $qty)
                                                        {{$qty > 0 ? $qty . ' ' . trans_choice('labels.'.$type, $qty) : ''}}
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td colspan="2"  class="text-right">{{number_format($total_amount)}}/=</td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div class="ln_solid"></div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="justify-content-center d-flex" style="gap: 10px">
                                        <button type="button" class="btn btn-primary" wire:click="back" type="button">Back</button>
                                        <button type="button" class="btn btn-danger" wire:click="cancel" type="button">Cancel</button>
                                        <button type="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </div>
                            </div>
                            {{-- End Purchase Total Amount Area --}}
                        </div>
                    </form>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <h5 class="x_title text-center">Amount Calculation</h5>
                        <div class="px-3">

                            <table class="table table-striped table-bordered">
                                <tr class="text-right"><th>Total Sales<td class="calculation-text-right"> {{Cart::instance('sales_return')->total()-Cart::instance('sales_return')->tax()}}/=</td></th></tr>
                                <tr class="text-right has-input"><th>Carring<td> <input type="text" wire:model.lazy="carring" name="carring" class="form-control"></td></th></tr>
                                <tr class="text-right has-input"><th>Other Charge<td> <input type="text" wire:model.lazy="other_charge" name="other_charge" class="form-control"></td></th></tr>
                                <tr class="text-right "><th>Grand Total<td  class="calculation-text-right"> {{$grand_total . '/=' ?? ''}}</td></th></tr>
                            </table>
                        </div>

                </div>
            </div>
        </div>
    </div>
</div>

