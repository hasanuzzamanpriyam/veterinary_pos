@extends('layouts.admin')

@section('page-title')
    Collection List
@endsection

@section('main-content')
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">

                <div class="header-title d-flex align-items-center gap-2 p-3">
                    <h2 class="mr-auto">Collection List</h2>
                    <a href="{{ route('collection.create') }}" class="btn btn-md btn-primary"><i class="fa fa-plus"
                            aria-hidden="true"></i> Add Collection</a>
                </div>


            </div>
            <div class="x_content p-3">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 ">
                        <div class="card-box table-responsive">
                            <table id=""
                                class="table table-striped table-bordered dt-responsive nowrap"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="all">Date</th>
                                        <th class="all">Invoice</th>
                                        <th class="all">Customer Name</th>
                                        <th class="all">Address</th>
                                        <th class="all">Mobile</th>
                                        <th class="all">Received By</th>
                                        <th class="all">Remarks</th>
                                        <th class="all">Amount</th>
                                        <th class="all">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php
                                        $total = 0;
                                    @endphp
                                    @foreach ($collections_list as $customer)
                                        @php
                                            $total += $customer->payment;
                                        @endphp
                                        <tr>
                                            <td class="text-left">{{ date('d-m-Y', strtotime($customer->date)) }}</td>
                                            <td class="text-left">{{ $customer->id }} ({{$customer->type}})</td>
                                            <td class="text-left">{{ $customer->customer->name }}</td>
                                            <td>{{ $customer->customer->address }}</td>
                                            <td>{{ $customer->customer->mobile }}</td>
                                            <td>{{ $customer->payment_by }}{{ $customer->bank_title }}</td>
                                            <td class="text-left">{{ $customer->received_by }}</td>
                                            <td class="text-right">{{ $customer->payment }}/=</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                                        data-toggle="dropdown">
                                                        Action <span class="caret"></span></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="{{ route('sales.delete', ['invoice' => $customer->id, 'view' => 'collection']) }}"
                                                                class="btn btn-danger" id="delete"><i
                                                                    class="fa fa-trash"></i></a></li>
                                                        <li><a href="{{ route($customer->type === 'sale' ? 'sales.view' : 'collection.view', $customer->id) }}"
                                                                class="btn btn-info"><i class="fa fa-eye"></i></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tr class="text-right">
                                    <td colspan="9" class="payment_list_tb_total">Total = {{ $total }}/=</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            if ($.fn.dataTable.isDataTable('#datatable-responsive')) {
                $('#datatable-responsive').DataTable().destroy();
            }

            $('#datatable-responsive').DataTable({
                order: [
                    [1, "asc"]
                ],
            });
        });
    </script>
@endpush
