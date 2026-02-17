@section('page-title', 'Yearly Bonus Single')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2>Yearly Bonus Single</h2>
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
                    <div class="col-lg-12 col-md-12 col-sm-12 offset-md-2 offset-lg-2">
                        <div class="row">
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
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="supplier-search-area">
                                    {{-- <label for="supplier_search">Select Supplier:</label> --}}
                                    <div class="form-group" wire:ignore>
                                        <select type="search" id="due_supplier_search" name="get_supplier_id" placeholder="search supplier" class="form-control">
                                            <option value=""></option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{$supplier->id}}">
                                                    {{$supplier->company_name}} -
                                                    {{$supplier->address}} -
                                                    {{$supplier->mobile}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-1 col-md-1 col-sm-6">
                                <div class="supplier-search-button">
                                    <div class="form-group d-flex gap-2">
                                        <button type="submit" class="btn btn-success btn-sm">Get</button>
                                        <button type="button" class="btn btn-danger btn-sm" wire:click="resetSupplier">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="due-list-area mt-4" style="max-width: 600px; margin: 0 auto">
                            @if ($party)

                                <div class="x_title">
                                    <h3 class="text-center text-dark m-0">{{$party->company_name}}</h3>
                                    <p class="text-center text-dark p-0 m-0">{{$party->address}}, {{$party->mobile}}</p>
                                    <h4 class="text-center font-weight-bold text-dark">Yearly Bonus Calculation</h4>
                                </div>

                            @endif
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th style="width: 40px">SL</th>
                                            <th class="text-left">Date Range</th>
                                            <th class="text-right">Weight</th>
                                            <th class="text-right">Bonus Rate Tk</th>
                                            <th class="text-right">Bonus Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_weight = 0;
                                            $total_commission = 0;
                                        @endphp
                                        @foreach ($bonuses as $bonus)
                                            @php
                                                $total_weight += $bonus->weight;
                                                $total_commission +=$bonus->bonusAmount;
                                            @endphp
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td class="text-left">{{$startDate}} <strong>TO</strong> {{$endDate}}</td>
                                                <td class="text-right">{{$bonus->weight}}</td>
                                                <td class="text-right">{{formatAmount($bonus->rate)}}/=</td>
                                                <td class="text-right">{{formatAmount($bonus->bonusAmount)}}/=</td>
                                            </tr>
                                        @endforeach
                                        <tr class="font-weight-bold">
                                            <td class="text-left" colspan="2">Total</td>
                                            <td class="text-right">{{$total_weight}}</td>
                                            <td class="text-right"></td>
                                            <td class="text-right">{{formatAmount($total_commission)}}/=</td>
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
