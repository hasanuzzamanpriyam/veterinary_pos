@section('page-title', '---')
<div class="col-lg-12 col-md-12 col-sm-12 ">
<div class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2>Customer Ledger</h2>
                <ul class="nav navbar-right panel_toolbox">

                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a class="dropdown-item" href="#">Settings 1</a>
                            </li>
                            <li><a class="dropdown-item" href="#">Settings 2</a>
                            </li>
                        </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card-box table-responsive">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr class="text-center">
                                        <th class="all">Date</th>
                                        <th class="all">Invoice No.</th>
                                        <th class="all">Paying by</th>
                                        <th class="all">Remarks</th>
                                        <th class="all">Product Quantity</th>
                                        <th class="all">Sales Amount</th>
                                        <th class="all">Discount</th>
                                        <th class="all">Vat.</th>
                                        <th class="all">Carring</th>
                                        <th class="all">Others</th>
                                        <th class="all">Total Amount</th>
                                        <th class="all">Payment</th>
                                        <th class="all">Balance</th>
                                        <th class="all">Due Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @php
                                        $total_qty = 0;
                                        $total_sales = 0;
                                        $total_discount = 0;
                                        $total_vat = 0;
                                        $total_carring = 0;
                                        $total_others = 0;
                                        $total_amount = 0;
                                    @endphp --}}
                                    @foreach ($collections as $collection)
                                    {{-- @php
                                        $total_qty +=$collection->total_qty;
                                        $total_sales += $collection->total_price;
                                        $total_discount += $collection->price_discount;
                                        $total_vat += $collection->vat;
                                        $total_carring +=$collection->carring;
                                        $total_others += $collection->other_charge;
                                        $total_amount += $collection->total_price -$collection->price_discount+$collection->vat+$collection->carring+$collection->other_charge;
                                    @endphp --}}
                                    <tr>
                                        <td class="">{{date('d-m-Y', strtotime($collection->date))}}</td>
                                        <td class="text-center">{{$collection->invoice_no}}</td>
                                        <td class="text-center">{{$collection->payment_by}} : {{$collection->bank_title}}</td>
                                        <td class="text-wrap">{{$collection->remarks}}</td>
                                        <td class="text-center">{{$collection->total_qty ?? ''}}</td>
                                        <td class="text-right">{{$collection->total_price ??'' }}/=</td>
                                        <td class="text-right">{{$collection->price_discount ?? ''}}/=</td>
                                        <td class="text-right">{{$collection->vat ??'' }}/=</td>
                                        <td class="text-right">{{$collection->carring ?? ''}}/=</td>
                                        <td class="text-right">{{$collection->other_charge ?? ''}}/=</td>
                                        <td class="text-right">{{$collection->total_price ?? '' }}/=</td>
                                        <td class="text-right">{{$collection->paid_amount ?? '' }}/=</td>
                                        <td class="text-right">{{$collection->previous_due ?? '' }}/=</td>
                                        <td class="text-right">{{$collection->current_due ?? -$collection->current_advance}}/=</td>

                                    </tr>
                                    @endforeach
                                </tbody>
                                {{-- <tr>
                                    <td colspan="5" class="text-right">Total= {{$total_qty}} {{$type}}</td>
                                    <td colspan="1"  class="p-0">Total= {{$total_sales}}/=</td>
                                    <td colspan="1"  class="p-0">Total= {{$total_discount}}/=</td>
                                    <td colspan="1"  class="p-0">Total= {{$total_vat}}/=</td>
                                    <td colspan="1"  class="p-0">Total= {{$total_carring}}/=</td>
                                    <td colspan="1"  class="p-0">Total= {{$total_others}}/=</td>
                                    <td colspan="1"  class="p-0">Total= {{$total_amount}}/=</td>
                                </tr> --}}
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
    $(document).ready(function () {

           $('#get_customer_id').select2({
            placeholder: 'Select Customer from here',
           });

           $('#get_customer_id').on('change', function (e){
               @this.getCustomer(e.target.value);
                // var data = $('#get_customer_id').select2("val");
                // @this.set('get_customer_id', data);
            });
    });

    </script>

@endpush
