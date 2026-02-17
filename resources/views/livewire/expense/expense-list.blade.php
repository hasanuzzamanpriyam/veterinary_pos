@section('page-title', 'Expense List')

<div class="col-md-12 col-sm-12">
    <div class="x_panel p-3">
        <div class="x_title ">
                <div class="header-title d-flex align-items-center gap-2">
                    <h2 class="mr-auto">Expense List</h2>
                    <a href="{{route('expense.create')}}" class="btn btn-md btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add New Expense</a>
                </div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    {{cute_loader()}}
                    <div class="table-header d-flex align-items-center justify-content-between">
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
                        <div class="ajax-search d-flex align-items-center gap-2">
                            <div class="form-group">
                                <div class="input-group date" id="startdatepicker">
                                    <input name="startDate" wire:model="startDate" type="text" class="form-control form-control-sm" placeholder="dd-mm-yyyy">
                                    <div class="input-group-addon py-half">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group date" id="enddatepicker">
                                    <input name="endDate" wire:model="endDate" type="text" class="form-control form-control-sm" placeholder="dd-mm-yyyy">
                                    <div class="input-group-addon py-half">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="text" wire:model="queryString" class="form-control form-control-sm" style="min-width: 300px"/>
                            </div>

                            <div class="form-group">
                                <button type="button" class="btn btn-primary btn-sm" wire:click="supplierOfferSearch" style="min-width: 100px">Get</button>
                                <button type="reset" class="btn btn-warning btn-sm" wire:click="resetData" style="min-width: 100px">Reset</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-box table-responsive">
                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <th class="all">SL</th>
                                <th class="all">Date</th>
                                <th class="all">Voucher</th>
                                <th class="all">Description</th>
                                <th class="all">Paying Method</th>
                                <th class="all">Amount</th>
                                <th class="all" style="width: 50px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach($expense_lists as $expense_item)
                                    @php
                                        $total_amount = $expense_item->expense_type == 'salary_expense' ? $expense_item->amount + $expense_item->other_charge : $expense_item->amount;
                                        $total += $total_amount;
                                        $currentPage = method_exists($expense_lists, 'currentPage') ? $expense_lists->currentPage() : 1;
                                        $perPage = method_exists($expense_lists, 'perPage') ? $expense_lists->perPage() : $expense_lists->count(); // Fallback to total count
                                        $iteration = ($currentPage - 1) * $perPage + $loop->iteration;
                                    @endphp
                                    <tr>
                                        <td>{{ $iteration }}</td>
                                        <td>{{ date('d-m-Y', strtotime($expense_item->date)) }}</td>
                                        <td>{{$expense_item->id}}</td>
                                        @if ($expense_item->expense_type == 'salary_expense')
                                            @php
                                                $employee = $employees->where('id', $expense_item->employee_id)->first();
                                            @endphp
                                            <td class="text-left">Salary: {{$employee->name}} {{$expense_item->remarks ? ' - ' . $expense_item->remarks : ''}}</td>
                                            <td class="text-left">Cash-Self</td>
                                        @else
                                            <td class="text-left">{{$expense_item->expense_type}} {{$expense_item->purpose ? ' - ' . $expense_item->purpose : ''}}</td>
                                            <td class="text-left">{{$expense_item->paying_by}} {{$expense_item->remarks ? ' - ' . $expense_item->remarks : ''}}</td>
                                        @endif
                                        <td class="text-right">{{formatAmount($total_amount) . '/='}}</td>
                                        <td>
                                            @if ($expense_item->expense_type == 'salary_expense')
                                                <div class="d-flex align-items-center gap-2">
                                                    <a href="{{route('salary.expense.edit', $expense_item->id)}}"><i class="fa fa-edit text-primary" style="font-size:18px"></i></a>
                                                    <a href="{{route('salary.expense.delete', $expense_item->id)}}" class="btn btn-sm btn-link p-0" id="delete"><i class="fa fa-trash text-danger" style="font-size:18px"></i></a>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center gap-2">
                                                    <a href="{{route('expense.edit', $expense_item->id)}}"><i class="fa fa-edit text-primary" style="font-size:18px"></i></a>
                                                    <a href="{{route('expense.delete', $expense_item->id)}}" class="btn btn-sm btn-link p-0" id="delete"><i class="fa fa-trash text-danger" style="font-size:18px"></i></a>
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
                                    <td></td>
                                    <td></td>
                                    <td class="text-right font-weight-bold">{{formatAmount($total) . '/='}}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @if (method_exists($expense_lists, 'links'))
                        <div class="mt-4 w-100">
                            {{ $expense_lists->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    jQuery(document).ready(function ($) {
            $('#startdatepicker').datepicker( {
                format: "dd-mm-yyyy",
                autoclose: true,
            });
            $('#enddatepicker').datepicker( {
                format: "dd-mm-yyyy",
                autoclose: true,
            });
            $('#startdatepicker input[name=startDate]').on('change', function(e) {
                @this.set('startDate', e.target.value, false);
            });
            $('#enddatepicker input[name=endDate]').on('change', function(e) {
                @this.set('endDate', e.target.value, false);
            });
        });
    </script>
@endpush
