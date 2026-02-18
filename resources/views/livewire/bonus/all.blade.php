@section('page-title', 'Total Bonus Calculation')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2>Total Bonus Calculation</h2>
                <a href="#" class="mr-auto ml-3 cursor-pointer" onclick="history.back()"><i class="fa fa-close"></i></a>
            </div>

        </div>
        <div class="x_content">
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
                <form wire:submit.prevent=supplierDueSearch()>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="row justify-content-center">
                            <div class="col-lg-2 col-md-2 col-sm-12">
                                <div class="supplier-search-area">
                                    <div class="form-group">
                                        <div class="input-group date" id="startdatepicker">
                                            <input name="startDate" wire:model="startDate" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                            <div class="input-group-addon py-half">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-12">
                                <div class="supplier-search-area">
                                    <div class="form-group">
                                        <div class="input-group date" id="enddatepicker">
                                            <input name="endDate" wire:model="endDate" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                            <div class="input-group-addon py-half">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-6">
                                <div class="supplier-search-button">
                                    <div class="form-group d-flex gap-2">
                                        <button type="submit" class="btn btn-success btn-sm">Get</button>
                                        <button type="button" wire:click="searchReset" class="btn btn-danger btn-sm">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="due-list-area mt-4">
                            <h3 class="text-center font-weight-bold text-dark m-0">Total Bonus Calculation</h3>
                            <p class="text-center font-weight-bold text-dark">(Yearly + Monthly + Cash Offers)</p>
                            @if ($startDate && $endDate)
                            <h2 class="text-center"><span class="text-danger font-weight-bold">Start: {{$startDate}} <span class="text-dark">to</span> End: {{$endDate}}</span></h2>
                            @endif

                                <table class="table table-striped table-bordered mt-4">
                                    <thead>
                                        <tr class="text-center">
                                            <th style="width: 40px">SL</th>
                                            <th class="text-left">Supplier/Company Name</th>
                                            <th class="text-left">Address</th>
                                            <th class="text-left">Mobile</th>
                                            <th class="text-right">Weight</th>
                                            <th class="text-right">Monthly Bonus Tk</th>
                                            <th class="text-right">Yearly Bonus Tk</th>
                                            <th class="text-right">Cash Offer Tk</th>
                                            <th class="text-right">Total Tk</th>
                                            <th style="width: 60px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_weight = 0;
                                            $total_monthly_bonus = 0;
                                            $total_yearly_bonus = 0;
                                            $total_cash_offer = 0;
                                            $g_total = 0;
                                            $count = 0;
                                        @endphp
                                        @if (count($bonuses) > 0)
                                        @foreach ($bonuses as $supplier_id => $bonus)
                                            @php
                                                $count++;
                                                $weight = isset($bonus['yearly']->weight) ? $bonus['yearly']->weight : 0;
                                                $monthlyBonus = isset($bonus['monthly']->bonusAmount) ? $bonus['monthly']->bonusAmount : 0;
                                                $yearlyBonus = isset($bonus['yearly']->bonusAmount) ? $bonus['yearly']->bonusAmount : 0;
                                                $cashOffer = isset($bonus['cashOffer']->bonusAmount) ? $bonus['cashOffer']->bonusAmount : 0;

                                                $total = $monthlyBonus + $yearlyBonus + $cashOffer;

                                                $total_weight += $weight;
                                                $total_monthly_bonus += $monthlyBonus;
                                                $total_yearly_bonus += $yearlyBonus;
                                                $total_cash_offer += $cashOffer;
                                                $g_total += $total;


                                            @endphp

                                            <tr>
                                                <td>{{$count}}</td>
                                                <td class="text-left">{{ optional($all_suppliers->firstWhere('supplier_id', $supplier_id))->supplier->company_name ?? 'N/A' }}</td>
                                                <td class="text-left">{{ optional($all_suppliers->firstWhere('supplier_id', $supplier_id))->supplier->address ?? 'N/A' }}</td>
                                                <td class="text-left">{{ optional($all_suppliers->firstWhere('supplier_id', $supplier_id))->supplier->mobile ?? 'N/A' }}</td>
                                                <td class="text-right">{{$weight ? formatAmount($weight) : ''}}</td>
                                                <td class="text-right">{{$monthlyBonus ? formatAmount($monthlyBonus) . '/-' : ''}}</td>
                                                <td class="text-right">{{$yearlyBonus ? formatAmount($yearlyBonus) . '/-' : ''}}</td>
                                                <td class="text-right">{{$cashOffer ? formatAmount($cashOffer) . '/-' : ''}}</td>
                                                <td class="text-right">{{$total ? formatAmount($total) . '/-' : ''}}</td>
                                                <td>
                                                    {{-- <a class="btn btn-sm btn-success" href="{{route('monthly.bonus.index',['startdate' => $startDate,'enddate' => $endDate,'supplier_id' => $supplier_id])}}">Monthly</a> --}}
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2" data-toggle="dropdown"><i class="fa fa-eye"></i> <span class="caret"></span></button>
                                                        <ul class="dropdown-menu" role="menu">
                                                            <li><a href="{{route('monthly.bonus.index',['startdate' => $startDate,'enddate' => $endDate,'supplier_id' => $supplier_id])}}" class="btn btn-success btn-sm py-0" target="_blank">Monthly</a></li>
                                                            <li><a href="{{route('yearly.bonus.index',['startdate' => $startDate,'enddate' => $endDate,'supplier_id' => $supplier_id])}}" class="btn btn-primary btn-sm py-0" target="_blank">Yearly</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                        @endif
                                        <tr class="font-weight-bold">
                                            <td class="text-left" colspan="2">Total</td>
                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                            <td class="text-right">{{$total_weight}}</td>
                                            <td class="text-right">{{formatAmount($total_monthly_bonus)}}/=</td>
                                            <td class="text-right">{{formatAmount($total_yearly_bonus)}}/=</td>
                                            <td class="text-right">{{formatAmount($total_cash_offer)}}/=</td>
                                            <td class="text-right">{{formatAmount($g_total)}}/=</td>
                                            <td></td>
                                        </tr>
                                    </tbody>

                                </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
    jQuery(document).ready(function ($) {

           $('#due_supplier_search').select2({
                placeholder: 'Select supplier from here',
           });

           $('#due_supplier_search').on('change', function (e){
               //@this.searchSupplier(e.target.value);
                var data = $('#due_supplier_search').select2("val");
                // alert(data);
                @this.set('get_supplier_id', data);
            });


            $('#startdatepicker').datepicker( {
                format: "dd-mm-yyyy",
                autoclose: true,
            });
            $('#enddatepicker').datepicker( {
                format: "dd-mm-yyyy",
                autoclose: true,
            });
            $('#startdatepicker input[name=startDate]').on('change', function(e) {
                @this.set('startDate', e.target.value);
            });
            $('#enddatepicker input[name=endDate]').on('change', function(e) {
                @this.set('endDate', e.target.value);
            });
        });
    </script>
@endpush
