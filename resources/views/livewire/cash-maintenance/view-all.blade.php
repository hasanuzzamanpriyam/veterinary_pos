@section('page-title', 'Cash Maintenance List')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto">Cash Maintenance List</h2>
                <div class="download-btns d-flex align-items-center gap-2">
                    <div class="form-group m-0">
                        <span>Download Report</span>
                    </div>
                    <div class="form-group m-0">
                        <button type="button" class="btn btn-danger btn-sm" wire:click="downloadPdf" style="min-width: 80px"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</button>
                    </div>
                </div>
                <a href="{{ route('cash_maintenance.create') }}" class="btn btn-primary btn-sm cursor-pointer"><i
                        class="fa fa-plus"></i> Cash Maintenance Add</a>
            </div>

        </div>

        <div class="x_content p-3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{cute_loader()}}

                    <div class="table-header d-flex align-items-center justify-content-between">
                        <div class="per-page">
                            <div class="form-group">
                                {{-- <select id="perpage" class="form-control" wire:change="updatePerPage($event.target.value)"> --}}
                                <select id="perpage" class="form-control" wire:model.live="perPage">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="all">All</option>
                                </select>
                            </div>
                            {{-- <form>
                            </form> --}}
                        </div>

                        <div class="ajax-search d-flex align-items-center gap-2" wire:ignore>
                            <div class="form-group">
                                <div class="input-group date" id="start_date">
                                    <input name="date" wire:model="date" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                                {{-- <input type="text" id="customer-search" class="form-control form-control-sm" style="min-width: 200px" placeholder="Select Date"/> --}}
                            </div>
                            <div class="form-group">
                                <div class="input-group date" id="end_date">
                                    <input name="date" wire:model="date" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                                {{-- <input type="text" id="customer-search" class="form-control form-control-sm" style="min-width: 200px" placeholder="Select Date" /> --}}
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary btn-sm" wire:click="filterData" style="min-width: 100px">Get</button>
                                <button type="reset" class="btn btn-warning btn-sm" wire:click="resetData" style="min-width: 100px">Reset</button>
                            </div>
                        </div>
                    </div>

                    <div class="card-box table-responsive">
                        <table id="" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <th class="all">SL</th>
                                <th class="all" style="width: 90px">Date</th>
                                <th class="all" style="width: 120px">Dokan Cash (prev)</th>
                                <th class="all">Collection</th>
                                <th class="all">Payment</th>
                                <th class="all">Expense</th>
                                <th class="all">Home Cash</th>
                                <th class="all">Short Cash</th>
                                <th class="all">Dokan Cash</th>
                                <th class="all" style="width: 60px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $prev_balance = 0;
                                    $summary = [];
                                @endphp
                                @foreach ($cash_data as $data)
                                    @php
                                        $summary['collection'] = $summary['collection'] ?? 0;
                                        $summary['collection'] += $data->collection;
                                        $summary['payment'] = $summary['payment'] ?? 0;
                                        $summary['payment'] += $data->payment;
                                        $summary['expense'] = $summary['expense'] ?? 0;
                                        $summary['expense'] += $data->expense;
                                        $summary['home_cash'] = $summary['home_cash'] ?? 0;
                                        $summary['home_cash'] += $data->home_cash;
                                        $summary['short_cash'] = $summary['short_cash'] ?? 0;
                                        $summary['short_cash'] += $data->short_cash;
                                        if ($loop->first){
                                            $summary['dokan_cash'] = $summary['dokan_cash'] ?? 0;
                                            $summary['dokan_cash'] = $data->dokan_cash;
                                        }

                                        $currentPage = method_exists($cash_data, 'currentPage') ? $cash_data->currentPage() : 1;
                                        $perPage = method_exists($cash_data, 'perPage') ? $cash_data->perPage() : $cash_data->count(); // Fallback to total count
                                        $iteration = ($currentPage - 1) * $perPage + $loop->iteration;
                                    @endphp
                                <tr>
                                    <td>{{$iteration}}</td>
                                    <td>{{date('d-m-Y',strtotime($data->date))}}</td>
                                    <td class="text-right">{{$data->prev_balance ? number_format($data->prev_balance) . '/=' : ''}}</td>
                                    <td class="text-right">{{$data->collection ? number_format($data->collection) . '/=' : ''}}</td>
                                    <td class="text-right">{{$data->payment ? number_format($data->payment) . '/=' : ''}}</td>
                                    <td class="text-right">{{$data->expense ? number_format($data->expense) . '/=' : ''}}</td>
                                    <td class="text-right">{{$data->home_cash ? number_format($data->home_cash) . '/=' : ''}}</td>
                                    <td class="text-right">{{$data->short_cash ? number_format($data->short_cash) . '/=' : ''}}</td>
                                    <td class="text-right">{{$data->dokan_cash ? number_format($data->dokan_cash) . '/=' : ''}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                data-toggle="dropdown">
                                                <i class="fa fa-list"></i> <span class="caret"></span></button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li>
                                                    <a href="{{route('cash_maintenance.view', $data->id)}}" class="btn btn-primary"><i class="fa fa-eye" ></i></a>
                                                </li>
                                                <li>
                                                    <a href="{{route('cash_maintenance.edit', $data->id)}}" class="btn btn-success"><i class="fa fa-edit" ></i></a>
                                                </li>
                                                <li>
                                                    <a href="{{route('cash_maintenance.delete', $data->id)}}" class="btn btn-danger" id="delete"><i class="fa fa-trash" ></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-right">{{isset($summary['collection']) ? number_format($summary['collection']) : 0 }}/=</th>
                                        <th class="text-right">{{isset($summary['payment']) ? number_format($summary['payment']) : 0 }}/=</th>
                                        <th class="text-right">{{isset($summary['expense']) ? number_format($summary['expense']) : 0 }}/=</th>
                                        <th class="text-right">{{isset($summary['home_cash']) ? number_format($summary['home_cash']) : 0 }}/=</th>
                                        <th class="text-right">{{isset($summary['short_cash']) ? number_format($summary['short_cash']) : 0 }}/=</th>
                                        <th class="text-right">{{isset($summary['dokan_cash']) ? number_format($summary['dokan_cash']) : 0 }}/=</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </tbody>
                        </table>
                    </div>
                    @if (method_exists($cash_data, 'links'))
                        <div class="mt-4 w-100">
                            {{ $cash_data->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>


@push('scripts')
<script>
    $(document).ready(function() {

        $('#start_date, #end_date').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });

        $('#start_date input[name=date]').on('change', function(e) {
            @this.set('start_date', e.target.value, false);
        });
        $('#end_date input[name=date]').on('change', function(e) {
            @this.set('end_date', e.target.value, false);
        });

    });
</script>
@endpush
