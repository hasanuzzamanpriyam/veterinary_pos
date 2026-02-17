@php
    $type = 0;
@endphp

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title">

            <div class="header-title d-flex align-items-center gap-2 p-3">
                <h2 class="mr-auto">Purchase Report</h2>
                @if($get_supplier_id && $start_date && $end_date && $reports->count() > 0)
                    <a href="{{ route('purchase.supplier.report.pdf',[$start_date, $end_date, $get_supplier_id]) }}" class="btn btn-info btn-link"> Download <i class="fa fa-file-pdf-o text-danger"></i></a>
                @elseif($start_date && $end_date && !$get_supplier_id && $reports->count() > 0)
                    <a href="{{ route('purchase.all.report.pdf',[$start_date, $end_date]) }}" class="btn btn-info btn-link"> Download <i class="fa fa-file-pdf-o text-danger"></i></a>
                @endif

            </div>

        </div>
        <div class="x_content">
            <br />
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="supplier-search-area">
                            <label class="d-block py-1 border" for="start_date">From Date:</label>
                            <div class="input-group date" id="datepicker33">
                                <input name="start_date" wire:model.live="start_date" type="text" class="form-control"
                                    placeholder="dd-mm-yyyy">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="supplier-search-area">
                            <label class=" d-block py-1 border" for="end_date">To Date:</label>
                            <div class="input-group date" id="end_datepicker">
                                <input name="end_date" wire:model.live="end_date" type="text" class="form-control"
                                    placeholder="dd-mm-yyyy">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="supplier-search-area">
                            <label class="py-1 border" for="supplier_search">Select Supplier:</label>
                            <div class="form-group" wire:ignore>
                                <select type="search" id="get_supplier_id" name="get_supplier_id"
                                    placeholder="search supplier" class="form-control">
                                    <option value="">All Suppliers</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{$supplier->id}}">
                                            {{$supplier->company_name}} -
                                            {{$supplier->address}} -
                                            {{$supplier->mobile}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="supplier-search-area">
                            <label class="py-1 border" for="per_page">Per Page:</label>
                            <select wire:model.live="perPage" class="form-control">
                                <option value="10">10</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="200">200</option>
                                <option value="all">All</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="due-list-area mt-4">
                            <div class="x_title">
                                @if($reports->count() > 0)
                                    @if($get_supplier_id && $reports->first()->supplier)
                                        <h3 class="text-center"> <strong>Supplier Wise Purchase Report</strong></h3>
                                    @else
                                        <h3 class="text-center"> <strong>Total Purchase Report</strong></h3>
                                    @endif
                                @endif
                            </div>
                            <div class="supplier-info-area py-2">
                                @if($get_supplier_id && $reports->count() > 0 && $reports->first()->supplier)
                                    @php
                                        $supplier = $reports->first()->supplier;
                                    @endphp
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <p class="border border-dark p-2">ID: {{ $supplier->id }}</p>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <p class="border border-dark p-2">Name: {{ $supplier->company_name }}</p>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <p class="border border-dark p-2 text-wrap">Address: {{ $supplier->address }}</p>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <p class="border border-dark p-2">Mobile: {{ $supplier->mobile }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($start_date && $end_date)
                                    <div class="row pt-3">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <h4 class="text-right text-dark font-weight-bold">Start Date:
                                                {{date('d-m-Y', strtotime($start_date))}}</h4>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <h4 class="text-left text-dark font-weight-bold">End Date:
                                                {{ date('d-m-Y', strtotime($end_date))}}</h4>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if($reports->count() > 0)
                                <div id="datatable-responsive_wrapper"
                                    class="dataTables_wrapper container-fluid dt-bootstrap no-footer">
                                    <table id="datatable-responsive"
                                        class="table table-striped table-bordered nowrap category_list_table no-footer display dataTable"
                                        style="width: 100%;" role="grid" aria-describedby="datatable-responsive_info">
                                        <thead>
                                            <tr class="text-center" role="row">
                                                <th>Date</th>
                                                <th>Invoice No.</th>
                                                <th>From Delivery</th>
                                                <th>Description</th>
                                                <th>Product Quantity</th>
                                                <th>Purchase Amount</th>
                                                <th>Discount</th>
                                                <th>Vat.</th>
                                                <th>Carring</th>
                                                <th>Others</th>
                                                <th>Total Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $quantity = 0;
                                                $amount = 0;
                                                $discount = 0;
                                                $vat = 0;
                                                $carring = 0;
                                                $others = 0;
                                            @endphp
                                            @foreach ($reports as $report)
                                                @php
                                                    $quantity += $report->total_qty;
                                                    $amount += $report->total_price;
                                                    $discount += $report->price_discount;
                                                    $vat += $report->vat;
                                                    $carring += $report->carring;
                                                    $others += $report->other_charge;
                                                @endphp
                                                <tr>
                                                    <td>{{date('d-m-Y', strtotime($report->date))}}</td>
                                                    <td class="text-center">{{$report->invoice_no}}</td>
                                                    <td>{{ $report->warehouse->name ?? 'N/A' }}</td>
                                                    <td>
                                                        <table class="table table-sm table-borderless">
                                                            <tbody>
                                                                @foreach ($report->transactions as $transaction)
                                                                    @php
                                                                        $type = $transaction->product->type ?? '';
                                                                    @endphp
                                                                    <tr>
                                                                        <td><small>{{ $transaction->product->name ?? 'N/A' }}</small></td>
                                                                        <td><small>{{ $transaction->product_quantity }} {{ $type }}</small></td>
                                                                        <td class="text-right"><small>{{ $transaction->product_price }}/=</small></td>
                                                                        <td class="text-right"><small>{{ $transaction->sub_total }}/=</small></td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td class="text-center">{{$report->total_qty}} {{$type}}</td>
                                                    <td class="text-right">{{$report->total_price}}/=</td>
                                                    <td class="text-right">{{$report->price_discount}}/=</td>
                                                    <td class="text-right">{{$report->vat}}/=</td>
                                                    <td class="text-right">{{$report->carring}}/=</td>
                                                    <td class="text-right">{{$report->other_charge}}/=</td>
                                                    <td class="text-right">{{$report->total_price - $report->price_discount + $report->vat + $report->carring + $report->other_charge}}/=</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="text-right p-0" colspan="4">Total</td>
                                                <td class="text-right p-0" colspan="1">{{$quantity}} {{ trans_choice("labels.$type", $quantity)}}</td>
                                                <td class="text-right p-0" colspan="1">{{$amount ? $amount . ' /= ' : ''}}</td>
                                                <td class="text-right p-0" colspan="1">{{$discount ? $discount . ' /= ' : ''}}</td>
                                                <td class="text-right p-0" colspan="1">{{$vat ? $vat . ' /= ' : ''}}</td>
                                                <td class="text-right p-0" colspan="1">{{$carring ? $carring . ' /= ' : ''}}</td>
                                                <td class="text-right p-0" colspan="1">{{$others ? $others . ' /= ' : ''}}</td>
                                                <td class="text-right p-0" colspan="1">{{$amount - $discount + $vat + $carring + $others}}/=</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    @if($perPage != 'all')
                                        {{ $reports->links() }}
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-info text-center">
                                    No purchase records found. Please select date range to view reports.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#get_supplier_id').select2();
            $('#get_supplier_id').on('change', function(e) {
                @this.set('get_supplier_id', e.target.value);
            });

            $('#datepicker33').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            }).on('changeDate', function(e) {
                @this.set('start_date', e.format('dd-mm-yyyy'));
            });

            $('#end_datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            }).on('changeDate', function(e) {
                @this.set('end_date', e.format('dd-mm-yyyy'));
            });
        });
    </script>
@endpush
