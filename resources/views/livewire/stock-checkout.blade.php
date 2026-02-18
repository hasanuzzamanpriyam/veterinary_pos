<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Stock Checkout</h2>
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
            <form wire:submit.prevent="stockStore()" enctype="multipart/form-data" data-parsley-validate
                        class="form-horizontal form-label-left">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12">

                        @csrf
                        <!--Start supplier area-->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <h5 class="x_title text-center text-dark">Store Info</h5>
                                @if ($product_store_data)
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>id</th>
                                                <th>Store/Warehouse Name</th>
                                                <th>Address</th>
                                                <th>Mobile</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $product_store_data['id'] }}</td>
                                                <td>{{ $product_store_data['name'] }}</td>
                                                <td>{{ $product_store_data['address'] }}</td>
                                                <td>{{ $product_store_data['mobile'] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <!--End supplier area-->
                            {{-- Start Purchase Total Amount Area --}}



                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <h5 class="x_title text-center text-dark">Products Info</h5>
                                <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="all">Code</th>
                                            <th class="all">Name</th>
                                            <th class="all">Quantity</th>
                                            <th class="all">Prev Stock</th>
                                            <th class="all">Price Rate</th>
                                            <th class="all">Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $type = 0;
                                            $total_amount = 0;
                                            $total_sales = 0;
                                            $total_discounts = 0;
                                            $total_qty = 0;
                                            $items = 0;
                                        @endphp
                                        {{-- @dump($products) --}}
                                        @forelse ($products as $product)
                                            @php
                                                $type = $product->options->type;
                                                $total_qty += $product->qty;
                                                $items++;
                                                $summary['qty'][$type] = $summary['qty'][$type] ?? 0;
                                                $summary['qty'][$type] += $product->qty;
                                            @endphp
                                            <tr>
                                                <td>{{ $product->options->code }}</td>
                                                <td>{{ $product->name }}</td>
                                                <td class="text-center">
                                                    {{ $product->qty }} {{ trans_choice($product->options->type, $product->qty) }}
                                                </td>

                                                <td class="text-center">
                                                    <span>{{ $product->options->stock }} {{trans_choice($product->options->type, $product->options->stock)}}</span>
                                                </td>
                                                <td class="text-right">{{ $product->price }}/=</td>
                                                <td class="text-right">{{ $product->qty * $product->price }}/=</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td>No Product Found!</td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                    <tfoot>
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
                                                            <span class="d-inline-block"><strong>{{ $value }}</strong> <span class="ttl">{{trans_choice(strtolower($key), $value)}}</span></span>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>

                                            <td></td>
                                            <td></td>
                                            <td colspan="1" class="text-right">{{ $grand_total }}/=</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            {{-- End Purchase Total Amount Area --}}
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
                                <input type="submit" value="Add Stock" class="btn btn-success btn-md">
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
