@php
$type = 0;
@endphp
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Sales Report</h2>
                <a href="#" class="mr-auto ml-3 cursor-pointer" onclick="history.back()"><i class="fa fa-close"></i></a>
                @if($start_date && $end_date && $customer_info)
                    <a target="_blank" href="{{ route('sales.customer.report.pdf',[$start_date, $end_date, $customer_info->id ?? 0]) }}"  class="btn btn-primary btn-sm p-2"> Download <i class="fa fa-file-pdf-o text-white"></i></a>
                @elseif($start_date && $end_date)
                    <a  target="_blank" href="{{ route('sales.all.report.pdf',[$start_date, $end_date]) }}" class="btn btn-primary btn-sm p-2"> Download <i class="fa fa-file-pdf-o text-white"></i></a>
                @endif
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
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <form wire:submit.prevent=salesReportSearch()>
                        <div class="row justify-content-center">
                            <div class="col-lg-2 col-md-2 col-sm-12">
                                <div class="supplier-search-area">
                                    <label  class="py-1 border" for="supplier_search">From Date</label>
                                    <div class="input-group date" id="datepicker33">
                                        <input name="start_date" wire:model="start_date" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                </div>



                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-12">
                                <div class="supplier-search-area">
                                    <label  class="py-1 border" for="supplier_search">To Date</label>
                                    <div class="input-group date" id="end_datepicker">
                                        <input name="end_date" wire:model="end_date" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="supplier-search-area">
                                    <label  class="py-1 border" for="supplier_search">Select Customer</label>
                                    <div class="form-group" wire:ignore>
                                        <select type="search" id="get_customer_id" name="get_customer_id" placeholder="search supplier" class="form-control">
                                            <option value=""></option>
                                            @foreach ($customers as $customer)
                                                <option value="{{$customer->id}}">
                                                    {{$customer->name}} -
                                                    {{$customer->address}} -
                                                    {{$customer->mobile}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="supplier-search-button pt-4">
                                    <div class="form-group pt-3">
                                        <button type="submit" class="btn btn-success">Search</button>
                                        <button type="button" wire:click="resetSupplier" class="btn btn-danger">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="due-list-area mt-4">
                                <div class="x_title">
                                    @if($reports)
                                    <h3 class="text-center"> <strong>Customer Wise Sales Report</strong></h3>
                                    @elseif($all_reports)
                                    <h3 class="text-center"> <strong>Total Sales Report</strong></h3>
                                    @endif
                                </div>

                                <div class="customer-info-area py-2">
                                    @if($customer_info)
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                <p class="border border-dark p-2">ID: {{ $customer_info->id }}</p>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                <p class="border border-dark p-2">Name: {{$customer_info->name }}</p>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                <p class="border border-dark p-2 text-wrap">Address: {{  $customer_info->address }}</p>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                <p class="border border-dark p-2">Mobile: {{  $customer_info->mobile}}</p>
                                            </div>
                                        </div>
                                    @endif
                                    @if($start_date && $end_date)
                                        <div class="row pt-3">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <h4 class="text-right text-dark font-weight-bold">Start Date: {{date('d-m-Y', strtotime($start_date))}}</h4>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <h4 class="text-left text-dark font-weight-bold">End Date: {{ date('d-m-Y', strtotime($end_date))}}</h4>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @if($reports)
                                    <div class="sales_list_table">
                                        <table id="datatable-responsive"
                                            class="table table-striped table-bordered dt-responsive nowrap category_list_table"
                                            cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th class="all">Date</th>
                                                    <th class="all">Action</th>
                                                    <th class="all">Invoice No.</th>
                                                    <th class="all">Customer Name</th>
                                                    <th class="all">Customer Address</th>
                                                    <th class="all">Store/Warehouse</th>
                                                    <th class="all">Total Qty</th>
                                                    <th class="all">Dis. Qty</th>
                                                    <th class="all">Sales Qty</th>
                                                    <th class="all">Total Value</th>
                                                    <th class="all">Discount</th>
                                                    <th class="all">Vat</th>
                                                    <th class="all">Carring</th>
                                                    <th class="all">Others</th>
                                                    <th class="all">Total sales</th>
                                                    <th class="all">Payment </th>
                                                    <th class="all">Balance Tk</th>
                                                    <th class="all">Current Due</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php

                                                    $total_sales = count($reports);
                                                    $count = 0;

                                                @endphp

                                                    @foreach ($reports as $sales)

                                                        @php
                                                            $count = $count + 1;
                                                            $customer_info = DB::table('customers')->where('id', $sales->customer_id)->first();
                                                            $qty_summary = [];
                                                            $invoices = DB::table('customer_transaction_details')->where('transaction_id', $sales->id)->get();
                                                        @endphp

                                                        @foreach ($invoices as $invoice)

                                                            @php
                                                                $product_info = DB::table('products')->where('id', $invoice->product_id)->first();
                                                                $type = $product_info->type;
                                                                $qty_summary['total'][$type] = $qty_summary['total'][$type] ?? 0;
                                                                $qty_summary['discount'][$type] = $qty_summary['discount'][$type] ?? 0;
                                                                $qty_summary['sale'][$type] = $qty_summary['sale'][$type] ?? 0;
                                                                $qty_summary['total'][$type] += $invoice->quantity;
                                                                $qty_summary['discount'][$type] += $invoice->discount_qty;
                                                                $qty_summary['sale'][$type] += ($invoice->quantity - $invoice->discount_qty);
                                                            @endphp

                                                        @endforeach

                                                        @if ($sales->type == 'other')

                                                            <tr>
                                                                <td class="text-center">{{ date('d-m-Y', strtotime($sales->date)) }} </td>
                                                                <td>
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                                                            data-toggle="dropdown">
                                                                            Action <span class="caret"></span></button>
                                                                        <ul class="dropdown-menu" role="menu">
                                                                            @if ($count == $total_sales)
                                                                            <li><a href="{{ route('sales.delete', ['invoice' => $sales->id, 'view' => 'other']) }}"
                                                                                class="btn btn-danger" id="delete"><i
                                                                                    class="fa fa-trash"></i></a></li>
                                                                            @endif
                                                                            <li><a href="{{ route('sales.view', $sales->id) }}"
                                                                                    class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                                <td class="text-center">{{ $sales->id }}</td>
                                                                <td class="">{{ $sales->customer->name }}</td>
                                                                <td class="">{{ $sales->customer->address }}</td>

                                                                <td class="text-center">{{ $sales->store?->name }}</td>

                                                                {{-- Total Qty --}}
                                                                <td class="text-center"></td>
                                                                {{-- Discount Qty --}}
                                                                <td class="text-center"></td>
                                                                {{-- Sale Qty --}}
                                                                <td class="text-center"></td>


                                                                <td class="text-right"></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>

                                                                <td class="text-right"></td>
                                                                <td></td>
                                                                <td class="text-right"></td>
                                                                <td class="text-right">{{ $sales->balance}}</td>
                                                            </tr>

                                                        @else
                                                            <tr>
                                                                <td class="text-center">{{ date('d-m-Y', strtotime($sales->date)) }} </td>
                                                                <td>
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                                                            data-toggle="dropdown">
                                                                            Action <span class="caret"></span></button>
                                                                        <ul class="dropdown-menu" role="menu">
                                                                            <li><a href="{{ route('sales.delete', ['invoice' => $sales->id, 'view' => 'sale']) }}"
                                                                                    class="btn btn-danger" id="delete"><i
                                                                                        class="fa fa-trash"></i></a></li>
                                                                            <li><a href="{{ route('sales.view', $sales->id) }}"
                                                                                    class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                                <td class="text-center">{{ $sales->id }}</td>
                                                                <td class="">{{ $sales->customer->name }}</td>
                                                                <td class="">{{ $sales->customer->address }}</td>

                                                                <td class="text-center">{{ $sales->store?->name }}</td>

                                                                {{-- Total Qty --}}
                                                                <td class="text-center">
                                                                    @foreach ($qty_summary['total'] ?? [] as $type => $qty)
                                                                        {{$qty > 0 ? $qty . ' ' . trans_choice('labels.' . $type, $qty) : ''}}
                                                                    @endforeach
                                                                </td>
                                                                {{-- Discount Qty --}}
                                                                <td class="text-center">
                                                                    @foreach ($qty_summary['discount'] ?? [] as $type => $qty)
                                                                        {{$qty > 0 ? $qty . ' ' . trans_choice('labels.' . $type, $qty) : ''}}
                                                                    @endforeach
                                                                </td>
                                                                {{-- Sale Qty --}}
                                                                <td class="text-center">
                                                                    @foreach ($qty_summary['sale'] ?? [] as $type => $qty)
                                                                        {{$qty > 0 ? $qty . ' ' . trans_choice('labels.' . $type, $qty) : ''}}
                                                                    @endforeach
                                                                </td>


                                                                <td class="text-right">{{ $sales->total_price }}/=</td>
                                                                @if (empty($sales->price_discount))
                                                                    <td></td>
                                                                @else
                                                                    <td class="text-right">{{ $sales->price_discount }}/=</td>
                                                                @endif
                                                                @if (empty($sales->vat))
                                                                    <td></td>
                                                                @else
                                                                    <td class="text-right">{{ $sales->vat }}/=</td>
                                                                @endif

                                                                @if (empty($sales->carring))
                                                                    <td></td>
                                                                @else
                                                                    <td class="text-right">{{ $sales->carring }}/=</td>
                                                                @endif
                                                                @if (empty($sales->other_charge))
                                                                    <td></td>
                                                                @else
                                                                    <td class="text-right">{{ $sales->other_charge }}/=</td>
                                                                @endif
                                                                <td class="text-right">
                                                                    {{ $sales->total_price - $sales->price_discount + $sales->vat + $sales->carring + $sales->other_charge }}/=
                                                                </td>
                                                                @if (empty($sales->payment))
                                                                    <td></td>
                                                                @else
                                                                    <td class="text-right">{{ $sales->payment }}/=</td>
                                                                @endif

                                                                <td class="text-right">{{  ($sales->total_price - $sales->price_discount + $sales->vat + $sales->carring + $sales->other_charge ) - $sales->payment }} /=</td>
                                                                <td class="text-right">{{ $sales->balance}}</td>
                                                            </tr>
                                                        @endif

                                                    @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                @elseif($all_reports)
                                    {{-- @dd($all_reports) --}}

                                    <div class="sales_list_table">
                                        <table id="datatable-responsive"
                                            class="table table-striped table-bordered dt-responsive nowrap category_list_table"
                                            cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th class="all">Date</th>
                                                    <th class="all">Action</th>
                                                    <th class="all">Invoice No.</th>
                                                    <th class="all">Customer Name</th>
                                                    <th class="all">Customer Address</th>
                                                    <th class="all">Store/Warehouse</th>
                                                    <th class="all">Total Qty</th>
                                                    <th class="all">Dis. Qty</th>
                                                    <th class="all">Sales Qty</th>
                                                    <th class="all">Total Value</th>
                                                    <th class="all">Discount</th>
                                                    <th class="all">Vat</th>
                                                    <th class="all">Carring</th>
                                                    <th class="all">Others</th>
                                                    <th class="all">Total sales</th>
                                                    <th class="all">Payment </th>
                                                    <th class="all">Balance Tk</th>
                                                    <th class="all">Current Due</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                    {{-- @dd($all_reports); --}}

                                                    @foreach ($all_reports as $sales)

                                                        @php
                                                            $qty_summary = [];
                                                            $invoices = DB::table('customer_transaction_details')->where('transaction_id', $sales->id)->get();
                                                        @endphp

                                                        @foreach ($invoices as $invoice)

                                                            @php
                                                                $product_info = DB::table('products')->where('id', $invoice->product_id)->first();
                                                                $type = $product_info->type;
                                                                $qty_summary['total'][$type] = $qty_summary['total'][$type] ?? 0;
                                                                $qty_summary['discount'][$type] = $qty_summary['discount'][$type] ?? 0;
                                                                $qty_summary['sale'][$type] = $qty_summary['sale'][$type] ?? 0;
                                                                $qty_summary['total'][$type] += $invoice->quantity;
                                                                $qty_summary['discount'][$type] += $invoice->discount_qty;
                                                                $qty_summary['sale'][$type] += ($invoice->quantity - $invoice->discount_qty);
                                                            @endphp

                                                        @endforeach

                                                        <tr>
                                                            <td class="text-center">{{ date('d-m-Y', strtotime($sales->date)) }} </td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                                                        data-toggle="dropdown">
                                                                        Action <span class="caret"></span></button>
                                                                    <ul class="dropdown-menu" role="menu">
                                                                        <li><a href="{{ route('sales.delete', ['invoice' => $sales->id, 'view' => 'sale']) }}"
                                                                                class="btn btn-danger" id="delete"><i
                                                                                    class="fa fa-trash"></i></a></li>
                                                                        <li><a href="{{ route('sales.view', $sales->id) }}"
                                                                                class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">{{ $sales->id }}</td>
                                                            <td class="">{{ $sales->customer->name }}</td>
                                                            <td class="">{{ $sales->customer->address }}</td>

                                                            <td class="text-center">{{ $sales->store?->name }}</td>

                                                            {{-- Total Qty --}}
                                                            <td class="text-center">
                                                                @foreach ($qty_summary['total'] ?? [] as $type => $qty)
                                                                    {{$qty > 0 ? $qty . ' ' . trans_choice('labels.' . $type, $qty) : ''}}
                                                                @endforeach
                                                            </td>
                                                            {{-- Discount Qty --}}
                                                            <td class="text-center">
                                                                @foreach ($qty_summary['discount'] ?? [] as $type => $qty)
                                                                    {{$qty > 0 ? $qty . ' ' . trans_choice('labels.' . $type, $qty) : ''}}
                                                                @endforeach
                                                            </td>
                                                            {{-- Sale Qty --}}
                                                            <td class="text-center">
                                                                @foreach ($qty_summary['sale'] ?? [] as $type => $qty)
                                                                    {{$qty > 0 ? $qty . ' ' . trans_choice('labels.' . $type, $qty) : ''}}
                                                                @endforeach
                                                            </td>


                                                            <td class="text-right">{{ $sales->total_price }}/=</td>
                                                            @if (empty($sales->price_discount))
                                                                <td></td>
                                                            @else
                                                                <td class="text-right">{{ $sales->price_discount }}/=</td>
                                                            @endif
                                                            @if (empty($sales->vat))
                                                                <td></td>
                                                            @else
                                                                <td class="text-right">{{ $sales->vat }}/=</td>
                                                            @endif

                                                            @if (empty($sales->carring))
                                                                <td></td>
                                                            @else
                                                                <td class="text-right">{{ $sales->carring }}/=</td>
                                                            @endif
                                                            @if (empty($sales->other_charge))
                                                                <td></td>
                                                            @else
                                                                <td class="text-right">{{ $sales->other_charge }}/=</td>
                                                            @endif
                                                            <td class="text-right">
                                                                {{ $sales->total_price - $sales->price_discount + $sales->vat + $sales->carring + $sales->other_charge }}/=
                                                            </td>
                                                            @if (empty($sales->payment))
                                                                <td></td>
                                                            @else
                                                                <td class="text-right">{{ $sales->payment }}/=</td>
                                                            @endif
                                                                <td class="text-right">{{($sales->total_price - $sales->price_discount + $sales->vat + $sales->carring + $sales->other_charge ) - $sales->payment }} /=</td>

                                                                <td class="text-right">{{ $sales->balance}}</td>
                                                        </tr>
                                                    @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>

    $(document).ready(function () {

           $('#get_customer_id').select2({
            placeholder: 'Select Customer from here',
           });

           $('#get_customer_id, input[name="start_date"], input[name="end_date"]' ).on('change', function (e){
                @this.getCustomer(e.target.name, e.target.value);

            });

            $('table.category_list_table').DataTable();

            $(document).on('dataUpdated', function () {
                const timeout = setTimeout(() => {
                    $('table.category_list_table').DataTable({
                        // Your DataTable configuration here
                        "lengthMenu": [
                            [10, 25, 50, 100, -1],
                            [10, 25, 50, 100, "All"]
                        ]
                    });
                    clearTimeout(timeout);
                }, 10);
            })


            $('#datepicker33').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });

            $('#datepicker33 input[name=start_date]').on('change', function (e) {
                // console.log(e.target.value)
                @this.set('start_date', e.target.value);
            });


                // end date picker

            $('#end_datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });

            $('#end_datepicker input[name=end_date]').on('change', function (e) {
                // console.log(e.target.value)
                @this.set('end_date', e.target.value);
            });

    });
    </script>

@endpush
