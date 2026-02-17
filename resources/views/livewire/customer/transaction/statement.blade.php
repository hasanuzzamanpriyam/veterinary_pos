@section('page-title', 'Customer Statement')

@php
    $type = 0;
@endphp
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel p-3">
        <div class="x_title">
            <div class="header-title d-flex align-items-center justify-content-between gap-2">
                <h2>Customer Statement</h2>
                @if($customer)
                    <h6 class="text-dark mb-0">
                        {{$customer->name}}{{$customer->address ? ' - ' . $customer->address : ''}}{{$customer->mobile ? ' - ' . $customer->mobile : ''}}
                    </h6>
                @endif
                <a href="{{route('customer.index.due')}}" class="ml-3 cursor-pointer"><i class="fa fa-arrow-left"></i>
                    Back</a>
            </div>
        </div>
        <div class="search" wire:ignore>
            <form action="{{route('customer.transactions.statement')}}" method="get">
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
                            <label class="py-1 border" for="supplier_search">Select Customer</label>
                            <div class="form-group">
                                <select id="get_customer_id" name="id" placeholder="search supplier"
                                    class="form-control">
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{$customer->id}}" @selected($customer->id == $customer_id)>
                                            {{$customer->name}} - {{$customer->address}} - {{$customer->mobile}}</option>
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
                @if(count($customer_ledger_info) > 0)
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
                @if(count($customer_ledger_info) > 0)
                    <div class="table-responsive">
                        <table id="datatable-responsiverr" class="table table-striped table-bordered" cellspacing="0">
                            <thead>
                                <tr class="text-center">
                                    <th>Date</th>
                                    <th>Invoice</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Sale (Tk)</th>
                                    <th>Collection</th>
                                    <th>Due Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_summary = [
                                        'qty' => [],
                                        'sale_tk' => 0,
                                        'collection_tk' => 0,
                                        'balance' => 0
                                    ];
                                @endphp
                                @foreach($customer_ledger_info as $customer_info)

                                    @php
                                        if ($customer_info->type != 'prev' && $customer_info->payment > 0) {
                                            $total_summary['collection_tk'] = $total_summary['collection_tk'] ?? 0;
                                            $total_summary['collection_tk'] += $customer_info->payment;
                                        }

                                        $x_total = $customer_info->total_price - $customer_info->price_discount + $customer_info->vat + $customer_info->carring + $customer_info->other_charge;

                                        if ($customer_info->type == 'sale' || $customer_info->type == 'return') {
                                            $total_summary['sale_tk'] = $total_summary['sale_tk'] ?? 0;
                                            $total_summary['sale_tk'] += $customer_info->type == 'sale' ? $x_total : -$x_total;
                                        }

                                    @endphp
                                    @if($customer_info->type == 'other')
                                        @php
                                            $total_summary['balance'] = $total_summary['balance'] ?? 0;
                                            $total_summary['balance'] += $customer_info->balance;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{date('d-m-Y', strtotime($customer_info->date))}}</td>
                                            <td class="text-center">{{$customer_info->id}}</td>
                                            <td class="text-left">{{$customer_info->received_by}}</td>
                                            <td class=""></td>
                                            <td class=""></td>
                                            <td class="text-right">{{number_format($customer_info->payment) ?? 0}}/=</td>
                                            <td class="text-right">{{number_format($total_summary['balance'])}}/=</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                        data-toggle="dropdown">
                                                        <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="{{ route('sales.delete', ['invoice' => $customer_info->id, 'view' => 'other']) }}"
                                                                class="btn btn-danger" id="delete"><i class="fa fa-trash"></i></a>
                                                        </li>
                                                        <li><a href="{{ route('sales.view', $customer_info->id) }}"
                                                                class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @elseif($customer_info->type == 'prev')
                                        @php
                                            $total_summary['balance'] = $total_summary['balance'] ?? 0;
                                            $total_summary['balance'] += $customer_info->balance;
                                        @endphp
                                        <tr>
                                            <td>--</td>
                                            <td>--</td>
                                            <td class="text-left">Before Balance</td>
                                            <td></td>
                                            <td class=""></td>
                                            <td class="text-right"></td>
                                            <td class="text-right">{{number_format($total_summary['balance'])}}/=</td>
                                            <td></td>
                                        </tr>
                                    @elseif($customer_info->type == 'sale')
                                        @php
                                            $filtered_products = $products->where('transaction_id', $customer_info->id);
                                            $qty_summary = [];

                                            $total_tk = $customer_info->total_price - $customer_info->price_discount + $customer_info->vat + $customer_info->carring + $customer_info->other_charge;
                                            $balance = $total_tk - $customer_info->payment;
                                            $total_summary['balance'] = $total_summary['balance'] ?? 0;
                                            $total_summary['balance'] += $balance;
                                        @endphp
                                        <tr style="background-color: #e1f3eb" data-type="sale">
                                            <td>{{date('d-m-Y', strtotime($customer_info->date))}}</td>
                                            <td class="text-center">{{$customer_info->id}}</td>
                                            <td class="text-left">{{'Sale'}}
                                                {{$customer_info->product_store_id ? ' : ' . $customer_info->store->name : ''}}
                                                {{$customer_info->transport_no ? ' : ' . $customer_info->transport_no : ''}}</td>
                                            <td class="text-center">
                                                @foreach ($filtered_products as $product)
                                                    @php
                                                        $product_info = DB::table('products')->where('id', $product->product_id)->first();
                                                        $type = $product_info->type ?? '';
                                                        $qty_summary[$type] = $qty_summary[$type] ?? 0;
                                                        $qty_summary[$type] += ($product->quantity - $product->discount_qty);
                                                    @endphp
                                                @endforeach
                                                <div>
                                                    @if(isset($qty_summary) && count($qty_summary) > 0)
                                                        @php
                                                            ksort($qty_summary)
                                                        @endphp
                                                        @foreach ($qty_summary as $key => $value)
                                                            @php
                                                                $total_summary['qty'][$key] = $total_summary['qty'][$key] ?? 0;
                                                                $total_summary['qty'][$key] += $value;
                                                            @endphp
                                                            {{ $value }} {{trans_choice('labels.' . strtolower($key), $value)}}
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>

                                            <td class="text-right">
                                                {{ $x_total }}/=
                                            </td>
                                            @if($customer_info->payment)
                                                <td class="text-right">{{number_format($customer_info->payment)}}/=</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td class="text-right">{{number_format($total_summary['balance'])}}/=</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                        data-toggle="dropdown">
                                                        <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="{{ route('sales.delete', ['invoice' => $customer_info->id, 'view' => 'sale']) }}"
                                                                class="btn btn-danger" id="delete"><i class="fa fa-trash"></i></a>
                                                        </li>
                                                        <li><a href="{{ route('sales.view', $customer_info->id) }}"
                                                                class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @elseif($customer_info->type == 'collection')
                                        @php
                                            $total_summary['balance'] = $total_summary['balance'] ?? 0;
                                            $total_summary['balance'] -= $customer_info->payment;
                                        @endphp
                                        <tr style="background-color: #e1e3f3" data-type="collection">
                                            <td class="">{{date('d-m-Y', strtotime($customer_info->date))}}</td>
                                            <td class="text-center">{{$customer_info->id}}</td>
                                            <td class="text-left">
                                                {{ 'Collection' }}
                                                {{ $customer_info->payment_by ? ' : ' . $customer_info->payment_by : '' }}
                                                {{ $customer_info->bank_title ? ' - ' . $customer_info->bank_title : '' }}
                                                {{ $customer_info->received_by ? ' - ' . $customer_info->received_by : '' }}

                                            </td>
                                            <td></td>
                                            <td></td>
                                            @if($customer_info->payment)
                                                <td class="text-right">{{number_format($customer_info->payment)}}/=</td>
                                            @else
                                                <td></td>
                                            @endif

                                            <td class="text-right">{{number_format($total_summary['balance'])}}/=</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                        data-toggle="dropdown">
                                                        <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="{{ route('sales.delete', ['invoice' => $customer_info->id, 'view' => 'collection']) }}"
                                                                class="btn btn-danger" id="delete"><i class="fa fa-trash"></i></a>
                                                        </li>
                                                        <li><a href="{{ route('collection.view', $customer_info->id) }}"
                                                                class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @elseif($customer_info->type == 'return')
                                        @php
                                            $filtered_products = $products->where('transaction_id', $customer_info->id);
                                            $qty_summary = [];

                                            $total = $customer_info->total_price - ($customer_info->carring + $customer_info->other_charge);
                                            $total_summary['balance'] = $total_summary['balance'] ?? 0;
                                            $total_summary['balance'] -= $total;
                                        @endphp
                                        <tr style="background-color: #f3e1e1" data-type="return">
                                            {{-- Date --}}
                                            <td class="">
                                                <span
                                                    class="d-block text-nowrap">{{date('d-m-Y', strtotime($customer_info->date))}}</span>
                                            </td>

                                            {{-- Invoice --}}
                                            <td class="text-center">{{$customer_info->id}}</td>

                                            {{-- Description --}}
                                            <td class="text-left">
                                                <span class="d-block text-nowrap">Returned</span>

                                            </td>

                                            {{-- Quantity --}}
                                            <td>
                                                @foreach ($filtered_products as $product)
                                                    @php
                                                        $product_info = DB::table('products')->where('id', $product->product_id)->first();
                                                        $type = $product_info->type;
                                                        $qty_summary[$type] = $qty_summary[$type] ?? 0;
                                                        $qty_summary[$type] += $product->quantity;
                                                    @endphp
                                                @endforeach
                                                @php
                                                    ksort($qty_summary);
                                                @endphp
                                                <div>
                                                    @if(isset($qty_summary) && count($qty_summary) > 0)
                                                        @foreach ($qty_summary as $key => $value)
                                                            @php
                                                                $total_summary['qty'][$key] = $total_summary['qty'][$key] ?? 0;
                                                                $total_summary['qty'][$key] -= $value;
                                                            @endphp
                                                            {{ $value }} {{trans_choice('labels.' . strtolower($key), $value)}}
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>

                                            {{-- Total Value --}}
                                            <td class="text-right"></td>

                                            {{-- Collection --}}
                                            <td class="text-right">{{ number_format($total) }}/=</td>

                                            {{-- Due Amount --}}
                                            <td class="text-right">{{number_format($total_summary['balance'])}}/=</td>

                                            {{-- Action --}}
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                        data-toggle="dropdown">
                                                        <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="{{ route('sales.delete', ['invoice' => $customer_info->id, 'view' => 'return']) }}"
                                                                class="btn btn-danger" id="delete"><i class="fa fa-trash"></i></a>
                                                        </li>
                                                        <li><a href="{{ route('sales.return.view', $customer_info->id) }}"
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
                                    <th class="text-right">{{formatAmount($total_summary['sale_tk'] ?? 0)}}/=</th>
                                    <th class="text-right">{{formatAmount($total_summary['collection_tk'] ?? 0)}}/=</th>
                                    <th class="text-right">{{formatAmount($total_summary['balance'] ?? 0)}}/=</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                        @if (method_exists($customer_ledger_info, 'links'))
                            {{ $customer_ledger_info->links() }}
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
            $('#get_customer_id').select2();

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