@section('page-title', 'Due Customer List')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel p-3">
        <div class="x_title">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Due Customer List</h2>
                <a href="{{ route('live.customer.create') }}" class="btn btn-sm btn-info mr-auto">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add Customer</a>
                <span>Download as</span>
                <a href="{{ route('download.customer', ['type' => 'due', 'format' => 'excel', 'q' => $queryString ]) }}" class="btn btn-sm btn-success py-0">
                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a>
                <button type="button" class="btn btn-danger btn-sm py-0" wire:click="downloadPdf" style="min-width: 80px"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</button>
            </div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    {{cute_loader()}}
                    <div class="table-header d-flex align-items-center justify-content-between">
                        <div class="per-page mr-auto">
                            <div class="form-group">
                                <select id="perpage" class="form-control" wire:model.live="perPage">
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

                    <div class="card-box table-responsive">
                        <table id="datatable-responsiveer"
                            class="table table-striped table-bordered dt-responsive nowrap"
                            cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="all">SL</th>
                                    <th class="all customer_name_th">Customer Name</th>
                                    <th class="all customer_address_th">Address</th>
                                    <th class="all customer_mobile_th">Phone</th>
                                    <th class="all">Type</th>
                                    <th class="all customer_ledger_th">Ledger</th>
                                    <th class="all" style="max-width: 100px">Sale Qty</th>
                                    <th class="all">Sale (TK)</th>
                                    <th class="all">Collection</th>
                                    <th class="all">Balance</th>
                                    <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $g_total_summary = [];
                                @endphp
                                @foreach ($customers as $customer)
                                    @php
                                        $total_sales = $customer->total_sales ?? 0;
                                        $total_return = $customer->total_returns ?? 0;
                                        $total_discount = $customer->total_price_discounts ?? 0;
                                        $total_vat = $customer->total_vat ?? 0;
                                        $total_carring = $customer->total_carring ?? 0;
                                        $total_others = $customer->total_others ?? 0;
                                        $total_collection = $customer->total_collections ?? 0;
                                        $prev_due = $customer->previous_due ?? 0;
                                        $balance = $customer->balance;

                                        $total_qty = [];
                                        $total_sale_discount_qty = [];
                                        $total_return_qty = [];
                                        $total_sale_qty = [];
                                        $total_tk = $total_sales - $total_return - $total_discount + $total_vat + $total_carring + $total_others;
                                        $qty_summary = $group_by_customer->get($customer->id);
                                        if (is_array($qty_summary) && count($qty_summary) > 0) {
                                            foreach($qty_summary as $key => $value) {
                                                $total_qty[$key] = $total_qty[$key] ?? 0;
                                                $total_sale_discount_qty[$key] = $total_sale_discount_qty[$key] ?? 0;
                                                $total_return_qty[$key] = $total_return_qty[$key] ?? 0;
                                                $total_sale_qty[$key] = $total_sale_qty[$key] ?? 0;

                                                $total_qty[$key] += $value['sale'];
                                                $total_sale_discount_qty[$key] += $value['discount'];
                                                $total_return_qty[$key] += $value['return'];
                                                $total_sale_qty[$key] += $value['sale'] - $value['discount'] - $value['return'];

                                                $g_total_summary['sale_qty'][$key] = $g_total_summary['sale_qty'][$key] ?? 0;
                                                $g_total_summary['sale_qty'][$key] += $value['sale'] - $value['discount'] - $value['return'];
                                            }
                                            ksort($total_qty);
                                            ksort($total_sale_discount_qty);
                                            ksort($total_return_qty);
                                            ksort($total_sale_qty);
                                        }

                                        $g_total_summary['total_tk'] = $g_total_summary['total_tk'] ?? 0;
                                        $g_total_summary['total_tk'] += $total_tk;
                                        $g_total_summary['total_collection_tk'] = $g_total_summary['total_collection_tk'] ?? 0;
                                        $g_total_summary['total_collection_tk'] += $total_collection;
                                        $g_total_summary['balance'] = $g_total_summary['balance'] ?? 0;
                                        $g_total_summary['balance'] += $balance;

                                        $currentPage = method_exists($customers, 'currentPage') ? $customers->currentPage() : 1;
                                        $perPage = method_exists($customers, 'perPage') ? $customers->perPage() : $customers->count(); // Fallback to total count
                                        $iteration = ($currentPage - 1) * $perPage + $loop->iteration;
                                    @endphp
                                    <tr>
                                        <td>{{ $iteration }}</td>
                                        <td class="customer_name_td text-left">{{ $customer->name ?? '' }}</td>
                                        <td class="customer_address_td text-left">{{ $customer->address ?? '' }}</td>
                                        <td class="customer_mobile_td">{{ $customer->mobile ?? $customer->phone ?? '' }}</td>
                                        <td class="customer_type_td">{{ $customer->type ?? '' }}</td>
                                        <td class="customer_ledger_td">{{ $customer->ledger_page ?? '' }}</td>

                                        <td class="text-wrap" style="max-width: 100px">
                                            @foreach ($total_sale_qty as $key => $value)
                                                @if($value > 0)
                                                    <span>{{ number_format($value) }} {{ trans_choice('labels.'.$key, $value) }}</span>
                                                @endif
                                            @endforeach
                                        </td>

                                        {{-- Total TK --}}
                                        <td class="text-right">{{ $total_tk ? number_format($total_tk) . '/=' : '' }}</td>

                                        {{-- Collection --}}
                                        <td class="text-right">{{ $total_collection ? number_format($total_collection) . '/=' : ''}}</td>

                                        {{-- Balance --}}
                                        <td class="text-right">{{ $balance ? number_format($balance) . '/=' : ''}}</td>
                                        <td>
                                            <div class="btn-group btn-group-vertical customer_diplay_list">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                    data-toggle="dropdown">
                                                    <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a href="{{ route('customer.edit', $customer->id) }}"
                                                            class="btn btn-success btn-sm w-20">Edit <i
                                                            class="fa fa-edit"></i></a></li>
                                                    <li><a href="{{ route('customer.view', $customer->id) }}"
                                                            class="btn btn-info btn-sm w-20">View <i
                                                                class="fa fa-eye"></i></a></li>
                                                    <li><a href="{{ route('customer.sales.report1', $customer->id) }}"
                                                            class="btn btn-info btn-sm w-20">Sale List 1 <i
                                                                class="fa fa-list"></i></a></li>
                                                    <li><a href="{{ route('customer.ledger1', $customer->id) }}"
                                                            class="btn btn-info btn-sm w-20">Ledger-1 <i
                                                                class="fa fa-book"></i></a></li>
                                                    <li><a href="{{ route('customer.statement1', $customer->id) }}"
                                                            class="btn btn-info btn-sm w-20">Statement-1 <i
                                                                class="fa fa-tasks"></i></a></li>
                                                    <li><a href="{{ route('collection.customer.report', $customer->id) }}"
                                                            class="btn btn-info btn-sm w-20">Collection <i
                                                                class="fa fa-money"></i></a></li>
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
                                    <td></td>
                                    <td class="text-wrap" style="max-width: 100px">
                                        @if (isset($g_total_summary['sale_qty']) && $g_total_summary['sale_qty'] > 0)
                                            @foreach ($g_total_summary['sale_qty'] as $type => $qty)
                                                <strong>{{$qty > 0 ? number_format($qty) . ' ' . trans_choice('labels.' . $type, $qty) : ''}}</strong>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <strong>{{ isset($g_total_summary['total_tk']) && $g_total_summary['total_tk'] > 0 ? number_format($g_total_summary['total_tk']) . '/=' : '' }}</strong>
                                    </td>
                                    <td class="text-right">
                                        <strong>{{ isset($g_total_summary['total_collection_tk']) && $g_total_summary['total_collection_tk'] > 0 ? number_format($g_total_summary['total_collection_tk']) . '/=' : '' }}</strong>
                                    </td>
                                    <td class="text-right">
                                        <strong>{{ isset($g_total_summary['balance']) && $g_total_summary['balance'] > 0 ? number_format($g_total_summary['balance']) . '/=' : '' }}</strong>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @if (method_exists($customers, 'links'))
                        <div class="mt-4 w-100">
                            {{ $customers->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

