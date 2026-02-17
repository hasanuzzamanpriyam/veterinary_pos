@section('page-title', 'Cash Offer List')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto">Cash Offer List</h2>
                <a href="{{route('cash.offer.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Cash Offer</a>
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
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="due-list-area mt-4">
                            <h3 class="text-center font-weight-bold text-dark m-0">Cash Offer List</h3>
                            @if ($startDate && $endDate)
                            <h2 class="text-center"><span class="text-danger font-weight-bold">Start: {{$startDate}} <span class="text-dark">to</span> End: {{$endDate}}</span></h2>
                            @endif
                            {{cute_loader()}}

                            <div class="table-header d-flex align-items-center justify-content-between mt-4">
                                <div class="per-page">
                                    <div class="form-group">
                                        <select id="perpage" class="form-control form-control-sm" wire:model.live="perPage">
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="all">All</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="ajax-search d-flex align-items-center gap-2">

                                    <div class="form-group">
                                        <div class="input-group date" id="startdatepicker">
                                            <input name="startDate" wire:model="startDate" type="text" class="form-control form-control-sm" placeholder="dd-mm-yyyy">
                                            <div class="input-group-addon py-half">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group date" id="enddatepicker">
                                            <input name="endDate" wire:model="endDate" type="text" class="form-control form-control-sm" placeholder="dd-mm-yyyy">
                                            <div class="input-group-addon py-half">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" wire:model="search_query" class="form-control form-control-sm" style="min-width: 250px" placeholder="Type Supplier Name"/>
                                    </div>

                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary btn-sm" wire:click="supplierOfferSearch" style="min-width: 100px">Get</button>
                                        <button type="reset" class="btn btn-warning btn-sm" wire:click="resetData" style="min-width: 100px">Reset</button>
                                    </div>
                                </div>
                            </div>

                            <div class="card-box table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th style="width: 40px">SL</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-left">Supplier Name</th>
                                            <th class="text-left">Address</th>
                                            <th class="text-left">Mobile</th>
                                            <th class="text-left">Description</th>
                                            <th class="text-right">Amount</th>
                                            <th style="width: 60px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $count = 0;
                                            $totalAmount = 0;
                                        @endphp
                                        @if (count($offers) > 0)
                                        @foreach ($offers as $offer)
                                            @php
                                                $count++;
                                                $amount = $offer->amount ?? 0;
                                                $totalAmount += $amount;
                                            @endphp
                                            <tr>
                                                <td>{{$count}}</td>
                                                <td class="text-center">{{date('d-m-Y',strtotime($offer->date))}}</td>
                                                <td class="text-left">{{$offer->supplier->company_name}}</td>
                                                <td class="text-left">{{$offer->supplier->address}}</td>
                                                <td class="text-left">{{$offer->supplier->mobile}}</td>
                                                <td class="text-left">{{$offer->description}}</td>
                                                <td class="text-right">{{$amount ? formatAmount($amount) . '/-' : '-'}}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <a href="{{route('cash.offer.edit', $offer->id)}}"><i class="fa fa-edit text-primary" style="font-size:18px"></i></a>
                                                        <button class="btn btn-sm btn-link p-0" type="button" wire:click="delete({{$offer->id}})"><i class="fa fa-trash text-danger" style="font-size:18px"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                        @endif
                                        <tr class="font-weight-bold">
                                            <td class="text-left" colspan="2">Total</td>
                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                            <td class="text-right">{{formatAmount($totalAmount)}}/=</td>
                                            <td></td>
                                        </tr>
                                    </tbody>

                                </table>
                                @if (method_exists($offers, 'links'))
                                    <div class="mt-4 w-100">
                                        {{ $offers->links() }}
                                    </div>
                                @endif
                            </div>
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
