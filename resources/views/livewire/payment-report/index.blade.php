@section('page-title', '---')
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title">

            <div class="header-title d-flex align-items-center gap-2 p-3">
                <h2>Payment Report</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><span class="collapse-link btn btn-md btn-primary text-white "><i class="fa fa-eye"></i> Advance</span>
                    </li>
                </ul>
            </div>

        </div>
        <div>
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
                <form class="x_content" style="display: none;" wire:submit.prevent=paymentReportSearch()>
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-2 col-md-2 col-sm-12">
                            <div class="supplier-search-area">
                                <label class="d-block py-1 border" for="supplier_search">Start Date:</label>
                                <div class="input-group date" id="start_datepicker">
                                    <input name="start_date" wire:model="start_date" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12">
                            <div class="supplier-search-area">
                                <label class="d-block py-1 border" for="supplier_search">End Date:</label>
                                <div class="input-group date" id="end_datepicker">
                                    <input name="end_date" wire:model="end_date" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="supplier-search-area">
                                <label class="py-1 border" for="paying_by">Paying By:</label>
                                <div class="form-group" wire:ignore>
                                    <select type="search" id="paying_by" name="paying_by" class="form-control">
                                        <option value="all">All</option>
                                        @foreach ($payment_types as $type)
                                            <option value="{{strtolower($type)}}">{{$type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-12">
                            <div class="supplier-search-area">
                                <label class="py-1 border" for="supplier_search">Select Supplier:</label>
                                <div class="form-group" wire:ignore>
                                    <select type="search" id="get_supplier_id"   name="get_supplier_id" placeholder="search supplier" class="form-control">
                                        <option value=""></option>
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
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="supplier-search-button pt-4 text-center">
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
                            @if($reports)
                                @php
                                    $supplier = DB::table('suppliers')->where('id', $get_supplier_id)->first();
                                @endphp
                                @if( $supplier )
                                <div class="x_title">
                                    <h4 class="text-center"> <strong>{{$supplier->company_name}} - {{$supplier->address}} - {{$supplier->mobile}}</strong></h4>
                                </div>
                                @else
                                    <div class="x_title">
                                        <h4 class="text-center"> <strong>All Supplier Payment Report</strong></h4>
                                    </div>
                                @endif
                                <table class="table table-striped table-bordered dt-responsive nowrap dataTable">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Date</th>
                                            @if( !isset($get_supplier_id) )
                                            <th>Company Name</th>
                                            <th>Address</th>
                                            <th>Mobile</th>
                                            @endif

                                            <th>Invoice</th>
                                            @if( isset($get_supplier_id) )
                                            <th>Description</th>
                                            @else
                                            <th>Paying By</th>
                                            <th>Remarks</th>
                                            @endif
                                            <th>Payment</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_quantity = 0;
                                            $total_amount = 0;
                                        @endphp
                                        @foreach ($reports as $report)
                                            @php
                                                $total_amount += $report->payment;
                                            @endphp
                                        <tr>
                                            <td>{{date('d-m-Y', strtotime($report->date))}}</td>
                                            @if( !isset($get_supplier_id) )
                                            <td>{{$report->supplier->company_name ?? ''}}</td>
                                            <td class="text-wrap">{{$report->supplier->address ?? ''}}</td>
                                            <td class="text-center">{{$report->supplier->mobile ?? ''}}</td>
                                            @endif
                                            <td class="text-center">{{$report->id}}</td>
                                            @if( isset($get_supplier_id) )
                                            <td class="text-wrap">{{$report->payment_by}}{{$report->bank_title ? ': '.$report->bank_title : ''}} {{$report->payment_remarks ? '- '.$report->payment_remarks : ''}}</td>
                                            @else
                                            <td class="text-center">{{$report->payment_by}}{{$report->bank_title ? ': '.$report->bank_title : ''}}</td>
                                            <td class="text-wrap">{{$report->payment_remarks}}</td>
                                            @endif
                                            <td class="text-right">{{$report->payment ? $report->payment . '/=' : ''}}</td>
                                            <td class="text-right">{{$total_amount . '/='}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                </table>
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

    $(document).ready(function () {

        $('#paying_by').select2({
        placeholder: 'Select payment by',
        });
        $('#get_supplier_id').select2({
        placeholder: 'Select supplier',
        });

        $('#get_supplier_id').on('change', function (e){
            var data = $('#get_supplier_id').select2("val");
            @this.set('get_supplier_id', data);
        });
        $('#paying_by').on('change', function (e){
            var data = $('#paying_by').select2("val");
            @this.set('paying_by', data);
        });

        $('#start_datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });
        $('#start_datepicker input[name=start_date]').on('change', function (e) {
            @this.set('start_date', e.target.value);
        });

        $('#end_datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });
        $('#end_datepicker input[name=end_date]').on('change', function (e) {
            @this.set('end_date', e.target.value);
        });
    });

    </script>

@endpush
