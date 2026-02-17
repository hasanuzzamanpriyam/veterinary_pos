<div class="daily-summary-overview-area">
    @if (count($summary) > 0)
    <div class="mt-4">
        <div class="x_title">
           <h4 class="text-center"> <strong>Daily Summary Overview</strong></h4>
        </div>
        <div>
            <table class="table table-striped table-bordered" style="width: 100%">
                <thead>
                    <tr>
                        <th>Sale</th>
                        <th>Purchase</th>
                        <th>Collection</th>
                        <th>Payment</th>
                        <th>Expense</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <td>
                            @if(isset($summary['totalSale']) && count($summary['totalSale']) > 0)

                                <table class="table table-light table-borderless mb-0">
                                    <tr>
                                        <td>
                                            @if(isset($summary['totalSale']['quantity']))
                                                @php
                                                    $totalQuantity = $summary['totalSale']['quantity']->sortKeys();
                                                @endphp
                                                @forelse ( $totalQuantity as $key => $value )
                                                    <span class="text-center text-nowrap">{{formatAmount($value)}} {{ trans_choice('labels.' . strtolower($key), $value) }} </span>
                                                @empty
                                                @endforelse
                                            @endif
                                        </td>
                                        <td>{{isset($summary['totalSale']['total']) ? formatAmount($summary['totalSale']['total']) . '/=' : '-'}}</td>
                                    </tr>
                                </table>
                            @endif
                        </td>
                        <td>
                            @if(isset($summary['totalPurchase']) && count($summary['totalPurchase']) > 0)

                                <table class="table table-light table-borderless mb-0">
                                    <tr>
                                        <td>
                                            @if(isset($summary['totalPurchase']['quantity']))
                                                @php
                                                    $totalQuantity = $summary['totalPurchase']['quantity']->sortKeys();
                                                @endphp
                                                @forelse ( $totalQuantity as $key => $value )
                                                    <span class="text-center text-nowrap">{{formatAmount($value)}} {{ trans_choice('labels.' . strtolower($key), $value) }} </span>
                                                @empty
                                                @endforelse
                                            @endif
                                        </td>
                                        <td>{{isset($summary['totalPurchase']['total']) ? formatAmount($summary['totalPurchase']['total']) . '/=' : '-'}}</td>
                                    </tr>
                                </table>
                            @endif
                        </td>
                        {{-- <td>{{isset($summary['totalPurchase']) && $summary['totalPurchase'] > 0 ? formatAmount($summary['totalPurchase']) . '/=' : '-'}}</td> --}}
                        <td>{{isset($summary['totalCollection']) && $summary['totalCollection'] > 0 ? formatAmount($summary['totalCollection']) . '/=' : '-'}}</td>
                        <td>{{isset($summary['totalPayment']) && $summary['totalPayment'] > 0 ? formatAmount($summary['totalPayment']) . '/=' : '-'}}</td>
                        <td>{{isset($summary['totalExpense']) && $summary['totalExpense'] > 0 ? formatAmount($summary['totalExpense']) . '/=' : '-'}}</td>

                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
