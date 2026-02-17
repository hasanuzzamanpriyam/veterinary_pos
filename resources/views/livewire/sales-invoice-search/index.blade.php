
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Sales Invoice Search</h2>
                <a href="#" class="mr-auto ml-3 cursor-pointer" onclick="history.back()"><i class="fa fa-close"></i></a>
                {{-- @if($start_date && $end_date && $customer_info)
                    <a target="_blank" href="{{ route('sales.customer.report.pdf',[$start_date, $end_date, $customer_info->id ?? 0]) }}"  class="btn btn-primary btn-sm p-2"> Download <i class="fa fa-file-pdf-o text-white"></i></a>
                @elseif($start_date && $end_date)
                    <a  target="_blank" href="{{ route('sales.all.report.pdf',[$start_date, $end_date]) }}" class="btn btn-primary btn-sm p-2"> Download <i class="fa fa-file-pdf-o text-white"></i></a>
                @endif --}}
            </div>
        </div>

        <div class="x_content p-3">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <form wire:submit.prevent=salesInvoiceSearch()>
                        <div class="row justify-content-center">
                            <div class="col-lg-2 col-md-2 col-sm-12">
                                <div class="sales-invoices-search-area">
                                    <label  class="py-1 border" for="sales_invoices_search">Sales Invoice No</label>
                                    <div class="form-group">
                                    <input type="number" name="sales_invoices_no" wire:model="sales_invoices_no" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12">

                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="supplier-search-button pt-4">
                                    <div class="form-group pt-3">
                                        <button type="submit" class="btn btn-success">Search</button>
                                        <button type="button" wire:click="resetSalesInvoiceNo" class="btn btn-danger">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                            {{-- invoice section goes here --}}
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

           $('#get_customer_id, input[name="start_date"], input[name="end_date"]' ).on('change', function (e){
                @this.getCustomer(e.target.name, e.target.value);

            });

            $('table.category_list_table').DataTable();

            $(document).on('dataUpdated', function () {
                const timeout = setTimeout(() => {
                    $('table.category_list_table').DataTable({
                        // Your DataTable configuration here
                        "lengthMenu": [
                            [10, 25, 50, 100, -1],
                            [10, 25, 50, 100, "All"]
                        ]
                    });
                    clearTimeout(timeout);
                }, 10);
            })
    });
    </script>

@endpush
