@section('page-title', 'Statement - ' . $bank->name)

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2>Transactions</h2>
                <ul class="nav navbar-right panel_toolbox mr-auto">
                    <li><span class="collapse-link btn btn-sm btn-primary text-white "><i class="fa fa-eye"></i> Advance</span>
                    </li>
                </ul>

                <a href="{{route('bank.index')}}" class="btn btn-sm btn-primary"><i class="fa fa-list" aria-hidden="true"></i> All Banks</a>
                <a href="{{route('transaction.create', ['id' => $bank->id, 'view' => 'deposit'])}}" class="btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Deposit</a>
                <a href="{{route('transaction.create', ['id' => $bank->id, 'view' => 'withdraw'])}}" class="btn btn-sm btn-info"><i class="fa fa-plus" aria-hidden="true"></i> Withdraw</a>
                {{-- <a href="{{route('transaction.deposit.create', ['id' => $bank->id])}}" class="btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Deposit</a> --}}
            </div>
            <h6 class="text-center text-dark mb-0">{{$bank->name}} - {{$bank->branch}} - {{$bank->account_no}} - {{$bank->ac_mode}} </h6>


        </div>
        <div class="p-3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 x_content" style="display: none;">
                    <form wire:submit.prevent="search" data-parsley-validate>
                        @csrf
                        <div class="row justify-content-end">
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="supplier-search-area">
                                    <label  class="py-1 border">From Date</label>
                                    <div class="form-group">
                                        <input type="date" id="start_date" wire:model="start_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="supplier-search-area">
                                    <label  class="py-1 border">To Date</label>
                                    <div class="form-group">
                                        <input type="date" id="end_date" wire:model="end_date" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="supplier-search-area">
                                    <label  class="py-1 border">Type</label>
                                    <div class="form-group" wire:ignore>
                                        <select type="search" id="transaction_type" wire:model="transaction_type" name="transaction_type" placeholder="Select Type" class="form-control">
                                            @if ($view == 'deposit')
                                                <option value="deposit">Deposit</option>
                                            @elseif ($view == 'withdraw')
                                                <option value="withdraw">Withdraw</option>
                                            @else
                                                <option value="">All</option>
                                                <option value="deposit">Deposit</option>
                                                <option value="withdraw">Withdraw</option>
                                            @endif
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
                                            @foreach ($payment_types as $types)
                                                <option value="{{$types}}">
                                                    {{ucfirst($types)}}
                                                </option>
                                            @endforeach
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
                            {{-- <form>
                            </form> --}}
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
                                <th class="all">S.N.</th>
                                <th class="all">Date</th>
                                <th class="text-left">Description</th>
                                <th class="all">Type</th>
                                @if ($view == 'deposit')
                                    <th class="all">Deposit</th>
                                @elseif ($view == 'withdraw')
                                    <th class="all">Withdraw</th>
                                @else
                                    <th class="all">Deposit</th>
                                    <th class="all">Withdraw</th>
                                @endif
                                <th class="all">Balance</th>
                                <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $balance = 0;
                                    $total_deposit = 0;
                                    $total_withdraw = 0;
                                @endphp
                                @foreach($transactions as $transaction)
                                    @php
                                        $total_deposit += $transaction->type == 'deposit' ? $transaction->amount : 0;
                                        $total_withdraw += $transaction->type == 'withdraw' ? $transaction->amount : 0;
                                        $balance += $transaction->type == 'deposit' ? $transaction->amount : -$transaction->amount;

                                        $currentPage = method_exists($transactions, 'currentPage') ? $transactions->currentPage() : 1;
                                        $perPage = method_exists($transactions, 'perPage') ? $transactions->perPage() : $transactions->count(); // Fallback to total count
                                        $iteration = ($currentPage - 1) * $perPage + $loop->iteration;
                                    @endphp
                                    <tr style="background-color: {{ $transaction->type == 'deposit' ? '#e1f3eb' : '#f3e1e1' }}">
                                        <td>{{$iteration}}</td>
                                        <td>{{date('d-m-Y',strtotime($transaction->date))}}</td>
                                        <td class="text-left">{{ucfirst($transaction->payment_by)}}{{$transaction->payment_by_bank ? ' - ' . $transaction->payment_by_bank : ''}}{{$transaction->remarks ? ' - ' . $transaction->remarks : ''}}</td>
                                        <td>{{ucfirst($transaction->type)}}</td>
                                        @if ($view == 'deposit')
                                            <td class="text-right">{{$transaction->type == 'deposit' && $transaction->amount > 0  ? number_format($transaction->amount) . '/=' : ''}}</td>
                                        @elseif ($view == 'withdraw')
                                            <td class="text-right">{{$transaction->type == 'withdraw' && $transaction->amount > 0 ? number_format($transaction->amount) . '/=': ''}}</td>
                                        @else
                                            <td class="text-right">{{$transaction->type == 'deposit' && $transaction->amount > 0  ? number_format($transaction->amount) . '/=' : ''}}</td>
                                            <td class="text-right">{{$transaction->type == 'withdraw' && $transaction->amount > 0 ? number_format($transaction->amount) . '/=': ''}}</td>

                                        @endif
                                        <td class="text-right">{{number_format($balance)}}/=</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                    data-toggle="dropdown">
                                                    <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <a href="{{route('transaction.edit', ['view' => $transaction->type, 'id' => $transaction->id])}}" class="btn btn-success"><i class="fa fa-edit" ></i></a>
                                                    </li>
                                                    <li>
                                                        <a href="{{route('transaction.delete',$transaction->id)}}" class="btn btn-danger" id="delete"><i class="fa fa-trash" ></i></a>
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
                                    <td></td>
                                    @if ($view == 'deposit')
                                        <td class="text-right"><b>{{number_format( $total_deposit )}}/=</b></td>
                                    @elseif ($view == 'withdraw')
                                        <td class="text-right"><b>{{number_format( $total_withdraw)}}/=</b></td>
                                    @else
                                        <td class="text-right"><b>{{number_format( $total_deposit )}}/=</b></td>
                                        <td class="text-right"><b>{{number_format( $total_withdraw)}}/=</b></td>
                                    @endif
                                    <td class="text-right"><b>{{number_format( $balance )}}/=</b></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>

                        @if (method_exists($transactions, 'links'))
                            <div class="mt-4 w-100">
                                {{ $transactions->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
