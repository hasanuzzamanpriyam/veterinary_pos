<div class="daily-summary-collection-report-area">
    @if(count($ledgers) > 0)
    <div id="collection" class="mt-4">
        <div class="x_title">
           <h4 class="text-center"> <strong>Daily Collection Report</strong></h4>
        </div>
        <table class="table table-striped table-bordered">
            <thead>
                <tr class="text-center">
                    <th>SL</th>
                    <th>Customer Name</th>
                    <th>Address</th>
                    <th>Collection Method</th>
                    <th>Amount Tk</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_collection = 0;
                @endphp

                @forelse ($ledgers as $report)

                    @if ($report->payment > 0)
                        @php
                            $total_collection += $report->payment;
                        @endphp
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td class="text-left">{{$report->customer->name}}</td>
                            <td class="text-left">{{$report->customer->address}}</td>
                            <td class="text-left">{{$report->bank_title ? $report->bank_title . ' ' . $report->received_by : $report->payment_by . ' ' . $report->received_by}}</td>
                            <td class="text-right">{{formatAmount($report->payment)}}/=</td>
                        </tr>
                    @endif
                    @empty
                    <tr>
                        <td class="text-center" colspan="5">No Data Found?</td>
                    </tr>
                @endforelse
                <tr class="text-right">
                    <td class="text-left font-weight-bold" colspan="4">Total</td>
                    <td class="text-right font-weight-bold">{{formatAmount($total_collection)}}/=</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif
</div>
