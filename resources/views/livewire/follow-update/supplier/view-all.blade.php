@section('page-title', 'Supplier Following Date List')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto">Supplier Following Date List </h2>
                <a href="{{route('supplier.follow.create')}}" class="btn btn-md btn-primary"> <i class="fa fa-plus" aria-hidden="true"></i> Add Supplier Following Date</a>
            </div>


        </div>
        <div class="x_content p-3">
            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="search">
                        <form action="{{route('supplier.follow.index')}}" method="get">
                            <input type="hidden" name="view" value="ledger" />
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                <div class="supplier-search-area">
                                    <div class="input-group date" id="start_date_picker">
                                        <input name="start_date" type="text" class="form-control" placeholder="dd-mm-yyyy" value="{{$start_date}}">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="supplier-search-area">
                                    <div class="input-group date" id="end_date_picker">
                                        <input name="end_date" type="text" class="form-control" placeholder="dd-mm-yyyy" value="{{$end_date}}">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>

                                </div>
                                <div class="supplier-search-area flex-grow-1">
                                    <div class="form-group m-0" wire:ignore>
                                        <select id="get_supplier_id" name="id" placeholder="search supplier" class="form-control">
                                            @if($supplier_id)
                                                <option value="{{$supplier_id}}" selected>{{$supplier_label}}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="supplier-search-button">
                                    <div class="form-group m-0">
                                        <button type="submit" class="btn btn-sm btn-success">Get</button>
                                        <a type="button" href="{{route('supplier.follow.index')}}" class="btn btn-sm btn-danger">Reset</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    <div class="card-box table-responsive">
                        <table id="" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <th class="all">SL</th>
                                <th class="text-left">Supplier Name</th>
                                <th class="text-left">Address</th>
                                <th class="all">Mobile</th>
                                <th class="all">Due Amount</th>
                                <th class="all">Payment Tk</th>
                                <th class="all">Prev Date</th>
                                <th class="all">Paying Date</th>
                                <th class="text-left">Remarks</th>
                                <th class="text-left">Status</th>
                                <th class="all">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $invoice)
                                    <tr>
                                        <td class="text-left">{{$loop->iteration}}</td>
                                        <td class="text-left">{{$invoice->supplier->company_name}}</td>
                                        <td class="text-left">{{$invoice->supplier->address}}</td>
                                        <td>{{$invoice->supplier->mobile}}</td>
                                        <td class="text-right">{{formatAmount($invoice->previous_due)}}/=</td>
                                        <td class="text-right">{{formatAmount($invoice->payment)}}/=</td>
                                        <td class="text-left">{{$invoice->prev_date ? date('d-m-Y',strtotime($invoice->prev_date)) : ''}}</td>
                                        <td class="text-left">{{date('d-m-Y',strtotime($invoice->next_date))}}</td>
                                        <td class="text-left text-wrap">{{$invoice->remarks}}</td>
                                        <td class="text-left text-nowrap">{{$invoice->payment_date ? date('d-m-Y',strtotime($invoice->payment_date)) . ' @': ''}}{{$invoice->paid_amount ? formatAmount($invoice->paid_amount) . '/-' : ''}}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle py-0 px-2"
                                                data-toggle="dropdown">
                                                <i class="fa fa-list"></i> <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a href="{{route('supplier.follow.edit',$invoice->id)}}" class="btn btn-success"><i class="fa fa-edit" ></i></a></li>
                                                    <li><a href="{{route('supplier.follow.delete',$invoice->id)}}" class="btn btn-danger" id="delete"><i class="fa fa-trash" ></i></a></li>
                                                    <li><a href="{{route('supplier.follow.view',$invoice->id)}}" class="btn btn-info"><i class="fa fa-eye" ></i></a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    var preloadedSuppliers = @json($preloadedSuppliers);
    $(document).ready(function() {

        $('#start_date_picker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });

        $('#end_date_picker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });
        $('#get_supplier_id').select2({
            placeholder: 'Select Supplier',
            ajax: {
                url: '{{ route('supplier.ajax.search') }}',
                data: function (params) {
                    return {
                        q: params.term, // search term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.data
                    };
                }
            }
        });
    });
</script>
@endpush
