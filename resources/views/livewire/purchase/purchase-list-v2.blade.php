@section('page-title', 'Purchase List 2')
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto">Purchase List-2</h2>
                <a href="{{route('live.purchase.create')}}" class="btn btn-md btn-primary"> <i class="fa fa-plus"
                        aria-hidden="true"></i> Add New Purchase</a>
            </div>
        </div>
        <div class="x_content p-3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
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
                        <table id="purchaseList" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">

                            <thead>
                                <tr>
                                    <th class="all">Date</th>
                                    <th class="all">Invoice</th>
                                    <th class="all">Supplier Name </th>
                                    <th class="all">Address</th>
                                    <th class="all">Mobile</th>
                                    <th class="all">Warehouse</th>
                                    <th class="all">Transport Info</th>
                                    <th class="all">Particulars</th>
                                    <th class="all">Total Qty</th>
                                    <th class="all">Dis. Qty</th>
                                    <th class="all">Purchase Qty</th>
                                    <th class="all">Weight</th>
                                    <th class="all">Total Value</th>
                                    <th class="all">Discount</th>
                                    <th class="all">Purchase Tk</th>
                                    <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $g_total_summary = [];
                                @endphp
                                @foreach($supplier_ledger as $purchase)
                                @php
                                $qty_summary = ['weight' => 0];
                                $filtered_products = $products->get($purchase->id, collect());
                                $g_total_summary['purchasePrice'] = $g_total_summary['purchasePrice'] ?? 0;
                                $g_total_summary['purchasePrice'] += $purchase->total_price;
                                $g_total_summary['discountPrice'] = $g_total_summary['discountPrice'] ?? 0;
                                $g_total_summary['discountPrice'] += $purchase->price_discount;
                                $g_total_summary['totalPrice'] = $g_total_summary['totalPrice'] ?? 0;
                                $g_total_summary['totalPrice'] += $purchase->total_price - $purchase->price_discount;
                                @endphp
                                <tr>
                                    {{-- Date --}}
                                    <td class="text-left">{{date('d-m-Y', strtotime($purchase->date))}}</td>

                                    {{-- Invoice --}}
                                    <td class="text-left">{{$purchase->id}}</td>

                                    {{-- Supplier Name --}}
                                    <td class="text-left">{{$purchase->supplier->company_name ?? ''}}</td>

                                    {{-- Address --}}
                                    <td class="text-left">{{$purchase->supplier->address ?? ''}}</td>

                                    {{-- Mobile --}}
                                    <td class="text-left">{{$purchase->supplier->phone ?? $purchase->supplier->mobile ?? ''}}</td>

                                    {{-- Warehouse --}}
                                    <td>{{$purchase->warehouse->name ?? ''}}</td>

                                    {{-- Transport Info --}}
                                    <td class="text-left">{{$purchase->transport_no}}{{$purchase->delivery_man ? ", " . $purchase->delivery_man : ''}}</td>

                                    {{-- Particular --}}
                                    <td class="text-left">
                                        @foreach ($filtered_products as $product)
                                        @php
                                        $type = $product->product->type;
                                        $qty_summary['total'][$type] = $qty_summary['total'][$type] ?? 0;
                                        $qty_summary['total'][$type] += $product->quantity;
                                        $qty_summary['discount'][$type] = $qty_summary['discount'][$type] ?? 0;
                                        $qty_summary['discount'][$type] += $product->discount_qty;
                                        $qty_summary['purchase'][$type] = $qty_summary['purchase'][$type] ?? 0;
                                        $qty_summary['purchase'][$type] += ($product->quantity - $product->discount_qty);
                                        $qty_summary['weight'] = $qty_summary['weight'] ?? 0;
                                        $qty_summary['weight'] += ($product->quantity - $product->discount_qty) * $product->weight;
                                        @endphp
                                        <p class="mb-0 text-left">
                                            {{ $product->product_code}} -
                                            {{ $product->product_name}} {{'('}}{{ $product->product->size->description}}{{')'}} -
                                            {{ formatAmount($product->quantity - $product->discount_qty)}} {{ trans_choice('labels.'.$type, ($product->quantity - $product->discount_qty))}}{{' @ '}}{{ formatAmount($product->unit_price) }}/=
                                            {{ formatAmount($product->total_price)}}/=
                                        </p>
                                        @endforeach
                                    </td>

                                    {{-- Total Qty --}}
                                    <td class="text-center">
                                        @if(isset($qty_summary['total']))
                                        @foreach ($qty_summary['total'] as $type => $qty)
                                        @php
                                        $g_total_summary['total'][$type] = $g_total_summary['total'][$type] ?? 0;
                                        $g_total_summary['total'][$type] += $qty;
                                        @endphp
                                        {{$qty > 0 ? formatAmount($qty) . ' ' . trans_choice('labels.' . $type, $qty) : ''}}
                                        @endforeach
                                        @endif
                                    </td>
                                    {{-- Dis Qty --}}
                                    <td class="text-center">
                                        @if(isset($qty_summary['discount']))
                                        @foreach ($qty_summary['discount'] as $type => $qty)
                                        @php
                                        $g_total_summary['discount'][$type] = $g_total_summary['discount'][$type] ?? 0;
                                        $g_total_summary['discount'][$type] += $qty;
                                        @endphp
                                        {{$qty > 0 ? formatAmount($qty) . ' ' . trans_choice('labels.' . $type, $qty) : ''}}
                                        @endforeach
                                        @endif
                                    </td>
                                    {{-- Purchase Qty --}}
                                    <td class="text-center">
                                        @if(isset($qty_summary['purchase']))
                                        @foreach ($qty_summary['purchase'] as $type => $qty)
                                        @php
                                        $g_total_summary['purchase'][$type] = $g_total_summary['purchase'][$type] ?? 0;
                                        $g_total_summary['purchase'][$type] += $qty;
                                        @endphp
                                        {{$qty > 0 ? formatAmount($qty) . ' ' . trans_choice('labels.' . $type, $qty) : ''}}
                                        @endforeach
                                        @endif
                                    </td>

                                    {{-- Weight --}}
                                    <td>
                                        @php
                                        $g_total_summary['weight'] = $g_total_summary['weight'] ?? 0;
                                        $g_total_summary['weight'] += $qty_summary['weight'];
                                        @endphp
                                        {{isset($qty_summary['weight']) && $qty_summary['weight'] > 0 ? formatAmount($qty_summary['weight']/1000) : 0}}
                                    </td>

                                    {{-- Total Value --}}
                                    <td class="text-right">{{formatAmount($purchase->total_price)}}/=</td>

                                    {{-- Discount --}}
                                    <td class="text-right">{{$purchase->price_discount ? formatAmount($purchase->price_discount) . '/=' : ''}}</td>

                                    {{-- Purchase Tk --}}
                                    <td class="text-right">{{formatAmount($purchase->total_price - $purchase->price_discount)}}/=</td>

                                    {{-- Action --}}
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                data-toggle="dropdown">
                                                <i class="fa fa-list"></i> <span class="caret"></span></button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a href="{{route('purchase.delete', $purchase->id)}}" class="btn btn-danger" id="delete"><i class="fa fa-trash"></i></a></li>
                                                <li><a href="{{route('purchase.view', $purchase->id)}}" class="btn btn-info"><i class="fa fa-eye"></i></a></li>
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
                                <td></td>
                                <th class="text-center">
                                    @php
                                    if(isset($g_total_summary['total'])){
                                    ksort($g_total_summary['total']);
                                    }
                                    @endphp
                                    @foreach (($g_total_summary['total'] ?? []) as $type => $qty)
                                    <span style="white-space: nowrap;">{{$qty > 0 ? formatAmount($qty) . ' ' . trans_choice('labels.' . $type, $qty) : ''}}</span>
                                    @endforeach
                                </th>
                                <th class="text-center">
                                    @php
                                    if(isset($g_total_summary['discount'])){
                                    ksort($g_total_summary['discount']);
                                    }
                                    @endphp
                                    @foreach (($g_total_summary['discount'] ?? []) as $type => $qty)
                                    <span style="white-space: nowrap;">{{$qty > 0 ? formatAmount($qty) . ' ' . trans_choice('labels.' . $type, $qty) : ''}}</span>
                                    @endforeach
                                </th>
                                <th class="text-center">
                                    @php
                                    if(isset($g_total_summary['purchase'])){
                                    ksort($g_total_summary['purchase']);
                                    }
                                    @endphp
                                    @foreach (($g_total_summary['purchase'] ?? []) as $type => $qty)
                                    <span style="white-space: nowrap;">{{$qty > 0 ? formatAmount($qty) . ' ' . trans_choice('labels.' . $type, $qty) : ''}}</span>
                                    @endforeach
                                </th>
                                <th class="text-center" style="white-space: nowrap;">{{formatAmount(($g_total_summary['weight'] ?? 0)/1000)}}</th>
                                <th class="text-right">{{formatAmount($g_total_summary['purchasePrice'])}}/=</th>
                                <th class="text-right">{{formatAmount($g_total_summary['discountPrice'])}}/=</th>
                                <th class="text-right">{{formatAmount($g_total_summary['totalPrice'])}}/=</th>
                                <td></td>
                            </tfoot>
                        </table>
                    </div>

                    @if (method_exists($supplier_ledger, 'links'))
                    {{ $supplier_ledger->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>