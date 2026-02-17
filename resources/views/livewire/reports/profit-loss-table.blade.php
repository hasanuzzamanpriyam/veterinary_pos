<div class="summary-report-area">
    @if(count($summary) > 0)
        <div id="summary-table" class="mt-4">
            <div>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th>SL</th>
                            <th>Date</th>
                            <th>Sale Amount</th>
                            <th>Purchase Amount</th>
                            <th>Expense Amount</th>
                            <th>Gross Profit/Loss</th>
                            <th>Bonus Amount</th>
                            <th>Net Profit/Loss</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $count = 0;
                            $total_summary = [];
                        @endphp
                        @forelse ($summary as $report)
                            @if(count($report) > 0)
                                @php
                                    $count++;
                                    $saleAmount = isset($report['sale']) ? $report['sale'] : 0;
                                    $purchaseAmount = isset($report['purchase']) ? $report['purchase'] : 0;
                                    $expenseAmount = isset($report['expense']) ? $report['expense'] : 0;
                                    $grossProfit = $saleAmount - $purchaseAmount - $expenseAmount;
                                    $bonusAmount = isset($report['bonusAmount']) ? $report['bonusAmount'] : 0;
                                    $netProfit = $grossProfit + $bonusAmount;
                                    $total_summary['sale'] = $total_summary['sale'] ?? 0;
                                    $total_summary['sale'] += $saleAmount;
                                    $total_summary['purchase'] = $total_summary['purchase'] ?? 0;
                                    $total_summary['purchase'] += $purchaseAmount;
                                    $total_summary['expense'] = $total_summary['expense'] ?? 0;
                                    $total_summary['expense'] += $expenseAmount;
                                    $total_summary['grossProfit'] = $total_summary['grossProfit'] ?? 0;
                                    $total_summary['grossProfit'] += $grossProfit;
                                    $total_summary['bonusAmount'] = $total_summary['bonusAmount'] ?? 0;
                                    $total_summary['bonusAmount'] += $bonusAmount;
                                    $total_summary['netProfit'] = $total_summary['netProfit'] ?? 0;
                                    $total_summary['netProfit'] += $netProfit;
                                @endphp
                                <tr>
                                    <td>{{$count}}</td>
                                    <td class="text-left">{{isset($report['date']) ? date('F Y', strtotime($report['date'] . '-01')) : ''}}</td>
                                    <td class="text-right">{{ $saleAmount ? formatAmount($saleAmount) . '/-' : '-'}}</td>
                                    <td class="text-right">{{ $purchaseAmount ? formatAmount($purchaseAmount) . '/-' : '-'}}</td>
                                    <td class="text-right">{{ $expenseAmount ? formatAmount($expenseAmount) . '/-' : '-'}}</td>
                                    <td class="text-right">{{ $grossProfit ? formatAmount($grossProfit) . '/-' : '-'}}</td>
                                    <td class="text-right">{{ $bonusAmount ? formatAmount($bonusAmount) . '/-' : '-'}}</td>
                                    <td class="text-right">{{ $netProfit ? formatAmount($netProfit) . '/-' : '-'}}</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td class="text-center" colspan="8"> No Data Found?</td>
                            </tr>
                        @endforelse
                        <tr class="font-weight-bold">
                            <td class="text-left font-weight-bold" colspan="2">Total</td>
                            <td class="text-right">{{isset($total_summary['sale']) ? formatAmount($total_summary['sale']) . '/=' : '-'}}</td>
                            <td class="text-right">{{isset($total_summary['purchase']) ? formatAmount($total_summary['purchase']) . '/=' : '-'}}</td>
                            <td class="text-right">{{isset($total_summary['expense']) ? formatAmount($total_summary['expense']) . '/=' : '-'}}</td>
                            <td class="text-right">{{isset($total_summary['grossProfit']) ? formatAmount($total_summary['grossProfit']) . '/=' : '-'}}</td>
                            <td class="text-right">{{isset($total_summary['bonusAmount']) ? formatAmount($total_summary['bonusAmount']) . '/=' : '-'}}</td>
                            <td class="text-right">{{isset($total_summary['netProfit']) ? formatAmount($total_summary['netProfit']) . '/=' : '-'}}</td>
                        </tr>

                    </tbody>
                </table>

                @php
                    $net = isset($total_summary['netProfit']) ? $total_summary['netProfit'] : 0;
                    $stocked = isset($stockedPurchase) ? $stockedPurchase : 0;
                    // Use purchased_rate_total (purchase_rate × quantity) for stocked products on today's date
                    $purchasedRateTotal = isset($purchased_rate_total) ? $purchased_rate_total : $stocked;
                    $today = \Carbon\Carbon::today();
                    $isEndDateToday = isset($endDate) && \Carbon\Carbon::parse($endDate)->isSameDay($today);

                    // Only add purchase rate to net profit/loss when end date is today
                    if ($isEndDateToday && $stocked > 0) {
                        $productSummary = $net + $purchasedRateTotal;
                    } else {
                        $productSummary = $net;
                    }
                @endphp

                @if($isEndDateToday)
                    @if($stocked > 0)
                    {{-- Today's date with stocked products: Show Net Profit/Loss + Purchase Rate --}}
                    <div class="product-summary-detail mt-3 p-3 border bg-light">
                        <h5 class="mb-3">Product Summary — Detailed Calculation (Today's Report)</h5>
                        <div class="d-flex justify-content-between py-1">
                            <div>Net Profit / Loss (from sales, purchases & expenses)</div>
                            <div class="text-right">{{ formatAmount($net) }}/=</div>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <div>Today's Stocked Products Value (Purchase Rate × Stock Quantity)</div>
                            <div class="text-right">{{ formatAmount($purchasedRateTotal) }}/=</div>
                        </div>
                        <hr />
                        <div class="d-flex justify-content-between font-weight-bold py-1">
                            <div>Product Summary = Net Profit/Loss + Stocked Products Value</div>
                            <div class="text-right text-success">{{ formatAmount($productSummary) }}/=</div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fa fa-info-circle"></i> Stocked products value is included only for today's date.
                            </small>
                        </div>
                    </div>
                    @else
                    {{-- Today's date with NO stocked products: Show only Net Profit/Loss --}}
                    <div class="product-summary-detail mt-3 p-3 border bg-light">
                        <h5 class="mb-3">Product Summary — Detailed Calculation (Today's Report)</h5>
                        <div class="d-flex justify-content-between py-1 font-weight-bold">
                            <div>Net Profit / Loss (from sales, purchases & expenses)</div>
                            <div class="text-right text-success">{{ formatAmount($net) }}/=</div>
                        </div>
                        <div class="mt-2">
                            <div class="alert alert-info mb-0">
                                <i class="fa fa-info-circle"></i> No products in stock. Purchase rate value not added to the calculation.
                            </div>
                        </div>
                    </div>
                    @endif
                @else
                    {{-- Historical date: Show only Net Profit/Loss --}}
                    <div class="product-summary-detail mt-3 p-3 border bg-light">
                        <h5 class="mb-3">Product Summary — Detailed Calculation</h5>
                        <div class="d-flex justify-content-between py-1 font-weight-bold">
                            <div>Net Profit / Loss (from sales, purchases & expenses)</div>
                            <div class="text-right text-success">{{ formatAmount($net) }}/=</div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fa fa-info-circle"></i> Stocked products value is only calculated for today's date reports.
                            </small>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
