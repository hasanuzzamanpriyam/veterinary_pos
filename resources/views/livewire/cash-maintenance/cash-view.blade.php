@section('page-title', 'Cash Maintenance View')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title px-3 mt-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto">Cash Maintenance View</h2>
                <a href="{{ route('cash_maintenance.index') }}" class="btn btn-secondary btn-sm cursor-pointer"><i
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

            <h1 class="m-0 text-center text-dark">{{date('d-m-Y', strtotime($summary['date']))}}</h1>

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
                                        <td class="text-center">{{isset($summary['prev_balance']) ? formatAmount($summary['prev_balance']) . '/-' : '0'}}</td>
                                        <td class="text-center">{{isset($summary['collection']) ? formatAmount($summary['collection']) . '/-' : '0'}}</td>
                                        <td class="text-center">{{isset($summary['payment']) ? formatAmount($summary['payment']) . '/-' : '0'}}</td>
                                        <td class="text-center">{{isset($summary['expense']) ? formatAmount($summary['expense']) . '/-' : '0'}}</td>
                                        <td class="text-center">{{isset($summary['home_cash']) ? formatAmount($summary['home_cash']) . '/-' : '0'}}</td>
                                        <td class="text-center">{{isset($summary['short_cash']) ? formatAmount($summary['short_cash']) . '/-' : '0'}}</td>
                                        <td class="text-center">{{isset($summary['dokan_cash']) ? formatAmount($summary['dokan_cash']) . '/-' : '0'}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

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
                                    @forelse ( $cash_data as $item )
                                        @if($item->type == 'collection' || $item->type == 'collection_editable')
                                            @php
                                                $ex_c++;
                                            @endphp
                                            <tr>
                                                <td>{{$ex_c}}</td>
                                                <td class="text-left">{{$item->name}}</td>
                                                <td class="text-left">{{$item->address}}</td>
                                                <td>{{$item->mobile}}</td>
                                                <td class="text-right">{{formatAmount($item->amount)}}/-</td>
                                            </tr>
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
                                    @forelse ( $cash_data as $item )
                                        @if($item->type == 'payment' || $item->type == 'payment_editable')
                                            @php
                                                $ex_p++;
                                            @endphp
                                            <tr>
                                                <td>{{$ex_p}}</td>
                                                <td class="text-left">{{$item->name}}</td>
                                                <td class="text-left">{{$item->address}}</td>
                                                <td>{{$item->mobile}}</td>
                                                <td class="text-right">{{formatAmount($item->amount)}}/-</td>
                                            </tr>
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
                                    @forelse ( $cash_data as $item )
                                        @if($item->type == 'expense' || $item->type == 'expense_editable')
                                            @php
                                                $ex_e++;
                                            @endphp
                                            <tr>
                                                <td>{{$ex_e}}</td>
                                                <td class="text-left">{{$item->name}}</td>
                                                <td class="text-left">{{$item->note}}</td>
                                                <td></td>
                                                <td class="text-right">{{formatAmount($item->amount)}}/-</td>
                                            </tr>
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
        </div>
    </div>
</div>


@push('styles')
<style>
    .table {
        table-layout: fixed;
    }
</style>
@endpush
