@section('page-title', 'Monthly Bonus All')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2>Monthly Bonus All</h2>
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
                                        <button type="submit" class="btn btn-success btn-sm">Search</button>
                                        <button type="button" wire:click="searchReset" class="btn btn-danger btn-sm">Reset</button>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-lg-1 col-md-1 col-sm-6">
                                <div class="supplier-search-button">
                                    <div class="form-group">
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="due-list-area mt-4">
                            <h3 class="text-center font-weight-bold text-dark m-0">Monthly Bonus Calculation</h3>
                            @if ($startDate && $endDate)
                            <h2 class="text-center"><span class="text-danger font-weight-bold">Start: {{$startDate}} <span class="text-dark">to</span> End: {{$endDate}}</span></h2>
                            @endif

                                <table class="table table-striped table-bordered mt-4">
                                    <thead>
                                        <tr class="text-center">
                                            <th style="width: 40px">SL</th>
                                            <th class="text-left">Supplier Name</th>
                                            <th class="text-left">Address</th>
                                            <th class="text-left">Mobile</th>
                                            <th class="text-right">Weight</th>
                                            <th class="text-right">Bonus Amount</th>
                                            <th style="width: 60px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_weight = 0;
                                            $total_commission = 0;
                                            $count = 0;
                                        @endphp
                                        @if (count($bonuses) > 0)
                                        @foreach ($bonuses as $supplier_id => $bonus)
                                            @php
                                            // dump($bonus);
                                                if($bonus['bonusAmount'] == 0){
                                                    continue;
                                                }
                                                $count++;
                                                $total_weight += $bonus['weight'];
                                                $total_commission +=$bonus['bonusAmount'];

                                            @endphp
                                            <tr>
                                                <td>{{$count}}</td>
                                                <td class="text-left">{{$suppliers->find($supplier_id)->company_name}}</td>
                                                <td class="text-left">{{$suppliers->find($supplier_id)->address}}</td>
                                                <td class="text-left">{{$suppliers->find($supplier_id)->mobile}}</td>
                                                <td class="text-right">{{$bonus['weight']}}</td>
                                                <td class="text-right">{{$bonus['bonusAmount']}}/=</td>
                                                <td>
                                                    <a href="{{route('monthly.bonus.index',['startdate' => $startDate,'enddate' => $endDate,'supplier_id' => $supplier_id])}}">
                                                        <i class="fa fa-eye text-success" style="font-size:24px"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach

                                        @endif
                                        <tr class="font-weight-bold">
                                            <td class="text-left" colspan="2">Total</td>
                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                            <td class="text-right">{{$total_weight}}</td>
                                            <td class="text-right">{{$total_commission}} /=</td>
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
