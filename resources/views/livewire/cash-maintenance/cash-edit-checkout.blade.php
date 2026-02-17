@section('page-title', 'Cash Maintenance Edit Checkout')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title px-3 mt-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto">Cash Maintenance Edit Checkout</h2>
                <a href="{{ route('cash_maintenance.edit', $id) }}" class="btn btn-secondary btn-sm cursor-pointer"><i
                        class="fa fa-arrow-left"></i> Back</a>
            </div>

        </div>
        <div class="x_content px-3">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h1 class="m-0 text-center text-dark">{{date('d-m-Y', strtotime($date))}}</h1>
                {{-- @dump($allData, $summary) --}}

            <section class="summary">
                <div class="row">
                    <div class="col-md-12">
                        <h5>Summary</h5>
                        <div class="responsive-table ">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">Dokan Cash (prev)</th>
                                        <th class="text-center">Collection</th>
                                        <th class="text-center">Payment</th>
                                        <th class="text-center">Expense</th>
                                        <th class="text-center">Home Cash</th>
                                        <th class="text-center">Short Cash</th>
                                        <th class="text-center">Dokan Cash</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        {{-- <td class="text-center">{{$prev_balance ? formatAmount($prev_balance) . '/-' : '0'}}</td> --}}
                                        <td class="text-center">{{isset($summary['prev_balance']) ? formatAmount($summary['prev_balance']) . '/-' : '0'}}</td>
                                        <td class="text-center">{{isset($summary['collection']) ? formatAmount($summary['collection']) . '/-' : '0'}}</td>
                                        <td class="text-center">{{isset($summary['payment']) ? formatAmount($summary['payment']) . '/-' : '0'}}</td>
                                        <td class="text-center">{{isset($summary['expense']) ? formatAmount($summary['expense']) . '/-' : '0'}}</td>
                                        <td class="text-center">{{isset($summary['home_cash']) ? formatAmount($summary['home_cash']) . '/-' : '0'}}</td>
                                        <td class="text-center">{{isset($summary['short_cash']) ? formatAmount($summary['short_cash']) . '/-' : '0'}}</td>
                                        <td class="text-center">
                                            @php
                                                $balance = floatval($summary['prev_balance'])
                                                        + floatval($summary['collection'])
                                                        - floatval($summary['payment'])
                                                        - floatval($summary['expense'])
                                                        - floatval($summary['home_cash'])
                                                        - floatval($summary['short_cash']);
                                            @endphp
                                            {{$balance ? formatAmount($balance) . '/-' : '0'}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            {{-- @dump($allData) --}}

            <section class="collection">
                <div class="row">
                    <div class="col-md-12">
                        <h5>Collection info</h5>
                        <div class="responsive-table">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 40px">SL</th>
                                        <th class="text-left" style="width: 30%">Customer Name</th>
                                        <th class="text-left" style="width: 40%">Address</th>
                                        <th class="text-center" style="width: 100px">Mobile</th>
                                        <th class="text-right" style="width: 90px">Amount Tk</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $ex_c = 0;
                                    @endphp
                                    @forelse ( $allData as $key => $items )
                                        @if($key == 'collection' || $key == 'collection_editable')
                                            @php
                                                $ex_c++;
                                            @endphp
                                            @foreach ( $items as $item )
                                                <tr>
                                                    <td>{{$ex_c}}</td>
                                                    <td class="text-left">{{$item['name']}}</td>
                                                    <td class="text-left">{{$item['address']}}</td>
                                                    <td>{{$item['mobile']}}</td>
                                                    <td class="text-right">{{formatAmount($item['amount'])}}/-</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <p>Nothing to show</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section class="payment">
                <div class="row">
                    <div class="col-md-12">
                        <h5>Payment info</h5>
                        <div class="responsive-table">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 40px">SL</th>
                                        <th class="text-left" style="width: 30%">Supplier Name</th>
                                        <th class="text-left" style="width: 40%">Address</th>
                                        <th class="text-center" style="width: 100px">Mobile</th>
                                        <th class="text-right" style="width: 90px">Amount Tk</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $ex_p = 0;
                                    @endphp
                                    @forelse ( $allData as $key => $items )
                                    @if($key == 'payment' || $key == 'payment_editable')
                                        @php
                                            $ex_p++;
                                        @endphp
                                        @foreach ( $items as $item )
                                            <tr>
                                                <td>{{$ex_p}}</td>
                                                <td class="text-left">{{$item['name']}}</td>
                                                <td class="text-left">{{$item['address']}}</td>
                                                <td>{{$item['mobile']}}</td>
                                                <td class="text-right">{{formatAmount($item['amount'])}}/-</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @empty
                                        <tr>
                                            <td colspan="5">
                                                <p>Nothing to show</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section class="expense">
                <div class="row">
                    <div class="col-md-12">
                        <h5>Expense info</h5>
                        <div class="responsive-table">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 40px">SL</th>
                                        <th class="text-left" style="width: 30%">Expense Name</th>
                                        <th class="text-left" style="width: 40%">Purpose/Note</th>
                                        <th class="text-left" style="width: 100px"></th>
                                        <th class="text-right" style="width: 90px">Amount Tk</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $ex_e = 0;
                                    @endphp
                                    @forelse ( $allData as $key => $items )
                                        @if($key == 'expense' || $key == 'expense_editable')
                                            @php
                                                $ex_e++;
                                            @endphp
                                            @foreach ( $items as $item )
                                                <tr>
                                                    <td>{{$ex_e}}</td>
                                                    <td class="text-left">{{$item['name']}}</td>
                                                    <td class="text-left">{{$item['note']}}</td>
                                                    <td></td>
                                                    <td class="text-right">{{formatAmount($item['amount'])}}/-</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                <p>Nothing to show</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section class="footer">
                <div class="ln_solid"></div>
                <div class="item form-group">
                    <div class="col-md-12 col-sm-12 text-center gap-2">
                        <button type="button" class="btn btn-danger" wire:click="cancel" @disabled(!$date)>Cancel</button>
                        <a href="{{ route('cash_maintenance.edit', $id) }}" class="btn btn-warning cursor-pointer text-dark"><i
                            class="fa fa-arrow-left"></i> Edit</a>
                        <button type="button" class="btn btn-success" wire:click="update" @disabled(!$date)><i
                            class="fa fa-save"></i> Submit</button>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function goBack() {
        window.history.back();
    }
</script>
@endpush
@push('styles')
<style>
    .table {
        table-layout: fixed;
    }
</style>
@endpush
