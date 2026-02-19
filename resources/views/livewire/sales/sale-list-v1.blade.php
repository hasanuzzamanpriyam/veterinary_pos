@section('page-title', 'Sale List 1')
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto">Sale List-1</h2>
                <a href="{{route('live.sales.create')}}" class="btn btn-md btn-primary"> <i class="fa fa-plus"
                        aria-hidden="true"></i> Add New Sale</a>
            </div>
        </div>
        <div class="x_content p-3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    {{cute_loader()}}
                    <div class="card-box flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
                        <div class="form-group">
                            <select id="perpage" class="form-control form-control-sm" wire:model.live="perPage">
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
                                        <div class="input-group date" id="start_date">
                                            <input name="start_date" type="text" class="form-control form-control-sm"
                                                placeholder="Start Date">
                                            <div class="input-group-addon py-half">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group date" id="end_date">
                                            <input name="end_date" type="text" class="form-control form-control-sm"
                                                placeholder="End Date">
                                            <div class="input-group-addon py-half">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" value="Search" class="btn btn-success btn-sm">
                                    </div>
                                    <div class="form-group">
                                        <button type="reset" wire:click="searchReset"
                                            class=" btn btn-danger btn-sm">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <div class="table-responsive">
                            <table id="salesList" class="table table-striped table-bordered nowrap" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th class="all">Date</th>
                                        <th class="all">Invoice</th>
                                        <th class="all">Customer Name</th>
                                        <th class="all">Address</th>
                                        <th class="all">Mobile</th>
                                        <th class="all">Delivery Info</th>
                                        <th class="all">Particular</th>
                                        <th class="all">Sales Qty</th>
                                        <th class="all">Sale Tk</th>
                                        <th class="all">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $g_total_summary = [];
                                    @endphp
                                    @foreach ($customer_ledger as $sales)

                                        @php
                                            $qty_summary = [];
                                            $filtered_products = $products->get($sales->id, collect());
                                        @endphp

                                        <tr>
                                            {{-- Date --}}
                                            <td class="text-center">{{ date('d-m-Y', strtotime($sales->date)) }} </td>

                                            {{-- Invoice No. --}}
                                            <td class="text-center">{{ $sales->id }}</td>

                                            {{-- Customer Name --}}
                                            <td class="text-left">{{ $sales->customer->name ?? '' }}</td>
                                            <td class="text-left">{{ $sales->customer->address ?? '' }}</td>

                                            {{-- Mobile --}}
                                            <td class="text-left">
                                                {{$sales->customer->phone ?? $sales->customer->mobile ?? ''}}</td>

                                            {{-- Delivery Info --}}
                                            <td class="text-left">
                                                {{$sales->store->name ?? ''}}{{$sales->transport_no ? ", " . $sales->transport_no : ''}}
                                            </td>

                                            {{-- Particular --}}
                                            <td class="text-left">
                                                @foreach ($filtered_products as $product)
                                                    @php
                                                        $type = $product->product->type;
                                                        $qty_summary['sale'][$type] = $qty_summary['sale'][$type] ?? 0;
                                                        $qty_summary['sale'][$type] += ($product->quantity - $product->discount_qty);
                                                    @endphp
                                                    <p class="mb-0 text-left">
                                                        {{ $product->product_code}} -
                                                        @if($product->product->barcode)
                                                            <svg class="barcode-render" data-barcode="{{ $product->product->barcode }}"
                                                                style="height: 20px; vertical-align: middle; display: inline-block;"></svg> -
                                                        @endif
                                                        {{ $product->product_name}}
                                                        {{'('}}{{ $product->product->size->description}}{{')'}} -
                                                        {{ $product->quantity - $product->discount_qty}}
                                                        {{ trans_choice('labels.' . $type, ($product->quantity - $product->discount_qty))}}{{' @ '}}{{ $product->unit_price}}/=
                                                        {{ $product->total_price}}/=
                                                    </p>
                                                @endforeach
                                            </td>

                                            {{-- Sale Qty --}}
                                            <td class="text-center">
                                                @if(isset($qty_summary['sale']))
                                                    @foreach ($qty_summary['sale'] as $type => $qty)
                                                        @php
                                                            $g_total_summary['sale'][$type] = $g_total_summary['sale'][$type] ?? 0;
                                                            $g_total_summary['sale'][$type] += $qty;
                                                        @endphp
                                                        {{$qty > 0 ? $qty . ' ' . trans_choice('labels.' . $type, $qty) : ''}}
                                                    @endforeach

                                                @endif
                                            </td>

                                            {{-- Sale Tk --}}
                                            <td class="text-right">
                                                @php
                                                    $total = $sales->total_price - $sales->price_discount + $sales->vat + $sales->carring + $sales->other_charge;
                                                    $g_total_summary['total'] = $g_total_summary['total'] ?? 0;
                                                    $g_total_summary['total'] += $total;
                                                @endphp
                                                {{ number_format($total) }}/=
                                            </td>

                                            {{-- Action --}}
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button"
                                                        class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                        data-toggle="dropdown">
                                                        <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="{{ route('sales.delete', ['invoice' => $sales->id, 'view' => 'sale']) }}"
                                                                class="btn btn-danger" id="delete"><i
                                                                    class="fa fa-trash"></i></a></li>
                                                        <li><a href="{{ route('sales.view', $sales->id) }}"
                                                                class="btn btn-info"><i class="fa fa-eye"></i></a></li>
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
                                    <th class="text-center">
                                        @php
                                            if (isset($g_total_summary['sale'])) {
                                                ksort($g_total_summary['sale']);
                                            }
                                        @endphp
                                        @foreach (($g_total_summary['sale'] ?? []) as $type => $qty)
                                            <span
                                                style="white-space: nowrap;">{{$qty > 0 ? formatAmount($qty) . ' ' . trans_choice('labels.' . $type, $qty) : ''}}</span>
                                        @endforeach
                                    </th>
                                    <th class="text-right">{{formatAmount($g_total_summary['total'] ?? 0)}}/=</th>
                                    <td></td>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @if (method_exists($customer_ledger, 'links'))
                        {{ $customer_ledger->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {

            $('#start_date').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });
            $('#start_date input[name=start_date]').on('change', function (e) {
                @this.set('rw_start_date', e.target.value, false);
            });
            $('#end_date').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });
            $('#end_date input[name=end_date]').on('change', function (e) {
                @this.set('rw_end_date', e.target.value, false);
            });
        });
    </script>
@endpush