@section('page-title', 'Bank Ledger')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel p-3">
        <div class="x_title my-3">
            <div class="header-title d-flex align-items-center justify-content-between flex-wrap p-3 bg-light rounded shadow-sm border">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="fa fa-university fa-lg"></i>
                    </div>
                    <h2 class="mb-0 fw-bold text-dark">Bank Ledger</h2>
                </div>

                @if($bank)
                    <div class="text-end">
                        <h4 class="text-dark mb-1">
                            {{ $bank->name }}
                        </h4>
                        <h6>{{ $bank->branch }}</h6>
                        <small class="text-muted d-block">
                            A/C No: <strong>{{ $bank->account_no }}</strong> | Mode: <strong>{{ ucfirst($bank->ac_mode) }}</strong>
                        </small>
                    </div>
                @endif
            </div>
        </div>
        <div class="search" wire:ignore>
            <form action="{{route('bank.ledger')}}" method="get">
                <div class="row justify-content-center">
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="supplier-search-area">
                            <label  class="py-1 border" for="start_date">From Date</label>
                            <div class="input-group date" id="start_date_picker">
                                <input name="start_date" id="start_date" type="text" class="form-control" placeholder="dd-mm-yyyy" value="{{$start_date}}">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="supplier-search-area">
                            <label  class="py-1 border" for="end_date">To Date</label>
                            <div class="input-group date" id="end_date_picker">
                                <input name="end_date" id="end_date" type="text" class="form-control" placeholder="dd-mm-yyyy" value="{{$end_date}}">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="supplier-search-area">
                            <label  class="py-1 border" for="supplier_search">Select Bank</label>
                            <div class="form-group">
                                <select id="get_bank_id" name="id" placeholder="Search Bank" class="form-control">
                                    <option value="">Select Bank</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{$bank->id}}" @selected($bank->id == $bank_id)>{{$bank->name}} - {{$bank->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="supplier-search-button pt-4">
                            <div class="form-group pt-3">
                                <button type="submit" class="btn btn-primary btn-sm" style="min-width: 100px">Get</button>
                                <button type="reset" class="btn btn-warning btn-sm" wire:click="resetData" style="min-width: 100px">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="x_content">
            <div class="due-list-area">
                {{cute_loader()}}
                @if(count($bank_statements) > 0)
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
                            <button type="button" class="btn btn-danger btn-sm" wire:click="downloadPdf" style="min-width: 80px"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</button>
                        </div>
                    </div>
                    <div class="ajax-search d-flex align-items-center gap-2">
                        <div class="form-group m-0">
                            <input type="text" wire:model="search_query" class="form-control form-control-sm" style="min-width: 250px"/>
                        </div>

                        <div class="form-group m-0">
                            <button type="button" class="btn btn-primary btn-sm" wire:click="filterData" style="min-width: 80px">Search</button>
                        </div>
                    </div>
                </div>
                @endif
                @if(count($bank_statements) > 0)
                    <div class="card-box table-responsive">
                        <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <th class="all">SL</th>
                                <th class="all">Date</th>
                                <th class="text-left">Description</th>
                                <th class="all">Deposit</th>
                                <th class="all">Withdraw</th>
                                <th class="all">Balance</th>
                                <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_deposit = 0;
                                    $total_withdraw = 0;
                                    $transactions = [];
                                    $balance = 0;
                                @endphp
                                @foreach($bank_statements as $transaction)
                                    @php
                                        $deposit = in_array($transaction->type, ['deposit', 'opening', 'others']) ? $transaction->amount : 0;
                                        $total_deposit += $deposit;
                                        $withdraw = $transaction->type == 'withdraw' ? $transaction->amount : 0;
                                        $total_withdraw += $withdraw;
                                        $balance += $transaction->type == 'prev' ? $transaction->amount : $deposit - $withdraw;

                                        $currentPage = method_exists($bank_statements, 'currentPage') ? $bank_statements->currentPage() : 1;
                                        $perPage = method_exists($bank_statements, 'perPage') ? $bank_statements->perPage() : $bank_statements->count(); // Fallback to total count
                                        $iteration = ($currentPage - 1) * $perPage + $loop->iteration;
                                    @endphp
                                    <tr style="background-color: {{ in_array($transaction->type, ['deposit', 'opening', 'others']) ? '#e1f3eb' :($transaction->type == 'prev' ? '' : '#f3e1e1') }}">
                                        <td>{{$iteration}}</td>
                                        @if ($transaction->type == 'prev')
                                            <td>-</td>
                                            <td class="text-left">{{$transaction->remarks}}</td>
                                            <td class="text-right">-</td>
                                            <td class="text-right">-</td>
                                        @else
                                            <td>{{date('d-m-Y',strtotime($transaction->date))}}</td>
                                            <td class="text-left">{{ucfirst($transaction->type)}}{{$transaction->payment_method ? ' - ' . ucfirst($transaction->payment_method) : ''}}{{$transaction->remarks ? ' - ' . $transaction->remarks : ''}}</td>
                                            <td class="text-right">{{in_array($transaction->type, ['deposit', 'opening', 'others']) && $transaction->amount > 0  ? number_format($transaction->amount) . '/=' : ''}}</td>
                                            <td class="text-right">{{$transaction->type == 'withdraw' && $transaction->amount > 0 ? number_format($transaction->amount) . '/=': ''}}</td>
                                        @endif
                                        <td class="text-right">{{formatAmount($balance ?? 0)}}/=</td>
                                        <td>
                                            @if ($transaction->id)
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                        data-toggle="dropdown">
                                                        <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li>
                                                            <a href="{{route('transaction.delete', $transaction->id)}}" class="btn btn-danger" id="delete"><i class="fa fa-trash" ></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right"><b>{{formatAmount( $total_deposit )}}/=</b></td>
                                    <td class="text-right"><b>{{formatAmount( $total_withdraw)}}/=</b></td>
                                    <td class="text-right"><b>{{formatAmount( $balance )}}/=</b></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>

                        @if (method_exists($bank_statements, 'links'))
                            <div class="mt-4 w-100">
                                {{ $bank_statements->links() }}
                            </div>
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
    jQuery(document).ready(function($) {
        $('#get_bank_id').select2();

        $('#start_date_picker').datepicker( {
            format: "dd-mm-yyyy",
            autoclose: true,
        });
        $('#end_date_picker').datepicker( {
            format: "dd-mm-yyyy",
            autoclose: true,
        });
    })
</script>
@endpush
