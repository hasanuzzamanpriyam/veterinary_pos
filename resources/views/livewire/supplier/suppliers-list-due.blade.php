@section('page-title', 'Due Supplier List')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Due Supplier List</h2>
                <a href="{{ route('live.supplier.create') }}" class="btn btn-md btn-primary mr-auto"><i
                    class="fa fa-plus" aria-hidden="true"></i> Add Supplier</a>

                <span>Download as</span>
                <a href="{{ route('download.supplier', ['type' => 'due', 'format' => 'excel', 'q' => $queryString ]) }}" class="btn btn-sm btn-success py-0">
                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a>
                <button type="button" class="btn btn-danger btn-sm py-0" wire:click="downloadPdf" style="min-width: 80px"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</button>
            </div>
        </div>

        <div class="x_content p-3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    {{cute_loader()}}
                    <div class="table-header d-flex align-items-center justify-content-between">
                        <div class="per-page mr-auto">
                            <div class="form-group">
                                <select id="perpage" class="form-control form-control-sm" wire:model.live="perPage">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="all">All</option>
                                </select>
                            </div>
                        </div>

                        @if(!empty($queryString))
                            <div class="search-result d-flex align-items-center mr-auto">
                                <span style="font-size: 16px">Search result for: <strong>{{$queryString}}</strong></span>
                            </div>
                        @endif

                        <div class="ajax-search d-flex align-items-center gap-2">
                            <div class="form-group m-0">
                                <input type="text" id="supplier-search" class="form-control form-control-sm" placeholder="Name, Address or Phone" wire:model="queryString" />
                            </div>
                            <div class="form-group m-0">
                                <button type="button" class="btn btn-primary btn-sm" wire:click="filterData" style="min-width: 80px">Search</button>
                            </div>
                        </div>
                    </div>
                    {{-- @dump($suppliers) --}}
                    <div class="card-box table-responsive">
                        <table id="datatable-responsiveer" class="table table-striped table-bordered dt-responsive nowrap" cellpadding="0" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">ID</th>
                                    <th class="text-left">Company Name</th>
                                    <th class="text-left">Address</th>
                                    <th class="text-left">Phone</th>
                                    <th class="all">Ledger</th>
                                    <th class="all" style="max-width: 100px">Pur. Qty</th>
                                    <th class="all">Weight</th>
                                    <th class="all">Total (TK)</th>
                                    <th class="all">Payment</th>
                                    <th class="all">Balance</th>
                                    <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $g_total_summary = [];
                                @endphp
                                @foreach($suppliers as $supplier)
                                    @php
                                        $total_purchases = $supplier->total_purchases ?? 0;
                                        $total_return = $supplier->total_returns ?? 0;
                                        $total_discount = $supplier->total_price_discounts ?? 0;
                                        $total_vat = $supplier->total_vat ?? 0;
                                        $total_carring = $supplier->total_carring ?? 0;
                                        $total_others = $supplier->total_others ?? 0;
                                        $total_payment = $supplier->total_payments ?? 0;
                                        $prev_due = $supplier->previous_due ?? 0;
                                        $balance = $supplier->balance;
                                        $totalWeight = $supplierWeights->firstWhere('supplier_id', $supplier->id)->net_weight ?? 0;

                                        $total_qty = [];
                                        $total_purchase_discount_qty = [];
                                        $total_return_qty = [];
                                        $total_purchase_qty = [];
                                        $total_tk = $total_purchases - $total_return - $total_discount;
                                        $total_G_payment = $total_payment + $total_vat + $total_carring + $total_others;
                                        $qty_summary = $group_by_supplier->get($supplier->id);
                                        if (is_array($qty_summary) && count($qty_summary) > 0) {
                                            foreach($qty_summary as $key => $value) {
                                                $total_qty[$key] = $total_qty[$key] ?? 0;
                                                $total_purchase_discount_qty[$key] = $total_purchase_discount_qty[$key] ?? 0;
                                                $total_return_qty[$key] = $total_return_qty[$key] ?? 0;
                                                $total_purchase_qty[$key] = $total_purchase_qty[$key] ?? 0;

                                                $total_qty[$key] += $value['purchase'];
                                                $total_purchase_discount_qty[$key] += $value['discount'];
                                                $total_return_qty[$key] += $value['return'];
                                                $total_purchase_qty[$key] += $value['purchase'] - $value['discount'] - $value['return'];

                                                $g_total_summary['purchase_qty'][$key] = $g_total_summary['purchase_qty'][$key] ?? 0;
                                                $g_total_summary['purchase_qty'][$key] += $value['purchase'] - $value['discount'] - $value['return'];
                                            }
                                            ksort($total_qty);
                                            ksort($total_purchase_discount_qty);
                                            ksort($total_return_qty);
                                            ksort($total_purchase_qty);
                                        }

                                        $g_total_summary['weight'] = $g_total_summary['weight'] ?? 0;
                                        $g_total_summary['weight'] += $totalWeight;
                                        $g_total_summary['total_g_payment'] = $g_total_summary['total_g_payment'] ?? 0;
                                        $g_total_summary['total_g_payment'] += $total_G_payment;
                                        $g_total_summary['total_payment_tk'] = $g_total_summary['total_payment_tk'] ?? 0;
                                        $g_total_summary['total_payment_tk'] += $total_payment;
                                        $g_total_summary['balance'] = $g_total_summary['balance'] ?? 0;
                                        $g_total_summary['balance'] += $balance;

                                        $currentPage = method_exists($suppliers, 'currentPage') ? $suppliers->currentPage() : 1;
                                        $perPage = method_exists($suppliers, 'perPage') ? $suppliers->perPage() : $suppliers->count(); // Fallback to total count
                                        $iteration = ($currentPage - 1) * $perPage + $loop->iteration;
                                    @endphp
                                    <tr>
                                        <td>{{$iteration}}</td>

                                        <td class="text-left">{{$supplier->company_name}}</td>
                                        <td class="text-left">{{$supplier->address}}</td>
                                        <td class="text-left">{{$supplier->mobile}}</td>
                                        <td>{{$supplier->ledger_page}}</td>

                                        {{-- Pur. Qty --}}
                                        <td class="text-wrap" style="max-width: 100px">
                                            @foreach ($total_purchase_qty as $key => $value)
                                                @if($value > 0)
                                                    <span>{{ number_format($value) }} {{ trans_choice('labels.'.$key, $value) }}</span>
                                                @endif
                                            @endforeach
                                        </td>

                                        {{-- M/T --}}
                                        <td>{{$totalWeight ? $totalWeight / 1000 . ' MT' : ''}}</td>

                                        {{-- Total TK --}}
                                       <td class="text-right">{{ $total_tk ? number_format($total_tk) . '/=' : '' }}</td>

                                        {{-- Payment --}}
                                        <td class="text-right">{{ $total_G_payment ? number_format($total_G_payment) . '/=' : '' }}</td>

                                        {{-- Balance --}}
                                        <td class="text-right">{{ $balance ? number_format($balance) . '/=' : '' }}</td>

                                        {{-- Action --}}
                                        <td>
                                            <div class="btn-group btn-group-vertical customer_diplay_list">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                    data-toggle="dropdown">
                                                    <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a href="{{route('supplier.edit',$supplier->id)}}"
                                                        class="btn btn-success btn-sm w-20">Edit <i class="fa fa-edit" ></i></a></li>

                                                    <li><a href="{{route('supplier.delete',$supplier->id)}}"
                                                        class="btn btn-danger btn-sm w-20" id="delete">Delete <i class="fa fa-trash" ></i></a></li>

                                                    <li><a href="{{route('supplier.view',$supplier->id)}}"
                                                        class="btn btn-info btn-sm w-20">View <i class="fa fa-eye" ></i></a></li>

                                                    <li><a href="{{route('supplier.purchase.list',['id' => $supplier->id, 'v' => 1])}}"
                                                        class="btn btn-info btn-sm w-20">Purchase List 1 <i class="fa fa-book" ></i></a></li>

                                                    <li><a href="{{route('supplier.ledger1',$supplier->id)}}"
                                                        class="btn btn-info btn-sm w-20">Ledger 1 <i class="fa fa-book" ></i></a></li>

                                                    <li><a href="{{route('supplier.statement', ['id' => $supplier->id, 'v' => 1])}}"
                                                        class="btn btn-info btn-sm w-20">Statement 1 <i class="fa fa-tasks" ></i></a></li>

                                                    <li><a href="{{route('payment.supplier.report',$supplier->id)}}"
                                                        class="btn btn-info btn-sm w-20">Payment <i class="fa fa-money"></i></a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-wrap" style="max-width: 100px">
                                        @if (isset($g_total_summary['purchase_qty']) && $g_total_summary['purchase_qty'] > 0)
                                            @foreach ($g_total_summary['purchase_qty'] as $type => $qty)
                                                <strong>{{$qty > 0 ? number_format($qty) . ' ' . trans_choice('labels.' . $type, $qty) : ''}}</strong>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <strong>{{ isset($g_total_summary['weight']) && $g_total_summary['weight'] > 0 ? $g_total_summary['weight']/1000 . ' MT' : '' }}</strong>
                                    </td>
                                    <td class="text-right">
                                        <strong>{{ isset($g_total_summary['total_payment_tk']) && $g_total_summary['total_payment_tk'] > 0 ? number_format($g_total_summary['total_payment_tk']) . '/=' : '' }}</strong>
                                    </td>
                                    <td class="text-right">
                                        <strong>{{ isset($g_total_summary['total_g_payment']) && $g_total_summary['total_g_payment'] > 0 ? number_format($g_total_summary['total_g_payment']) . '/=' : '' }}</strong>
                                    </td>
                                    <td class="text-right">
                                        <strong>{{ isset($g_total_summary['balance']) && $g_total_summary['balance'] > 0 ? number_format($g_total_summary['balance']) . '/=' : '' }}</strong>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if (method_exists($suppliers, 'links'))
                        <div class="mt-4 w-100">
                            {{ $suppliers->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
