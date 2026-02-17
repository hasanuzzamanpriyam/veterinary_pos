
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title">

            <div class="header-title d-flex align-items-center gap-2 p-3">
                <h2>Collection Report</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><span class="collapse-link btn btn-md btn-primary text-white"><i class="fa fa-eye"></i> Advance</span>
                    </li>
                </ul>
                <h6 class="text-dark mb-0 mr-auto">{{$customer->name}}{{$customer->address ? ' - '.$customer->address : ''}}{{$customer->mobile ? ' - '.$customer->mobile : ''}}</h6>
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
                <form class="x_content" style="display: none;" wire:submit.prevent=collectionReportSearch()>
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="customer-search-area">
                                <label class="d-block py-1 border" for="customer_search">Start Date:</label>
                                <div class="input-group date" id="datepicker_start">
                                    <input name="start_date"  wire:model="start_date" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div  class="customer-search-area">
                                <label class="d-block py-1 border" for="customer_search">End Date:</label>
                                <div class="input-group date" id="datepicker_end">
                                    <input name="end_date"  wire:model="end_date" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="customer-search-area">
                                <label class="py-1 border" for="received_by">Received By:</label>
                                <div class="form-group" wire:ignore>
                                    <select type="search" id="received_by" name="received_by" class="form-control">
                                        <option value="all">All</option>
                                        @foreach ($payment_types as $type)
                                            <option value="{{strtolower($type)}}">{{$type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-12">
                            <div class="customer-search-button pt-4 text-center">
                                <div class="form-group pt-3">
                                    <button type="button" wire:click="resetCustomer" onClick="window.location.reload()" class="btn btn-danger">Reset</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="due-list-area mt-4">
                            @if(count($reports) > 0)
                                <table id="datatable-responsivesss" class="table table-striped table-bordered dt-responsive nowrap dataTable" cellspacing="0" width="100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Date</th>
                                            @if( !isset($get_customer_id) )
                                            <th>Name</th>
                                            <th>Address</th>
                                            <th>Mobile</th>
                                            @endif

                                            <th>Invoice</th>
                                            @if( isset($get_customer_id) )
                                            <th>Description</th>
                                            @else
                                            <th>Received By</th>
                                            <th>Remarks</th>
                                            @endif
                                            <th>Collection</th>
                                            <th>Balance</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_quantity = 0;
                                            $total_amount = 0;
                                        @endphp
                                        @foreach ($reports as $report)
                                            @php
                                                $total_amount += abs($report->payment);
                                            @endphp
                                            @if ($report->payment > 0)
                                                <tr>
                                                    <td>{{date('d-m-Y', strtotime($report->date))}}</td>
                                                    @if( !isset($get_customer_id) )
                                                    <td>{{$report->customer->name ?? ''}}</td>
                                                    <td class="text-wrap">{{$report->customer->address ?? ''}}</td>
                                                    <td class="text-center">{{$report->customer->mobile ?? ''}}</td>
                                                    @endif
                                                    <td class="text-center">{{$report->id}}</td>
                                                    @if( isset($get_customer_id) )
                                                    <td class="text-wrap text-left">
                                                        @if ($report->type == 'other')
                                                            {{$report->remarks}}
                                                        @else
                                                        {{$report->payment_by}}{{$report->bank_title ? ': '.$report->bank_title : ''}} {{$report->received_by ? '- '.$report->received_by : ''}}
                                                        @endif
                                                    </td>
                                                    @else
                                                    <td class="text-center">{{$report->payment_by}}{{$report->bank_title ? ': '.$report->bank_title : ''}}</td>
                                                    <td class="text-wrap">{{$report->payment_remarks}}</td>
                                                    @endif
                                                    <td class="text-right">{{$report->payment ? abs($report->payment) . '/=' : ''}}</td>
                                                    <td class="text-right">{{$total_amount . '/='}}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2" data-toggle="dropdown">
                                                                <i class="fa fa-list"></i> <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu">
                                                                <li><a href="{{ route('sales.delete', ['invoice' => $report->id, 'view' => 'collection']) }}" class="btn btn-danger" id="delete"><i class="fa fa-trash"></i></a></li>
                                                                <li><a href="{{ route('sales.view', ['invoice' => $report->id, 'view' => 'collection']) }}" class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @else
                                            <tr>
                                                <td colspan="5" class="text-center">No data found</td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>

                                </table>
                            @else
                                <p class="text-center">No collection data found</p>
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

            $('#datepicker_start').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });

            $('#datepicker_start input[name=start_date]').on('change', function (e) {
                @this.set('start_date', e.target.value);
            });
            $('#received_by').on('change', function (e) {
                @this.set('received_by', e.target.value);
            });

            // end datepicker section

            $('#datepicker_end').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });

            $('#datepicker_end input[name=end_date]').on('change', function (e) {
                @this.set('end_date', e.target.value);
            });

            $(document).on('dataUpdated', function() {
            setTimeout(() => {
                $('#datatable-responsivesss').DataTable({
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
