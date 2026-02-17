@section('page-title', 'Supplier Ledger')

@php
    $type = 0;
@endphp
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel p-3">
        <div class="x_title">
            <div class="header-title d-flex align-items-center justify-content-between gap-2">
                <h2>Supplier Ledger</h2>
                @if($supplier)
                    <h6 class="text-dark mb-0">
                        {{$supplier->company_name}}{{$supplier->address ? ' - ' . $supplier->address : ''}}{{$supplier->mobile ? ' - ' . $supplier->mobile : ''}}
                    </h6>
                @endif
                {{-- <a href="{{route('supplier.index.due')}}" class="ml-3 cursor-pointer"><i
                        class="fa fa-arrow-left"></i> Back</a> --}}
            </div>
        </div>
        <div class="search" wire:ignore>
            <form action="{{route('supplier.transactions.ledger')}}" method="get">
                <div class="row justify-content-center">
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="supplier-search-area">
                            <label class="py-1 border" for="supplier_search">From Date</label>
                            <div class="input-group date" id="start_date_picker">
                                <input name="start_date" type="text" class="form-control" placeholder="dd-mm-yyyy"
                                    value="{{$start_date}}">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="supplier-search-area">
                            <label class="py-1 border" for="supplier_search">To Date</label>
                            <div class="input-group date" id="end_date_picker">
                                <input name="end_date" type="text" class="form-control" placeholder="dd-mm-yyyy"
                                    value="{{$end_date}}">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="supplier-search-area">
                            <label class="py-1 border" for="supplier_search">Select Supplier</label>
                            <div class="form-group">
                                <select id="get_supplier_id" name="id" placeholder="search supplier"
                                    class="form-control">
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{$supplier->id}}" @selected($supplier->id == $supplier_id)>
                                            {{$supplier->company_name}} - {{$supplier->address}} - {{$supplier->mobile}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="supplier-search-button pt-4">
                            <div class="form-group pt-3">
                                <button type="submit" class="btn btn-primary btn-sm"
                                    style="min-width: 100px">Get</button>
                                <button type="reset" class="btn btn-warning btn-sm" wire:click="resetData"
                                    style="min-width: 100px">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="x_content">
            <div class="due-list-area">
                {{cute_loader()}}
                @if(count($supplier_ledger_info) > 0)
                    <div class="table-header d-flex align-items-center gap-2 mb-2">
                        <div class="per-page mr-auto">
                            <div class="form-group m-0">
                                <select id="perpage" class="form-control form-control-sm" wire:model.live="perPage">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="all">All</option>
                                </select>
                            </div>
                        </div>
                        @if(!empty($search_query))
                            <div class="search-result d-flex align-items-center mr-auto">
                                <span style="font-size: 16px">Search result for: <strong>{{$search_query}}</strong></span>
                            </div>
                        @endif
                        <div class="download-btns d-flex align-items-center gap-2">
                            <div class="form-group m-0">
                                <span>Download Report</span>
                            </div>
                            <div class="form-group m-0">
                                <button type="button" class="btn btn-danger btn-sm" wire:click="downloadPdf"
                                    style="min-width: 80px"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</button>
                            </div>
                        </div>
                        <div class="ajax-search d-flex align-items-center gap-2">
                            <div class="form-group m-0">
                                <input type="text" wire:model="search_query" class="form-control form-control-sm"
                                    style="min-width: 250px" />
                            </div>

                            <div class="form-group m-0">
                                <button type="button" class="btn btn-primary btn-sm" wire:click="filterData"
                                    style="min-width: 80px">Search</button>
                            </div>
                        </div>
                    </div>
                @endif
                @if(count($supplier_ledger_info) > 0)
                    <div class="table-responsive">
                        <table id="datatable-responsiverr" class="table table-striped table-bordered" cellspacing="0">
                            <thead>
                                <tr class="text-center">
                                    <th>Date</th>
                                    <th>Invoice</th>
                                    <th>Description</th>
                                    <th>Purchase Qty</th>
                                    <th>Weight</th>
                                    <th>Purchase</th>
                                    <th>Payment</th>
                                    <th>Due Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_summary = [
                                        'qty' => [],
                                        'weight' => 0,
                                        'purchase_tk' => 0,
                                        'payment_tk' => 0,
                                        'balance' => 0
                                    ];
                                @endphp
                                {{-- Show previous balance only on first page of pagination --}}
                                @if($previous_balance && $perPage !== 'all' && $supplier_ledger_info->currentPage() == 1)
                                    @php
                                        $total_summary['balance'] = $previous_balance->balance;
                                    @endphp
                                    <tr>
                                        <td style="white-space: nowrap;">--</td>
                                        <td class="text-center">--</td>
                                        <td class="text-left">Before Balance</td>
                                        <td class=""></td>
                                        <td class=""></td>
                                        <td class=""></td>
                                        <td class="text-right"></td>
                                        <td class="text-right">{{number_format($total_summary['balance'])}}/=</td>
                                        <td></td>
                                    </tr>
                                @endif
                                @foreach($supplier_ledger_info as $report)

                                    @php
                                        if ($report->type != 'prev' && $report->payment > 0) {
                                            $total_summary['payment_tk'] = $total_summary['payment_tk'] ?? 0;
                                            $total_summary['payment_tk'] += $report->payment;
                                        }

                                        $x_total = $report->total_price - $report->price_discount - $report->vat - $report->carring - $report->other_charge;

                                        if ($report->type == 'purchase' || $report->type == 'return') {
                                            $total_summary['purchase_tk'] = $total_summary['purchase_tk'] ?? 0;
                                            $total_summary['purchase_tk'] += $report->type == 'purchase' ? $x_total : -$x_total;
                                        }

                                    @endphp
                                    @if($report->type == 'other')
                                        @php
                                            $total_summary['balance'] = $total_summary['balance'] ?? 0;
                                            $total_summary['balance'] += $report->balance;
                                        @endphp
                                        <tr>
                                            <td style="white-space: nowrap;">{{date('d-m-Y', strtotime($report->date))}}</td>
                                            <td class="text-center">{{$report->id}}</td>
                                            <td class="text-left">Opening Balance</td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class="text-right">
                                                {{$report->payment > 0 ? number_format($report->payment) . '/=' : ''}}</td>
                                            <td class="text-right">{{number_format($total_summary['balance'])}}/=</td>
                                            <td class="">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                        data-toggle="dropdown">
                                                        <i class="fa fa-list"></i> <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="{{ route('purchase.delete', ['invoice' => $report->id, 'view' => 'other']) }}"
                                                                class="btn btn-danger" id="delete"><i class="fa fa-trash"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @elseif ($report->type == 'prev')
                                        @php
                                            $total_summary['balance'] = $total_summary['balance'] ?? 0;
                                            $total_summary['balance'] += $report->balance;
                                        @endphp
                                        <tr>
                                            <td style="white-space: nowrap;">--</td>
                                            <td class="text-center">--</td>
                                            <td class="text-left">Before Balance</td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class="text-right"></td>
                                            <td class="text-right">{{number_format($total_summary['balance'])}}/=</td>
                                            <td></td>
                                        </tr>
                                    @elseif($report->type == 'purchase')
                                        @php
                                            $qty_summary = [];
                                            $qty_summary_dis = [];
                                            $qty_summary_purchase = [];
                                            $qty_mt = 0;

                                            $total_tk = $report->total_price - $report->price_discount;
                                            $total_payment = $report->payment + $report->vat + $report->carring + $report->other_charge;
                                            $balance = $total_tk - $total_payment;
                                            $total_summary['balance'] = $total_summary['balance'] ?? 0;
                                            $total_summary['balance'] += $balance;
                                        @endphp
                                        <tr style="background-color: #e1f3eb" data-type="purchase">
                                            <td>{{date('d-m-Y', strtotime($report->date))}}</td>
                                            <td class="text-center">{{$report->id}}</td>
                                            <td>
                                                @if(isset($products[$report->id]))
                                                    @foreach ($products[$report->id] as $product)
                                                        @php
                                                            $type = $product->product->type ?? 'pc';
                                                        @endphp
                                                        <p class="mb-0 text-left">
                                                            {{ $product->product_code}} -
                                                            {{ $product->product_name}} {{'('}}{{ $product->product->size->description ?? 'N/A'}}{{')'}}
                                                            - {{ $product->quantity - $product->discount_qty}} {{ trans_choice('labels.'.$type, ($product->quantity - $product->discount_qty))}}{{' @ '}}{{ $product->unit_price}}/=
                                                            {{ $product->total_price}}/=
                                                        </p>
                                                    @endforeach
                                                @endif
                                            </td>

                                            {{-- Quantity --}}
                                            <td>
                                                @if(isset($products[$report->id]))
                                                    @foreach ($products[$report->id] as $product)
                                                        @php
                                                            $type = $product->product->type ?? 'pc';
                                                            $qty_summary_purchase[$type] = $qty_summary_purchase[$type] ?? 0;
                                                            $qty_summary_purchase[$type] += ($product->quantity - $product->discount_qty);
                                                            $qty_mt += ($product->product->size->name ?? 0) * ($product->quantity - $product->discount_qty);
                                                        @endphp
                                                    @endforeach
                                                @endif
                                                @php
                                                    $total_summary['weight'] += $qty_mt;
                                                    ksort($qty_summary_purchase);
                                                @endphp
                                                <div>
                                                    @if(isset($qty_summary_purchase) && count($qty_summary_purchase) > 0)
                                                        @foreach ($qty_summary_purchase as $key => $value)
                                                            @if($value > 0)
                                                                @php
                                                                    $total_summary['qty'][$key] = $total_summary['qty'][$key] ?? 0;
                                                                    $total_summary['qty'][$key] += $value;
                                                                @endphp
                                                                {{ $value }} {{trans_choice('labels.' . strtolower($key), $value)}}
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>

                                            {{-- Weight --}}
                                            <td>{{$qty_mt / 1000}} MT</td>

                                            {{-- Total Tk --}}
                                            <td class="text-right">{{$total_tk > 0 ? number_format($total_tk) . '/=' : ''}}</td>

                                            {{-- Payment --}}
                                            <td class="text-right">
                                                {{$total_payment > 0 ? number_format($total_payment) . '/=' : ''}}</td>

                                            {{-- Due Amount --}}
                                            <td class="text-right">{{number_format($total_summary['balance'])}}/=</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                        data-toggle="dropdown">
                                                        <i class="fa fa-list"></i> <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="{{ route('purchase.delete', ['invoice' => $report->id, 'view' => 'purchase']) }}"
                                                                class="btn btn-danger" id="delete"><i class="fa fa-trash"></i></a>
                                                        </li>
                                                        <li><a href="{{ route('purchase.view', ['invoice' => $report->id, 'view' => 'purchase']) }}"
                                                                class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @elseif($report->type == 'payment')
                                        @php
                                            $total_summary['balance'] = $total_summary['balance'] ?? 0;
                                            $total_summary['balance'] -= $report->payment;
                                        @endphp
                                        <tr style="background-color: #e1e3f3" data-type="payment">
                                            <td class="">{{date('d-m-Y', strtotime($report->date))}}</td>
                                            <td class="text-center">{{$report->id}}</td>
                                            <td class="text-left">
                                                {{ $report->payment_by }}
                                                {{ $report->bank_title ? ' - ' . $report->bank_title : '' }}
                                                {{ $report->payment_remarks ? ' - ' . $report->payment_remarks : '' }}

                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">
                                                {{$report->payment > 0 ? number_format($report->payment) . '/=' : ''}}</td>
                                            <td class="text-right">{{number_format($total_summary['balance'])}}/=</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                        data-toggle="dropdown">
                                                        <i class="fa fa-list"></i> <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="{{ route('purchase.delete', ['invoice' => $report->id, 'view' => 'payment']) }}"
                                                                class="btn btn-danger" id="delete"><i class="fa fa-trash"></i></a>
                                                        </li>
                                                        <li><a href="{{ route('purchase.view', ['invoice' => $report->id, 'view' => 'payment']) }}"
                                                                class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @elseif($report->type == 'return')
                                        @php
                                            $qty_summary = [];
                                            $qty_summary_return = [];
                                            $qty_mt = 0;
                                            $t_payment = $report->carring + $report->other_charge + $report->payment;
                                            $total = $report->total_price - $report->price_discount + $report->carring + $report->other_charge;

                                            $total_summary['balance'] = $total_summary['balance'] ?? 0;
                                            $total_summary['balance'] -= $total;
                                        @endphp
                                        <tr style="background-color: #f3e1e1" data-type="return">
                                            <td class="">
                                                <span class="d-block text-nowrap">{{date('d-m-Y', strtotime($report->date))}}</span>
                                            </td>
                                            <td class="text-center">{{$report->id}}</td>
                                            <td>
                                                @if(isset($products[$report->id]))
                                                    @foreach ($products[$report->id] as $product)
                                                        @php
                                                            $type = $product->product->type ?? 'pc';
                                                        @endphp
                                                        <p class="mb-0 text-left">
                                                            {{ $product->product_code}} -
                                                            {{ $product->product_name}} {{'('}}{{ $product->product->size->description ?? 'N/A'}}{{')'}} -
                                                            {{ $product->quantity}} {{ trans_choice('labels.'.$type, $product->quantity)}}{{' @ '}}{{ $product->unit_price}}/=
                                                            {{ $product->total_price}}/=
                                                        </p>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($products[$report->id]))
                                                    @foreach ($products[$report->id] as $product)
                                                        @php
                                                            $type = $product->product->type ?? 'pc';
                                                            $qty_summary_return[$type] = $qty_summary_return[$type] ?? 0;
                                                            $qty_summary_return[$type] += $product->quantity;
                                                            $qty_mt += ($product->product->size->name ?? 0) * $product->quantity;
                                                        @endphp
                                                    @endforeach
                                                @endif
                                                @php
                                                    $total_summary['weight'] -= $qty_mt;
                                                    ksort($qty_summary_return);
                                                @endphp
                                                <div>
                                                    @if(isset($qty_summary_return) && count($qty_summary_return) > 0)
                                                        (-)
                                                        @foreach ($qty_summary_return as $key => $value)
                                                            @php
                                                                $total_summary['qty'][$key] = $total_summary['qty'][$key] ?? 0;
                                                                $total_summary['qty'][$key] -= $value;
                                                            @endphp
                                                            {{ $value }} {{trans_choice('labels.' . strtolower($key), $value)}}
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>

                                            {{-- Weight --}}
                                            <td>-{{$qty_mt / 1000}} MT</td>

                                            {{-- Total Tk --}}
                                            <td class="text-right">-{{number_format($total)}}/=</td>

                                            {{-- Payment --}}
                                            <td class="text-right">{{$t_payment ? number_format($t_payment) . '/=' : ''}}</td>

                                            {{-- Due Amount --}}
                                            <td class="text-right">{{number_format($total_summary['balance'])}}/=</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                        data-toggle="dropdown">
                                                        <i class="fa fa-list"></i> <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="{{ route('purchase.delete', ['invoice' => $report->id, 'view' => 'return']) }}"
                                                                class="btn btn-danger" id="delete"><i class="fa fa-trash"></i></a>
                                                        </li>
                                                        <li><a href="{{ route('purchase.view', ['invoice' => $report->id, 'view' => 'return']) }}"
                                                                class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>
                                        @if (isset($total_summary) && count($total_summary['qty']) > 0)
                                            @php
                                                ksort($total_summary['qty']);
                                            @endphp
                                            @foreach ($total_summary['qty'] as $key => $value)
                                                <div>{{ $value }} {{trans_choice('labels.' . strtolower($key), $value)}}</div>
                                            @endforeach
                                        @endif
                                    </th>
                                    <th>
                                        @php
                                            $weightT = $total_summary['weight'] / 1000;
                                        @endphp
                                        {{formatAmount($weightT)}} {{trans_choice('labels.ton', $total_summary['weight'])}}
                                    </th>
                                    <th class="text-right">{{formatAmount($total_summary['purchase_tk'] ?? 0)}}/=</th>
                                    <th class="text-right">{{formatAmount($total_summary['payment_tk'] ?? 0)}}/=</th>
                                    <th class="text-right">{{formatAmount($total_summary['balance'] ?? 0)}}/=</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                        @if (method_exists($supplier_ledger_info, 'links'))
                            {{ $supplier_ledger_info->links() }}
                        @endif
                    </div>
                @else
                    <p class="text-center">No data found</p>
                @endif
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        jQuery(document).ready(function ($) {
            $('#get_supplier_id').select2();

            $('#start_date_picker').datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });
            $('#end_date_picker').datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });
        })
    </script>
@endpush
