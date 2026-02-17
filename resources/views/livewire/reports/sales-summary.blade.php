<div class="daily-summary-sales-report-area">
    @if (count($ledgers) > 0)
        <div id="sales" class="mt-4">
            <div class="x_title">
                <h4 class="text-center"> <strong>Daily Sales Report</strong></h4>
            </div>
            <div>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th>SL</th>
                            <th>Customer Name</th>
                            <th>Address</th>
                            <th>Qty</th>
                            <th>Amount Tk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_sales = 0;
                            $total_qty_summary = [];
                        @endphp
                        @forelse ($ledgers as $report)
                            @if ($report->type == 'sale')
                                @php
                                    $total_sales += $report->total_price;
                                    $list_products = $products->where('transaction_id', $report->id);
                                    $qty_summary = [];
                                @endphp
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td class="text-left">{{$report->customer->name}}</td>
                                    <td class="text-left">{{$report->customer->address}}</td>
                                    <td class="text-center">
                                        @foreach ($list_products as $item)
                                            @php
                                                // Qty Summary
                                                $qty_summary[$item->product->type] = $qty_summary[$item->product->type] ?? 0;
                                                $qty_summary[$item->product->type] += $item->quantity - $item->discount_qty;

                                                // Total Qty Summary
                                                $total_qty_summary[$item->product->type] = $total_qty_summary[$item->product->type] ?? 0;
                                                $total_qty_summary[$item->product->type] += $item->quantity - $item->discount_qty;
                                            @endphp
                                        @endforeach

                                        @php
                                            ksort($qty_summary);
                                        @endphp

                                        @foreach ($qty_summary as $key => $value)
                                            <span class="text-center">{{formatAmount($value)}}
                                                {{ trans_choice('labels.' . strtolower($key), $value) }} </span>
                                        @endforeach
                                    </td>
                                    <td class="text-right">{{formatAmount($report->total_price)}}/=</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td class="text-center" colspan="5"> No Data Found?</td>
                            </tr>
                        @endforelse
                        <tr class="text-right">
                            <td class="text-left font-weight-bold" colspan="3">Total</td>
                            <td class="text-center font-weight-bold">
                                @php
                                    ksort($total_qty_summary);
                                @endphp
                                @foreach ($total_qty_summary as $key => $value)
                                    <span class="text-center text-nowrap">{{formatAmount($value)}}
                                        {{ trans_choice('labels.' . strtolower($key), $value) }} </span>
                                @endforeach
                            </td>
                            <td class="text-right font-weight-bold total--amount">{{formatAmount($total_sales)}}/=</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>