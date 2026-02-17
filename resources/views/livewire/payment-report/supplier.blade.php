@section('page-title', '---')
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title">

            <div class="header-title d-flex align-items-center gap-2 px-3">
                <h2 class="">Payment Report</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><span class="collapse-link btn btn-md btn-primary text-white "><i class="fa fa-eye"></i> Advance</span>
                    </li>
                </ul>
                <h6 class="text-dark mb-0 mr-auto">{{$supplier->company_name}} - {{$supplier->address}} - {{$supplier->mobile}} </h6>

            </div>

        </div>
        <div class="" style="max-width: 800px; margin: 0 auto; background-color: #bbd3e1; padding: 20px">
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
                <form wire:submit.prevent=paymentReportSearch() class="x_content" style="display: none">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-3 col-md-3 col-sm-12">
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
                        <div class="col-lg-3 col-md-3 col-sm-12">
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
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="supplier-search-button pt-4">
                                <div class="form-group pt-3">
                                    <button type="button" wire:click="resetSupplier" class="btn btn-danger button-sm">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="due-list-area">
                            {{-- @dump($reports) --}}
                            @if($reports)
                                <table id="datatable-responsivesss" class="table table-striped table-bordered">
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
                                            <th class="text-left">Description</th>
                                            @else
                                            <th>Paying By</th>
                                            <th>Remarks</th>
                                            @endif
                                            <th style="width: 100px">Payment</th>
                                            <th style="width: 100px">Balance</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_quantity = 0;
                                            $total_amount = 0;
                                        @endphp
                                        @foreach ($reports as $report)

                                            @if($report->vat > 0)
                                                @php
                                                    $total_amount += $report->vat;
                                                @endphp
                                                <tr>
                                                    <td class="text-nowrap">{{date('d-m-Y', strtotime($report->date))}}</td>
                                                    @if( !isset($get_supplier_id) )
                                                        <td>{{$report->supplier->company_name ?? ''}}</td>
                                                        <td class="text-wrap">{{$report->supplier->address ?? ''}}</td>
                                                        <td class="text-center">{{$report->supplier->mobile ?? ''}}</td>
                                                    @endif

                                                    <td class="text-center">{{$report->id}}</td>

                                                    @if( isset($get_supplier_id) )
                                                        <td class="text-left">VAT by cash</td>
                                                    @else
                                                    <td class="text-center">{{$report->payment_by}}{{$report->bank_title ? ': '.$report->bank_title : ''}}</td>
                                                    <td class="text-wrap">{{$report->payment_remarks}}</td>
                                                    @endif
                                                    <td class="text-right">{{$report->vat}}/=</td>
                                                    <td class="text-right">{{$total_amount . '/='}}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2" data-toggle="dropdown">
                                                                <i class="fa fa-list"></i> <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu">
                                                                <li><a href="{{ route('purchase.delete', ['invoice' => $report->id, 'view' => 'payment']) }}" class="btn btn-danger" id="delete"><i class="fa fa-trash"></i></a></li>
                                                                <li><a href="{{ route('purchase.view', ['invoice' => $report->id, 'view' => 'payment']) }}" class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if($report->carring > 0)
                                                @php
                                                    $total_amount += $report->carring;
                                                @endphp
                                                <tr>
                                                    <td class="text-nowrap">{{date('d-m-Y', strtotime($report->date))}}</td>
                                                    @if( !isset($get_supplier_id) )
                                                        <td>{{$report->supplier->company_name ?? ''}}</td>
                                                        <td class="text-wrap">{{$report->supplier->address ?? ''}}</td>
                                                        <td class="text-center">{{$report->supplier->mobile ?? ''}}</td>
                                                    @endif

                                                    <td class="text-center">{{$report->id}}</td>

                                                    @if( isset($get_supplier_id) )
                                                        <td class="text-left">Carring by cash</td>
                                                    @else
                                                    <td class="text-center">{{$report->payment_by}}{{$report->bank_title ? ': '.$report->bank_title : ''}}</td>
                                                    <td class="text-wrap">{{$report->payment_remarks}}</td>
                                                    @endif
                                                    <td class="text-right">{{$report->carring}}/=</td>
                                                    <td class="text-right">{{$total_amount . '/='}}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2" data-toggle="dropdown">
                                                                <i class="fa fa-list"></i> <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu">
                                                                <li><a href="{{ route('purchase.delete', ['invoice' => $report->id, 'view' => 'payment']) }}" class="btn btn-danger" id="delete"><i class="fa fa-trash"></i></a></li>
                                                                <li><a href="{{ route('purchase.view', ['invoice' => $report->id, 'view' => 'payment']) }}" class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if($report->other_charge > 0)
                                                @php
                                                    $total_amount += $report->other_charge;
                                                @endphp
                                                <tr>
                                                    <td class="text-nowrap">{{date('d-m-Y', strtotime($report->date))}}</td>
                                                    @if( !isset($get_supplier_id) )
                                                        <td>{{$report->supplier->company_name ?? ''}}</td>
                                                        <td class="text-wrap">{{$report->supplier->address ?? ''}}</td>
                                                        <td class="text-center">{{$report->supplier->mobile ?? ''}}</td>
                                                    @endif

                                                    <td class="text-center">{{$report->id}}</td>

                                                    @if( isset($get_supplier_id) )
                                                        <td class="text-left">Other Charge by cash</td>
                                                    @else
                                                    <td class="text-center">{{$report->payment_by}}{{$report->bank_title ? ': '.$report->bank_title : ''}}</td>
                                                    <td class="text-wrap">{{$report->payment_remarks}}</td>
                                                    @endif
                                                    <td class="text-right">{{$report->other_charge}}/=</td>
                                                    <td class="text-right">{{$total_amount . '/='}}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2" data-toggle="dropdown">
                                                                <i class="fa fa-list"></i> <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu">
                                                                <li><a href="{{ route('purchase.delete', ['invoice' => $report->id, 'view' => 'payment']) }}" class="btn btn-danger" id="delete"><i class="fa fa-trash"></i></a></li>
                                                                <li><a href="{{ route('purchase.view', ['invoice' => $report->id, 'view' => 'payment']) }}" class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if($report->payment > 0)
                                                @php
                                                    $total_amount += $report->payment;
                                                @endphp
                                                <tr>
                                                    <td class="text-nowrap">{{date('d-m-Y', strtotime($report->date))}}</td>
                                                    @if( !isset($get_supplier_id) )
                                                        <td>{{$report->supplier->company_name ?? ''}}</td>
                                                        <td class="text-wrap">{{$report->supplier->address ?? ''}}</td>
                                                        <td class="text-center">{{$report->supplier->mobile ?? ''}}</td>
                                                    @endif

                                                    <td class="text-center">{{$report->id}}</td>

                                                    @if( isset($get_supplier_id) )
                                                        <td class="text-left">{{$report->payment_by}}{{$report->payment_by && $report->bank_title ? " : " : ""}}{{$report->bank_title}}{{$report->bank_title && $report->payment_remarks ? " - " : ""}}{{$report->payment_remarks}}</td>
                                                    @else
                                                    <td class="text-center">{{$report->payment_by}}{{$report->bank_title ? ': '.$report->bank_title : ''}}</td>
                                                    <td class="text-wrap">{{$report->payment_remarks}}</td>
                                                    @endif
                                                    <td class="text-right">{{$report->payment ? $report->payment . '/=' : ''}}</td>
                                                    <td class="text-right">{{$total_amount . '/='}}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2" data-toggle="dropdown">
                                                                <i class="fa fa-list"></i> <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu">
                                                                <li><a href="{{ route('purchase.delete', ['invoice' => $report->id, 'view' => 'payment']) }}" class="btn btn-danger" id="delete"><i class="fa fa-trash"></i></a></li>
                                                                <li><a href="{{ route('purchase.view', ['invoice' => $report->id, 'view' => 'payment']) }}" class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
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
        $(document).on('dataUpdated', function() {
            setTimeout(() => {
                $('#datatable-responsivesss').DataTable({
                    ordering: false,
                    columnDefs: [
                        { targets: 0, width: '50px', },
                        { targets: 1, width: '50px', },
                        { targets: 2, className: 'text-left', },
                        { orderable: false, targets: 3, width: '50px'  },
                        { orderable: false, targets: 4, width: '50px'  },
                    ]
                });
            }, 300);
        });
    });

    </script>

@endpush
