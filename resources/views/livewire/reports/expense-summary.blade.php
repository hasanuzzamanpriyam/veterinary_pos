<div class="daily-summary-expense-report-area">
    @if (count($ledgers) > 0)
    <div id="expense" class="mt-4">
        <div class="x_title">
           <h4 class="text-center"> <strong>Daily Expense Report</strong></h4>
        </div>
        <div>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr class="text-center">
                        <th>SL</th>
                        <th>Description</th>
                        <th>Note/Purpose</th>
                        <th>Payment Method</th>
                        <th>Amount Tk</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_expenses = 0;
                    @endphp
                    @forelse ($ledgers as $report)
                        @php
                            $total = $report->amount + $report->other_charge;
                            $total_expenses += $total;
                        @endphp
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td class="text-left">
                                @if ($report->expense_type == 'salary_expense')

                                    Salary: {{$report->employee->name}}
                                @else
                                    {{$report->expense_type}}
                                @endif
                            </td>
                            <td class="text-left">{{$report->purpose ?? '-'}}</td>
                            <td class="text-left">
                                @if ($report->expense_type == 'salary_expense')
                                    Cash-Self
                                @else
                                    {{$report->paying_by}}{{$report->remarks ? ' - ' . $report->remarks : ''}}
                                @endif
                            </td>
                            <td class="text-right">{{formatAmount($total)}}/=</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="5"> No Data Found?</td>
                        </tr>
                    @endforelse
                    <tr class="text-right">
                        <td class="text-left font-weight-bold" colspan="4">Total</td>
                        <td class="text-right font-weight-bold">{{formatAmount($total_expenses)}}/=</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
