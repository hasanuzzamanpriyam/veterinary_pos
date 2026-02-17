@section('page-title', 'Ledger - ' . $employee->name ?? '')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2>Ledger</h2>
                <ul class="nav navbar-right panel_toolbox mr-auto">
                    <li><span class="collapse-link btn btn-sm btn-primary text-white "><i class="fa fa-eye"></i> Filter</span>
                    </li>
                </ul>

                <a href="{{route('employee.payment.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Create Payment</a>
                {{-- <a href="{{route('bank.index')}}" class="btn btn-sm btn-primary"><i class="fa fa-list" aria-hidden="true"></i> All Banks</a>
                <a href="{{route('transaction.create', ['id' => $bank->id, 'view' => 'withdraw'])}}" class="btn btn-sm btn-info"><i class="fa fa-plus" aria-hidden="true"></i> Withdraw</a> --}}
                {{-- <a href="{{route('transaction.deposit.create', ['id' => $bank->id])}}" class="btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Deposit</a> --}}
            </div>
            <h6 class="text-center text-dark mb-0">{{$employee->name ?? ''}} - {{$employee->address ?? ''}} - {{$employee->mobile ?? ''}} - {{$employee->designation ?? ''}}</h6>


        </div>
        <div class="p-3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 x_content" style="display: none;">
                    <form wire:submit.prevent="search" data-parsley-validate>
                        @csrf
                        <div class="row justify-content-end">
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="supplier-search-area">
                                    <label class="py-1 border" for="start_date">From Date</label>
                                    <div class="input-group date" id="start_date_picker">
                                        <input name="date" id="start_date" type="text" class="form-control" placeholder="dd-mm-yyyy" required>
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="supplier-search-area">
                                    <label class="py-1 border" for="end_date">To Date</label>
                                    <div class="input-group date" id="end_date_picker">
                                        <input name="date" id="end_date" type="text" class="form-control" placeholder="dd-mm-yyyy" required>
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="supplier-search-area">
                                    <label  class="py-1 border">Type</label>
                                    <div class="form-group" wire:ignore>
                                        <select type="search" id="transaction_type" wire:model="transaction_type" name="transaction_type" placeholder="Select Type" class="form-control">
                                            {{-- @if ($view == 'deposit')
                                                <option value="deposit">Deposit</option>
                                            @elseif ($view == 'withdraw')
                                                <option value="withdraw">Withdraw</option>
                                            @else
                                                <option value="">All</option>
                                                <option value="deposit">Deposit</option>
                                                <option value="withdraw">Withdraw</option>
                                            @endif --}}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="supplier-search-area">
                                    <label  class="py-1 border">Method</label>
                                    <div class="form-group" wire:ignore>
                                        <select type="search" id="payment_method" wire:model="payment_method" name="payment_method" placeholder="Selct Method" class="form-control">
                                            <option value="">All</option>
                                            {{-- @foreach ($payment_types as $types)
                                                <option value="{{$types}}">
                                                    {{ucfirst($types)}}
                                                </option>
                                            @endforeach --}}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <input type="submit" value="Search" class="form-control btn btn-success btn-xl">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <button type="reset" wire:click="searchReset" class="form-control btn btn-danger btn-sm">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    <div class="table-header d-flex align-items-center justify-content-start gap-2">
                        <div class="per-page">
                            <div class="form-group">
                                <select id="perpage" class="form-control" wire:model.live="perPage">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="all">All</option>
                                </select>
                            </div>
                        </div>

                        <div class="ajax-search d-flex gap-2">
                            <div class="form-group">
                                <button type="reset" wire:click="searchReset" class="form-control btn btn-danger btn-sm">Reset</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-box table-responsive">
                        <table id="datatable-responsivesss" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
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
                                @foreach($expenses as $transaction)
                                    @php
                                        $deposit = $transaction->type == 'salary' ? $transaction->amount : 0;
                                        $total_deposit += $deposit;
                                        $withdraw = $transaction->type == 'payment' ? $transaction->amount : 0;
                                        $total_withdraw += $withdraw;
                                        $balance += $deposit - $withdraw;

                                        $currentPage = method_exists($expenses, 'currentPage') ? $expenses->currentPage() : 1;
                                        $perPage = method_exists($expenses, 'perPage') ? $expenses->perPage() : $expenses->count(); // Fallback to total count
                                        $iteration = ($currentPage - 1) * $perPage + $loop->iteration;
                                    @endphp
                                    <tr style="background-color: {{ $transaction->type == 'salary' ? '#e1f3eb' : '#f3e1e1' }}">
                                        <td>{{$iteration}}</td>
                                        <td>{{date('d-m-Y',strtotime($transaction->date))}}</td>
                                        <td class="text-left">{{ucfirst($transaction->type)}}{{$transaction->payment_method ? ' - ' . ucfirst($transaction->payment_method) : ''}}{{$transaction->remarks ? ' - ' . $transaction->remarks : ''}}</td>
                                        <td class="text-right">{{$transaction->type == 'salary' && $transaction->amount > 0  ? number_format($transaction->amount) . '/=' : ''}}</td>
                                        <td class="text-right">{{$transaction->type == 'payment' && $transaction->amount > 0 ? number_format($transaction->amount) . '/=': ''}}</td>
                                        <td class="text-right">{{formatAmount($balance ?? 0)}}/=</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                    data-toggle="dropdown">
                                                    <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <a href="{{route('employee.ledger.delete', $transaction->id)}}" class="btn btn-danger" id="delete"><i class="fa fa-trash" ></i></a>
                                                    </li>
                                                </ul>
                                            </div>
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
                                    <td class="text-right"><b>{{formatAmount( $employee->balance )}}/=</b></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>

                        @if (method_exists($expenses, 'links'))
                            <div class="mt-4 w-100">
                                {{ $expenses->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
jQuery(document).ready(function() {
    $('#end_date_picker, #start_date_picker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true
    });

    $('#start_date_picker input[name=date]').on('change', function(e) {
        @this.set('start_date', e.target.value, false);
    });
    $('#end_date_picker input[name=date]').on('change', function(e) {
        @this.set('end_date', e.target.value, false);
    });

})
</script>
@endpush
