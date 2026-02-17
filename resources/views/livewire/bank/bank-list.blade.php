@section('page-title', 'Bank List')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto">Bank List</h2>
                <a href="{{route('transaction.create', ['view' => 'deposit'])}}" class="btn btn-md btn-info"><i class="fa fa-arrow-down" aria-hidden="true"></i> Deposit</a>
                <a href="{{route('transaction.create', ['view' => 'withdraw'])}}" class="btn btn-md btn-danger"><i class="fa fa-arrow-up" aria-hidden="true"></i> Withdraw</a>
                <a href="{{route('bank.create')}}" class="btn btn-md btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Bank</a>
            </div>


        </div>
        <div class="x_content p-3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 ">
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

                        <div class="ajax-search">
                            <div class="form-group">
                                <input type="text" id="customer-search" class="form-control" style="min-width: 342px" placeholder="Name, Address or Phone" wire:model.live.debounce.500ms="queryString" />
                            </div>
                        </div>
                    </div>
                    <div class="card-box table-responsive">
                        {{-- notification message --}}
                        @if(session()->has('msg'))
                            <div class="text-center alert alert-success">
                                {{session()->get('msg')}}
                            </div>
                        @endif
                        <table id="" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <th class="all">SL</th>

                                <th class="all">Bank Name</th>
                                <th class="all">Branch Name</th>
                                <th class="all">Account No.</th>
                                <th class="all">Routing No</th>
                                <th class="all">Bank Title</th>
                                <th class="all">AC Mode</th>
                                <th class="all">Deposit</th>
                                <th class="all">Withdraw</th>
                                <th class="all">Balance</th>
                                <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $summary = [];
                                @endphp
                                @foreach($banks as $bank)
                                    @php
                                        $summary['deposit'] = $summary['deposit'] ?? 0;
                                        $summary['withdraw'] = $summary['withdraw'] ?? 0;
                                        $summary['balance'] = $summary['balance'] ?? 0;
                                        $deposit = $transactions->where('bank_id', $bank->id)->sum('total_deposit');
                                        $withdraw = $transactions->where('bank_id', $bank->id)->sum('total_withdraw');
                                        $summary['deposit'] += $deposit;
                                        $summary['withdraw'] += $withdraw;
                                        $summary['balance'] += $bank->balance;
                                        // $summary['deposit'] += $transactions->where('bank_id', $bank->id)->sum('total_deposit');

                                        $currentPage = method_exists($banks, 'currentPage') ? $banks->currentPage() : 1;
                                        $perPage = method_exists($banks, 'perPage') ? $banks->perPage() : $banks->count(); // Fallback to total count
                                        $iteration = ($currentPage - 1) * $perPage + $loop->iteration;
                                    @endphp
                                    <tr>
                                        <td>{{$iteration}}</td>

                                        <td class="text-left">{{$bank->name}}</td>
                                        <td  class="text-left">{{$bank->branch}}</td>
                                        <td  class="text-left">{{$bank->account_no}}</td>
                                        <td  class="text-left">{{$bank->code}}</td>
                                        <td  class="text-left">{{$bank->title}}</td>
                                        <td>{{$bank->ac_mode}}</td>
                                        <td class="text-right">{{$deposit > 0 ? formatAmount($deposit ?? 0) . '/=' : '-'}}</td>
                                        <td class="text-right">{{$withdraw > 0 ? formatAmount($withdraw ?? 0) . '/=' : '-'}}</td>
                                        <td class="text-right"> {{$bank->balance ? formatAmount($bank->balance ?? 0) . '/=' : '-'}}</td>
                                        <td>
                                            <div class="btn-group btn-group-vertical customer_diplay_list">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                    data-toggle="dropdown">
                                                    <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a href="{{route('bank.edit',$bank->id)}}" class="btn btn-success btn-sm w-20">Edit <i class="fa fa-edit"></i></a></li>
                                                    <li><a href="{{route('bank.delete',$bank->id)}}" class="btn btn-danger btn-sm w-20" id="delete">Delete <i class="fa fa-trash"></i></a></li>
                                                    <li><a href="{{ route('transaction.bank.statement', $bank->id) }}" class="btn btn-info btn-sm w-20">Transactions <i class="fa fa-eye"></i></a></li>
                                                    <li><a href="{{ route('transaction.bank.statement', ['id' => $bank->id, 'view' => 'deposit']) }}" class="btn btn-info btn-sm w-20">Deposit <i class="fa fa-book"></i></a></li>
                                                    <li><a href="{{ route('transaction.bank.statement', ['id' => $bank->id, 'view' => 'withdraw']) }}" class="btn btn-info btn-sm w-20">Withdraw <i class="fa fa-tasks"></i></a></li>
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
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">
                                        {{isset($summary['deposit']) && $summary['deposit'] > 0 ? formatAmount($summary['deposit']) . '/=' : '-'}}
                                    </td>
                                    <td class="text-right">
                                        {{isset($summary['withdraw']) && $summary['withdraw'] > 0 ? formatAmount($summary['withdraw']) . '/=' : '-'}}
                                    </td>
                                    <td class="text-right">
                                        {{isset($summary['balance']) ? formatAmount($summary['balance'] ?? 0) . '/=' : '-'}}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @if (method_exists($banks, 'links'))
                        <div class="mt-4 w-100">
                            {{ $banks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
