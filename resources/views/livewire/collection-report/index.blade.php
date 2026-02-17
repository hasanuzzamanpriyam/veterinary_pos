
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title">

            <div class="header-title d-flex align-items-center gap-2 p-3">
                <h2>Collection Report</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><span class="collapse-link btn btn-md btn-primary text-white"><i class="fa fa-eye"></i> Advance</span>
                    </li>
                </ul>
            </div>

        </div>
        <div class="">
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
                        <div class="col-lg-2 col-md-2 col-sm-12">
                            <div class="customer-search-area">
                                <label class="d-block py-1 border" for="customer_search">Start Date:</label>
                                <div class="input-group date" id="datepicker33">
                                    <input name="start_date"  wire:model="start_date" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12">
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
                        <div class="col-lg-5 col-md-5 col-sm-12">
                            <div class="customer-search-area">
                                <label class="py-1 border" for="customer_search">Select Customer:</label>
                                <div class="form-group" wire:ignore>
                                    <select type="search" id="get_customer_id"   name="get_customer_id" placeholder="search customer" class="form-control">
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
                            <div class="customer-search-button pt-4 text-center">
                                <div class="form-group pt-3">
                                    <button type="submit" class="btn btn-success">Search</button>
                                    <button type="button" wire:click="resetCustomer" onClick="window.location.reload()" class="btn btn-danger">Reset</button>
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
                                    $customer = DB::table('customers')->where('id', $get_customer_id)->first();
                                    // dump($reports)
                                @endphp
                                @if( $customer )
                                <div class="x_title">
                                    <h4 class="text-center"> <strong>{{$customer->name}} - {{$customer->address}} - {{$customer->mobile}}</strong></h4>
                                </div>
                                @else
                                    <div class="x_title">
                                        <h4 class="text-center"> <strong>All Customer Collection Report</strong></h4>
                                    </div>
                                @endif

                                <table class="table table-striped table-bordered dt-responsive nowrap dataTable">
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
                                            @if( !isset($get_customer_id) )
                                            <td>{{$report->customer->name ?? ''}}</td>
                                            <td class="text-wrap">{{$report->customer->address ?? ''}}</td>
                                            <td class="text-center">{{$report->customer->mobile ?? ''}}</td>
                                            @endif
                                            <td class="text-center">{{$report->id}}</td>
                                            @if( isset($get_customer_id) )
                                            <td class="text-wrap">{{$report->payment_by}}{{$report->bank_title ? ': '.$report->bank_title : ''}} {{$report->received_by && $report->type == 'other' ? $report->received_by : ($report->received_by && $report->type != 'other' ? '- '.$report->received_by : '')}}</td>
                                            @else
                                            <td class="text-center">{{$report->payment_by}}{{$report->bank_title ? ': '.$report->bank_title : ''}}</td>
                                            <td class="text-wrap">{{$report->received_by}}</td>
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

           $('#get_customer_id').select2({
            placeholder: 'Select customer from here',
           });

           $('#get_customer_id').on('change', function (e){
                var data = $('#get_customer_id').select2("val");
                @this.set('get_customer_id', data);
            });


            $('#datepicker33').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });

            $('#datepicker33 input[name=start_date]').on('change', function (e) {
                @this.set('start_date', e.target.value);
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


    });

    </script>

@endpush
