@section('page-title', '---')
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title">


            <div class="header-title d-flex align-items-center gap-2 p-3">
                <h2 class="mr-auto">Expense Report </h2>
                {{-- @if($get_expense) <a href="{{route('expense.report.pdf',[$start_date, $end_date, $get_expense])}}" class="btn btn-info btn-link"> Download <i class="fa fa-file-pdf-o text-danger"></i></a>@endif --}}
            </div>


        </div>
        <div class="x_content">
            <br />
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
                <form wire:submit.prevent=expenseReportSearch()>
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="supplier-search-area">
                                <label class="d-block py-1 border" for="supplier_search">From Date:</label>
                                <div class="form-group">
                                  <input type="date" name="start_date" wire:model="start_date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="supplier-search-area">
                                <label class="d-block py-1 border" for="supplier_search">To Date:</label>
                                <div class="form-group">
                                  <input type="date" name="end_date" wire:model="end_date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="supplier-search-area">
                                <label class="py-1 border" for="supplier_search">Select Expense Type:</label>
                                <div class="form-group">
                                    <select type="text" id="get_expense"  wire:model="get_expense" name="get_expense"  class="form-control">
                                        <option>Select an Option</option>
                                        <option value="salary_expense">Salary Expense</option>
                                        <option value="bank_expense">Bank Expense</option>
                                        <option value="labour_expense">Labour Expense</option>
                                        <option value="carring_expense">Carring Expense</option>
                                        <option value="dokan_expense">Dokan Expense</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="supplier-search-button pt-4">
                                <div class="form-group pt-3">
                                    <button type="submit" class="btn btn-success">Search</button>
                                    <button type="button" wire:click="resetExpenses" class="btn btn-danger">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="due-list-area mt-4">
                            @if($labours)
                                <div class="x_title">
                                    <h3 class="text-center"> <strong>{{$expense_name}} Expense Report</strong></h3>
                                </div>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Date</th>
                                            <th>Voucher No.</th>
                                            <th>Expense Amount</th>
                                            <th>Payment Amount</th>
                                            <th>Paying By</th>
                                            <th>Purpose</th>
                                            <th>Receive</th>
                                            <th>Remarks</th>
                                            <th>Created by</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_quantity = 0;
                                            $total_amount = 0;

                                        @endphp
                                        @forelse ($labours as $report )


                                        <tr>
                                            <td>{{date('d-m-Y', strtotime($report->date))}}</td>
                                            <td>{{$report->voucher_no}}</td>
                                            <td class="text-right">{{$report->amount}}/=</td>
                                            <td class="text-right">{{$report->payment_amount}}/=</td>
                                            <td class="text-center">{{$report->payment_by}}</td>
                                            <td class="text-wrap">{{$report->purpose }}</td>
                                            <td class="text-wrap">{{$report->receive}}</td>
                                            <td class="text-wrap">{{$report->remarks}}</td>
                                            <td class="text-center">{{$report->created_by}}</td>

                                        </tr>
                                        @empty
                                        <tr>
                                            <td class="text-center h4" colspan="10">No data found!</td>
                                        </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                                @elseif($salaries)
                                <div class="x_title">
                                    <h3 class="text-center"> <strong>{{$expense_name}} Expense Report</strong></h3>
                                </div>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th>SL. No.</th>
                                            <th>Date</th>
                                            <th>Voucher No.</th>
                                            <th>Employee Name</th>
                                            <th>Salary Amount</th>
                                            <th>Salary Month</th>
                                            <th>Payment Anount</th>
                                            <th>Payment Type</th>
                                            <th>Remarks</th>
                                            <th>Created by</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_quantity = 0;
                                            $total_amount = 0;

                                        @endphp
                                        @forelse ($salaries as $report )

                                        {{-- @php
                                            $total_quantity += $report->total_qty;
                                            $total_amount += $report->total_price;
                                        @endphp --}}
                                        <tr>
                                            <td class="text-center">{{$loop->iteration}}</td>
                                            <td class="text-center">{{date('d-m-Y', strtotime($report->date))}}</td>
                                            <td class="text-center">{{$report->voucher_no}}</td>
                                            <td class="text-center">{{$report->employee->name}}</td>
                                            <td class="text-right">{{$report->salary_amount}}/=</td>
                                            <td class="text-center">{{$report->salary_month}}</td>
                                            <td class="text-right">{{$report->payment_amount}}/=</td>
                                            <td class="text-center">{{$report->payment_by}}</td>
                                            <td class="text-wrap">{{$report->remarks}}</td>
                                            <td class="text-center">{{$report->created_by}}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td class="text-center h4" colspan="10">No data found!</td>
                                        </tr>
                                        @endforelse
                                        {{-- <tr class="text-right">
                                            <td colspan="8">Total Quantity = {{$total_quantity}} Pcs/Bags</td>
                                            <td colspan="1">Total Amount = {{$total_amount}} /=</td>
                                        </tr> --}}
                                    </tbody>

                                </table>
                                @elseif($banks)
                                <div class="x_title">
                                    <h3 class="text-center"> <strong>{{$expense_name}} Expense Report</strong></h3>
                                </div>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th>SL. No.</th>
                                            <th>Date</th>
                                            <th>Voucher No.</th>
                                            <th>Bank Name</th>
                                            <th>Profit Amount</th>
                                            <th>Profit Month</th>
                                            <th>Other Charge</th>
                                            <th>Payment Anount</th>
                                            <th>Payment Type</th>
                                            <th>Remarks</th>
                                            <th>Created by</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_quantity = 0;
                                            $total_amount = 0;

                                        @endphp
                                        @forelse ($banks as $report )

                                        {{-- @php
                                            $total_quantity += $report->total_qty;
                                            $total_amount += $report->total_price;
                                        @endphp --}}
                                        <tr>
                                            <td class="text-center">{{$loop->iteration}}</td>
                                            <td class="text-center">{{date('d-m-Y', strtotime($report->date))}}</td>
                                            <td class="text-center">{{$report->voucher_no}}</td>
                                            <td class="text-center">{{$report->bank->name}}</td>
                                            <td class="text-right">{{$report->profit_amount}}/=</td>
                                            <td class="text-center">{{$report->profit_month}}</td>
                                            <td class="text-center">{{$report->other_charge}}=/</td>
                                            <td class="text-right">{{$report->payment_amount}}/=</td>
                                            <td class="text-center">{{$report->payment_by}}</td>
                                            <td class="text-wrap">{{$report->remarks}}</td>
                                            <td class="text-center">{{$report->created_by}}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td class="text-center h4" colspan="10">No data found!</td>
                                        </tr>
                                        @endforelse
                                        {{-- <tr class="text-right">
                                            <td colspan="8">Total Quantity = {{$total_quantity}} Pcs/Bags</td>
                                            <td colspan="1">Total Amount = {{$total_amount}} /=</td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                                @elseif($carrings)
                                <div class="x_title">
                                    <h3 class="text-center"> <strong>{{$expense_name}} Expense Report</strong></h3>
                                </div>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th>SL. No.</th>
                                            <th>Date</th>
                                            <th>Voucher No.</th>
                                            <th>Driver Name</th>
                                            <th>Gari Number</th>
                                            <th>Load Point</th>
                                            <th>Delivery Point</th>
                                            <th>Payment Anount</th>
                                            <th>Remarks</th>
                                            <th>Created by</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_quantity = 0;
                                            $total_amount = 0;

                                        @endphp
                                        @forelse ($carrings as $report )

                                        {{-- @php
                                            $total_quantity += $report->total_qty;
                                            $total_amount += $report->total_price;
                                        @endphp --}}
                                        <tr>
                                            <td class="text-center">{{$loop->iteration}}</td>
                                            <td class="text-center">{{date('d-m-Y', strtotime($report->date))}}</td>
                                            <td class="text-center">{{$report->voucher_no}}</td>
                                            <td class="text-center">{{$report->driver_name}}</td>
                                            <td class="text-center">{{$report->gary_number}}</td>
                                            <td class="text-right">{{$report->load_point}}/=</td>
                                            <td class="text-right">{{$report->delivery_point}}</td>
                                            <td class="text-right">{{$report->payment_amount}}/=</td>
                                            <td class="text-wrap">{{$report->remarks}}</td>
                                            <td class="text-center">{{$report->created_by}}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td class="text-center h4" colspan="10">No data found!</td>
                                        </tr>
                                        @endforelse
                                        {{-- <tr class="text-right">
                                            <td colspan="8">Total Quantity = {{$total_quantity}} Pcs/Bags</td>
                                            <td colspan="1">Total Amount = {{$total_amount}} /=</td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                                @elseif($dokans)
                                <div class="x_title">
                                    <h3 class="text-center"> <strong>{{$expense_name}} Expense Report</strong></h3>
                                </div>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th>SL. No.</th>
                                            <th>Date</th>
                                            <th>Voucher No.</th>
                                            <th>Dokan Rent</th>
                                            <th>Rent Amount</th>
                                            <th>Rent Month</th>
                                            <th>Payment By</th>
                                            <th>Receive By</th>
                                            <th>Remarks</th>
                                            <th>Created by</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_quantity = 0;
                                            $total_amount = 0;

                                        @endphp
                                        @forelse ($dokans as $report )

                                        {{-- @php
                                            $total_quantity += $report->total_qty;
                                            $total_amount += $report->total_price;
                                        @endphp --}}
                                        <tr>
                                            <td class="text-center">{{$loop->iteration}}</td>
                                            <td class="text-center">{{date('d-m-Y', strtotime($report->date))}}</td>
                                            <td class="text-center">{{$report->voucher_no}}</td>
                                            <td class="text-center">{{$report->dokan_rent}}/=</td>
                                            <td class="text-center">{{$report->rent_amount}}/=</td>
                                            <td class="text-right">{{$report->rent_month}}</td>
                                            <td class="text-right">{{$report->payment_by}}</td>
                                            <td class="text-right">{{$report->receiving_by}}</td>
                                            <td class="text-wrap">{{$report->remarks}}</td>
                                            <td class="text-center">{{$report->created_by}}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td class="text-center h4" colspan="10">No data found!</td>
                                        </tr>
                                        @endforelse
                                        {{-- <tr class="text-right">
                                            <td colspan="8">Total Quantity = {{$total_quantity}} Pcs/Bags</td>
                                            <td colspan="1">Total Amount = {{$total_amount}} /=</td>
                                        </tr> --}}
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


