<div class="summary-report-area">
    @if(count($summary) > 0)
        <div id="summary-table" class="mt-4">
            <div>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th>SL</th>
                            <th>Date</th>
                            <th>Purchase Qty</th>
                            <th>Sale Qty</th>
                            <th>Purchase Tk</th>
                            <th>Sale Tk</th>
                            <th>Collection Tk</th>
                            <th>Payment Tk</th>
                            <th>Expense Tk</th>
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
                                @endphp
                                <tr>
                                    <td>{{$count}}</td>
                                    <td>{{isset($report['date']) ? date('d-m-Y', strtotime($report['date'])) : ''}}</td>
                                    <td>
                                        @if(isset($report['purchase_quantity']) && count($report['purchase_quantity']) > 0)
                                            @php
                                                $purchase_qty = $report['purchase_quantity']->toArray();
                                                ksort($purchase_qty);
                                            @endphp
                                            @foreach ($purchase_qty as $key => $value)
                                                @php
                                                    $total_summary['purchase_qty'][$key] = $total_summary['purchase_qty'][$key] ?? 0;
                                                    $total_summary['purchase_qty'][$key] += $value;
                                                @endphp
                                                <span>{{ number_format($value) }} {{ trans_choice('labels.'.$key, $value) }}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($report['sale_quantity']) && count($report['sale_quantity']) > 0)
                                            @php
                                                $sale_qty = $report['sale_quantity']->toArray();
                                                ksort($sale_qty);
                                            @endphp
                                            @foreach ($sale_qty as $key => $value)
                                                @php
                                                    $total_summary['sale_qty'][$key] = $total_summary['sale_qty'][$key] ?? 0;
                                                    $total_summary['sale_qty'][$key] += $value;
                                                @endphp
                                                <span>{{ number_format($value) }} {{ trans_choice('labels.'.$key, $value) }}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if(isset($report['purchase_amount']) && $report['purchase_amount'] > 0)
                                            @php
                                                $total_summary['purchase_amount'] = $total_summary['purchase_amount'] ?? 0;
                                                $total_summary['purchase_amount'] += $report['purchase_amount'];
                                            @endphp
                                            {{ formatAmount($report['purchase_amount'])}}/-
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if(isset($report['sale_amount']) && $report['sale_amount'] > 0)
                                            @php
                                                $total_summary['sale_amount'] = $total_summary['sale_amount'] ?? 0;
                                                $total_summary['sale_amount'] += $report['sale_amount'];
                                            @endphp
                                            {{ formatAmount($report['sale_amount'])}}/-
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if(isset($report['collection']) && $report['collection'] > 0)
                                            @php
                                                $total_summary['collection'] = $total_summary['collection'] ?? 0;
                                                $total_summary['collection'] += $report['collection'];
                                            @endphp
                                            {{ formatAmount($report['collection'])}}/-
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if(isset($report['payment']) && $report['payment'] > 0)
                                            @php
                                                $total_summary['payment'] = $total_summary['payment'] ?? 0;
                                                $total_summary['payment'] += $report['payment'];
                                            @endphp
                                            {{ formatAmount($report['payment'])}}/-
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if(isset($report['expense']) && $report['expense'] > 0)
                                            @php
                                                $total_summary['expense'] = $total_summary['expense'] ?? 0;
                                                $total_summary['expense'] += $report['expense'];
                                            @endphp
                                            {{ formatAmount($report['expense'])}}/-
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td class="text-center" colspan="9"> No Data Found?</td>
                            </tr>
                        @endforelse
                        <tr class="font-weight-bold">
                            <td class="text-left font-weight-bold" colspan="2">Total</td>
                            <td>
                                @if(isset($total_summary['purchase_qty']) && count($total_summary['purchase_qty']) > 0)
                                    @php
                                        ksort($total_summary['purchase_qty']);
                                    @endphp
                                    @foreach ($total_summary['purchase_qty'] as $key => $value)
                                        <span class="text-center text-nowrap">{{formatAmount($value)}} {{ trans_choice('labels.' . strtolower($key), $value) }} </span>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if(isset($total_summary['sale_qty']) && count($total_summary['sale_qty']) > 0)
                                    @php
                                        ksort($total_summary['sale_qty']);
                                    @endphp
                                    @foreach ($total_summary['sale_qty'] as $key => $value)
                                        <span class="text-center text-nowrap">{{formatAmount($value)}} {{ trans_choice('labels.' . strtolower($key), $value) }} </span>
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-right">{{isset($total_summary['purchase_amount']) ? formatAmount($total_summary['purchase_amount']) . '/=' : '-'}}</td>
                            <td class="text-right">{{isset($total_summary['sale_amount']) ? formatAmount($total_summary['sale_amount']) . '/=' : '-'}}</td>
                            <td class="text-right">{{isset($total_summary['collection']) ? formatAmount($total_summary['collection']) . '/=' : '-'}}</td>
                            <td class="text-right">{{isset($total_summary['payment']) ? formatAmount($total_summary['payment']) . '/=' : '-'}}</td>
                            <td class="text-right">{{isset($total_summary['expense']) ? formatAmount($total_summary['expense']) . '/=' : '-'}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
