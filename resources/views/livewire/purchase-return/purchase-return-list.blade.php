@section('page-title', 'Purchase Return List')
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto">Purchase Return List</h2>
                <a href="{{route('live.purchase.return.create')}}" class="btn btn-md btn-primary"> <i class="fa fa-plus"
                        aria-hidden="true"></i> Add New Purchase Return</a>
            </div>
        </div>

        <div class="x_content p-3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    <div class="card-box">
                        {{-- notification message --}}
                        @if(session()->has('msg'))
                            <div class="text-center alert alert-success">
                                {{session()->get('msg')}}
                            </div>
                        @endif
                    </div>
                    {{cute_loader()}}
                    <div class="card-box flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
                        <div class="form-group">
                            <select id="perpage" class="form-control" wire:model.live="perPage">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="all">All</option>
                            </select>
                        </div>
                        <form wire:submit.prevent="search" data-parsley-validate>
                            @csrf
                            <div class="row">
                                <div class="col-md-12 flex-sm-fill d-sm-flex align-items-sm-center gap-2">
                                    <div class="form-group">
                                            <input type="date" id="start_date" wire:model="start_date" class="form-control">
                                    </div>
                                    <div class="form-group">
                                            <input type="date" id="end_date" wire:model="end_date" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" value="Search" class="form-control btn btn-success btn-sm">
                                    </div>
                                    <div class="form-group">
                                        <button type="reset" wire:click="searchReset" class="form-control btn btn-danger btn-sm">Reset</button>
                                    </div>
                                </div>
                            </div>
                         </form>

                    </div>
                    <div class="table-responsive">
                        <table id="purchaseReturnList" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <th class="all">Return Date</th>
                                <th class="all">Supplier Name</th>
                                <th class="all">Address</th>
                                <th class="all">Mobile</th>
                                <th class="all">Delevary Info</th>
                                <th class="all">Particular</th>
                                <th class="all">Quantity</th>
                                <th class="all">Return Value</th>
                                <th class="all">Carring</th>
                                <th class="all">Others</th>
                                <th class="all">Total</th>
                                <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supplier_ledger as $ledger)
                                {{-- @dump($ledger) --}}
                                    @php
                                        $qty_summary = [];
                                        $filtered_products = $products->where('transaction_id', $ledger->id);
                                    @endphp
                                    <tr>
                                        {{-- Return Date --}}
                                        <td class="text-left">{{date('d-m-Y', strtotime($ledger->date))}}</td>

                                        {{-- Supplier Name --}}
                                        <td class="text-left">{{$ledger->supplier->company_name ?? ''}}</td>

                                        {{-- Supplier Address --}}
                                        <td class="text-left">{{$ledger->supplier->address ?? ''}}</td>

                                        {{-- Mobile --}}
                                        <td class="text-left">{{$ledger->supplier->mobile ?? $ledger->supplier->phone ?? ''}}</td>

                                        {{-- Delivery Info --}}
                                        <td class="text-left">{{$ledger->transport_no}}{{$ledger->delivery_man ? ", " . $ledger->delivery_man : ''}}</td>

                                        {{-- Particular --}}
                                        <td class="text-left">
                                            @foreach ($filtered_products as $product)
                                                @php
                                                    $type = $product->product->type;
                                                    $qty_summary['total'][$type] = $qty_summary['total'][$type] ?? 0;
                                                    $qty_summary['total'][$type] += $product->quantity;
                                                    $qty_summary['weight'] = $qty_summary['weight'] ?? 0;
                                                    $qty_summary['weight'] += ($product->quantity - $product->discount_qty) * $product->weight;
                                                @endphp
                                                <p class="mb-0 text-left">
                                                    {{ $product->product_code}} -
                                                    {{ $product->product_name}} {{'('}}{{ $product->product->size->description}}{{')'}} -
                                                    {{ formatAmount($product->quantity - $product->discount_qty)}} {{ trans_choice('labels.'.$type, ($product->quantity - $product->discount_qty))}}{{' @ '}}{{ formatAmount($product->unit_price)}}/=
                                                    {{ formatAmount($product->total_price)}}/=
                                                </p>
                                            @endforeach
                                        </td>

                                        {{-- Quantity --}}
                                        <td class="text-center">
                                            @if(isset($qty_summary['total']))
                                                @foreach ($qty_summary['total'] as $type => $qty)
                                                    {{$qty > 0 ? formatAmount($qty) . ' ' . trans_choice('labels.' . $type, $qty) : ''}}
                                                @endforeach
                                            @endif
                                        </td>

                                        {{-- Return Value --}}
                                        <td class="text-right">{{formatAmount($ledger->total_price)}}/=</td>

                                        {{-- Carring --}}
                                        <td class="text-right">{{$ledger->carring ? formatAmount($ledger->carring) . '/=' : ''}}</td>

                                        {{-- Other Charge --}}
                                        <td class="text-right">{{$ledger->other_charge ? formatAmount($ledger->other_charge) . '/=' : ''}}</td>

                                        {{-- Total --}}
                                        @php
                                            $total = $ledger->total_price - ($ledger->carring + $ledger->other_charge);
                                        @endphp
                                        <td  class="text-right">{{formatAmount($total)}}/=</td>

                                        {{-- Action --}}
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                    data-toggle="dropdown">
                                                    <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a href="{{route('purchase.delete', ['invoice' => $ledger->id, 'view' => 'return'])}}" class="btn btn-danger" id="delete"><i class="fa fa-trash" ></i></a></li>
                                                    <li> <a href="{{route('purchase.view', ['invoice' => $ledger->id, 'view' => 'return'])}}" class="btn btn-info"><i class="fa fa-eye" ></i></a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td></td>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
