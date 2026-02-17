<div class="daily-summary-payment-report-area">
    @if(count($ledgers) > 0)
    <div id="payment" class="mt-4">
        <div class="x_title">
           <h4 class="text-center"> <strong>Daily Payment Report</strong></h4>
        </div>
        <table class="table table-striped table-bordered">
            <thead>
                <tr class="text-center">
                    <th>SL</th>
                    <th>Company Name</th>
                    <th>Address</th>
                    <th>Payment Method</th>
                    <th>Amount Tk</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_payment = 0;
                @endphp

                @forelse ($ledgers as $report)
                    @if ($report->payment > 0)
                        @php
                            $total_payment += $report->payment;
                        @endphp
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td class="text-left">{{$report->supplier->company_name}}</td>
                            <td class="text-left">{{$report->supplier->address}}</td>
                            <td class="text-left">{{$report->bank_title ? $report->bank_title . ' ' . $report->payment_remarks : $report->payment_by . ' ' . $report->payment_remarks}}</td>
                            <td class="text-right">{{formatAmount($report->payment)}}/=</td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td class="text-center" colspan="5"> No Data Found?</td>
                    </tr>
                @endforelse

                <tr class="text-right">
                    <td class="text-left font-weight-bold" colspan="4">Total</td>
                    <td class="text-right font-weight-bold total--amount">{{formatAmount($total_payment)}}/=</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif
</div>
