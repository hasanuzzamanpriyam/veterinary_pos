

@section('page-title', 'Payment List')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto">Payment List</h2>
                <a href="{{route('employee.payment.create')}}" class="btn btn-md btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Payment</a>
            </div>

        </div>
        <div class="x_content p-3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    {{cute_loader()}}
                    <div class="card-box table-responsive">

                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <th class="all">SL</th>
                                <th class="all">Date</th>
                                <th class="all">Name</th>
                                <th class="all">Address</th>
                                <th class="all">Mobile</th>
                                <th class="all">Designation</th>
                                <th class="all">Paying Method</th>
                                <th class="all">Amount</th>
                                <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_balance = 0;
                                @endphp
                                @foreach($ledgers as $ledger)
                                    @php
                                        $total_balance += $ledger->amount;

                                        $currentPage = method_exists($ledgers, 'currentPage') ? $ledgers->currentPage() : 1;
                                        $perPage = method_exists($ledgers, 'perPage') ? $ledgers->perPage() : $ledgers->count();
                                        $iteration = ($currentPage - 1) * $perPage + $loop->iteration;
                                    @endphp
                                    <tr>
                                        <td>{{$iteration}}</td>
                                        <td>{{date('d-m-Y', strtotime($ledger->created_at))}}</td>
                                        <td class="text-left">{{$ledger->employee->name ?? ''}}</td>
                                        <td class="text-left">{{$ledger->employee->address ?? ''}}</td>
                                        <td>{{$ledger->employee->mobile ?? ''}}</td>
                                        <td>{{$ledger->employee->designation ?? ''}}</td>
                                        <td class="text-left">{{ucfirst($ledger->payment_method)}}{{$ledger->remarks ? ' - ' . ucfirst($ledger->remarks) : ''}}</td>
                                        <td class="text-right">{{$ledger->amount ? formatAmount($ledger->amount) . '/=' : '-'}}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                    data-toggle="dropdown">
                                                    <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a href="{{route('employee.payment.edit', $ledger->id)}}" class="btn btn-success" id="edit"><i class="fa fa-edit" ></i></a></li>
                                                    <li><a href="{{route('employee.ledger.delete', $ledger->id)}}" class="btn btn-danger" id="delete"><i class="fa fa-trash" ></i></a></li>
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
                                    <td class="text-right"><b>{{$total_balance ? formatAmount($total_balance) . '/=' : '-'}}</b></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
