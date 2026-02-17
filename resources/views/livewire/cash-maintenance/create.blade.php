@section('page-title', 'Cash Maintenance Add')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Cash Maintenance Add</h2>
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
            <form wire:submit.prevent="createSession()" enctype="multipart/form-data" id="demo-form2"
                data-parsley-validate class="form-horizontal form-label-left collection_from">
                @csrf
                <div class="row mb-4">
                    <div class="col-lg-12 col-md-12 col-sm-6">
                        <div class="row justify-content-end">
                            <div
                                class="search-area col-lg-12 col-md-12 col-sm-12 text-left purchase_return_entry_supplier_col">
                                <div wire:ignore class="row">
                                    <div class="col-md-3">
                                        <label class="py-1 border entry-lebel collection_entry_lebel"
                                            for="customer">Date</label>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="input-group date" id="datepicker33">
                                            <input name="date" wire:model="date" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <section class="collection">
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Collection info</h5>
                            <div class="responsive-table">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="width: 40px">SL</th>
                                            <th class="text-left" style="width: 30%">Customer Name</th>
                                            <th class="text-left" style="width: 40%">Address</th>
                                            <th style="width: 120px">Mobile</th>
                                            <th class="text-right" style="width: 90px">Amount Tk</th>
                                            <th style="width: 60px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($collection) > 0)
                                            @foreach ($collection as $item)
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td class="text-left">{{$item->customer->name}}</td>
                                                    <td class="text-left">{{$item->customer->address}}</td>
                                                    <td>{{$item->customer->mobile}}</td>
                                                    <td class="text-right">{{formatAmount($item->payment)}}/-</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-danger" type="button" wire:click="removeFromCollection({{$item->id}})"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif

                                        @foreach($rows as $index => $row)
                                            @if($row['type'] == 'collection_editable')
                                                <tr class="hidden">
                                                    <td></td>
                                                    <td><input type="text" wire:model="rows.{{ $index }}.name" placeholder="Customer Name" class="form-control form-control-sm"></td>
                                                    <td><input type="text" wire:model="rows.{{ $index }}.address" placeholder="Address" class="form-control form-control-sm"></td>
                                                    <td><input type="text" wire:model="rows.{{ $index }}.mobile" placeholder="Mobile" class="form-control form-control-sm text-center"></td>
                                                    <td><input type="text" wire:model="rows.{{ $index }}.amount" wire:keyup.debounce.500ms="addCustomAmountToSummary" placeholder="Amount" class="form-control form-control-sm text-right"></td>
                                                    <td><button class="btn btn-sm btn-danger" type="button" wire:click="removeRow({{ $index }})"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        <tr>
                                            <td colspan="6">
                                                <div class="d-flex align-items-center justify-content-center gap-2 py-3">
                                                    <button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#collectionModal" data-backdrop="static" @disabled(!$date)>Get</button>
                                                    <button class="btn btn-sm btn-success" type="button" wire:click="addRow('collection')" @disabled(!$date)>Add New Row</button>

                                                </div>
                                            </td>
                                        </tr>
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
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="width: 40px">SL</th>
                                            <th class="text-left" style="width: 30%">Supplier Name</th>
                                            <th class="text-left" style="width: 40%">Address</th>
                                            <th style="width: 120px">Mobile</th>
                                            <th class="text-right" style="width: 90px">Amount Tk</th>
                                            <th style="width: 60px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($payment) > 0)
                                            @foreach ($payment as $item)
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td class="text-left">{{$item->supplier->company_name}}</td>
                                                    <td class="text-left">{{$item->supplier->address}}</td>
                                                    <td>{{$item->supplier->mobile}}</td>
                                                    <td class="text-right">{{formatAmount($item->payment)}}/-</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-danger" type="button" wire:click="removeFromPayment({{$item->id}})"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        @foreach($rows as $index => $row)
                                            @if($row['type'] == 'payment_editable')
                                                <tr class="hidden">
                                                    <td></td>
                                                    <td><input type="text" wire:model="rows.{{ $index }}.name" placeholder="Supplier Name" class="form-control form-control-sm"></td>
                                                    <td><input type="text" wire:model="rows.{{ $index }}.address" placeholder="Address" class="form-control form-control-sm"></td>
                                                    <td><input type="text" wire:model="rows.{{ $index }}.mobile" placeholder="Mobile" class="form-control form-control-sm text-center"></td>
                                                    <td><input type="text" wire:model="rows.{{ $index }}.amount" wire:keyup.debounce.500ms="addCustomAmountToSummary" placeholder="Amount" class="form-control form-control-sm text-right"></td>
                                                    <td><button class="btn btn-sm btn-danger" type="button" wire:click="removeRow({{ $index }})"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        <tr>
                                            <td colspan="6">
                                                <div class="d-flex align-items-center justify-content-center gap-2 py-3">
                                                    <button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#paymentModal"  data-backdrop="static" @disabled(!$date)>Get</button>
                                                    <button class="btn btn-sm btn-success" type="button" wire:click="addRow('payment')" @disabled(!$date)>Add New Row</button>
                                                </div>
                                            </td>
                                        </tr>
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
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="width: 40px">SL</th>
                                            <th class="text-left" style="width: 30%">Expense Name</th>
                                            <th class="text-left" style="width: 40%">Purpose/Note</th>
                                            <th class="text-right" style="width: 90px">Amount Tk</th>
                                            <th style="width: 60px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($expense) > 0)
                                            @foreach ($expense as $item)
                                                @php
                                                    $title = $item->expense_type == 'salary_expense' ? 'Salary' : $item->expense_type;
                                                    $note = $item->expense_type == 'salary_expense' ? $item->employee->name : $item->purpose;
                                                    $amount = $item->amount + $item->other_charge;
                                                @endphp
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td class="text-left">{{$title}}</td>
                                                    <td class="text-left">{{$note}}</td>
                                                    <td class="text-right">{{formatAmount($amount)}}/-</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-danger" type="button" wire:click="removeFromExpense({{$item->id}})"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif

                                        @foreach($rows as $index => $row)
                                            @if($row['type'] == 'expense_editable')
                                                <tr class="hidden">
                                                    <td></td>
                                                    <td><input type="text" wire:model="rows.{{ $index }}.name" placeholder="Expense Title" class="form-control form-control-sm"></td>
                                                    <td><input type="text" wire:model="rows.{{ $index }}.note" placeholder="Purpose/Note" class="form-control form-control-sm"></td>
                                                    <td><input type="text" wire:model="rows.{{ $index }}.amount" wire:keyup.debounce.500ms="addCustomAmountToSummary" placeholder="Amount" class="form-control form-control-sm text-right"></td>
                                                    <td><button class="btn btn-sm btn-danger" type="button" wire:click="removeRow({{ $index }})"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        <tr>
                                            <td colspan="5">
                                                <div class="d-flex align-items-center justify-content-center gap-2 py-3">
                                                    <button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#expenseModal"  data-backdrop="static" @disabled(!$date)>Get</button>
                                                    <button class="btn btn-sm btn-success" type="button" wire:click="addRow('expense')" @disabled(!$date)>Add New Row</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </section>

                <section class="summary">
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Summary</h5>
                            <div class="responsive-table">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Dokan Cash (prev)</th>
                                            <th>Collection</th>
                                            <th>Payment</th>
                                            <th>Expense</th>
                                            <th>Home Cash</th>
                                            <th>Short Cash</th>
                                            <th>Dokan Cash</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{$previous_balance ? formatAmount($previous_balance) . '/-': '0'}}</td>
                                            <td>{{isset($total_summary_rows['collection']) ? formatAmount($total_summary_rows['collection']) . '/-' : '0'}}</td>
                                            <td>{{isset($total_summary_rows['payment']) ? formatAmount($total_summary_rows['payment']) . '/-' : '0'}}</td>
                                            <td>{{isset($total_summary_rows['expense']) ? formatAmount($total_summary_rows['expense']) . '/-' : '0'}}</td>
                                            <td>
                                                <div class="">
                                                    <input type="text" placeholder="0.00" name="home_cash" wire:keyup.debounce.500ms="addCashToCollection('home_cash', 'Home Cash', $event.target.value)" class="form-control form-control-sm text-right" />
                                                </div>
                                            </td>
                                            <td>
                                                <div class="">
                                                    <input type="text" placeholder="0.00" name="short_cash" wire:keyup.debounce.500ms="addCashToCollection('short_cash', 'Short Cash', $event.target.value)" class="form-control form-control-sm text-right" />
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $balance = floatval($previous_balance)
                                                    + floatval($total_summary_rows['collection'])
                                                    - floatval($total_summary_rows['payment'])
                                                    - floatval($total_summary_rows['expense'])
                                                    - floatval($total_summary_rows['home_cash'])
                                                    - floatval($total_summary_rows['short_cash']);
                                                @endphp
                                                {{formatAmount($balance)}}/-
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="ln_solid"></div>
                <div class="item form-group">
                    <div class="col-md-12 col-sm-12 text-center">
                        <a href="{{ route('cash_maintenance.index') }}" class="btn btn-danger" type="button">Cancel</a>
                        <button class="btn btn-warning " type="reset"
                            onClick="window.location.reload()">Reset</button>
                        <button type="submit" class="btn btn-success" @disabled(!$date)>Next</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <div class="all-modals">
        <!-- Modal -->
        <div class="modal fade" id="collectionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Collection List {{$date}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div class="responsive-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 40px">SL</th>
                                        <th style="width: 90px">Date</th>
                                        <th class="text-left" style="width: 30%">Customer Name</th>
                                        <th class="text-left" style="width: 40%">Address</th>
                                        <th style="width: 100px">Mobile</th>
                                        <th class="text-right" style="width: 90px">Amount Tk</th>
                                        <th style="width: 56px">Select</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($collection_data)
                                        @foreach ($collection_data as $item)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td class="text-left">{{date('d-m-Y', strtotime($item->date))}}</td>
                                                <td class="text-left">{{$item->customer->name}}</td>
                                                <td class="text-left">{{$item->customer->address}}</td>
                                                <td>{{$item->customer->mobile}}</td>
                                                <td class="text-right">{{formatAmount($item->payment)}}/-</td>
                                                <td>
                                                    <input
                                                        type="checkbox"
                                                        value="{{$item->id}}"
                                                        class="modal-checkbox form-control"
                                                        wire:model="selectedCollectionIds"
                                                    />
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>


                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Add to Page</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Payment List {{$date}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div class="responsive-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 40px">SL</th>
                                        <th style="width: 90px">Date</th>
                                        <th class="text-left" style="width: 30%">Supplier Name</th>
                                        <th class="text-left" style="width: 40%">Address</th>
                                        <th style="width: 100px">Mobile</th>
                                        <th class="text-right" style="width: 90px">Amount Tk</th>
                                        <th style="width: 56px">Select</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($payment_data)
                                        @foreach ($payment_data as $item)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td class="text-left">{{date('d-m-Y', strtotime($item->date))}}</td>
                                                <td class="text-left">{{$item->supplier->company_name}}</td>
                                                <td class="text-left">{{$item->supplier->address}}</td>
                                                <td>{{$item->supplier->mobile}}</td>
                                                <td class="text-right">{{formatAmount($item->payment)}}/-</td>
                                                <td>
                                                    <input
                                                        type="checkbox"
                                                        value="{{$item->id}}"
                                                        class="modal-checkbox form-control"
                                                        wire:model="selectedPaymentIds"
                                                    />
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>


                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Add to Page</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="expenseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Expense List {{$date}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div class="responsive-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 40px">SL</th>
                                        <th style="width: 90px">Date</th>
                                        <th class="text-left" style="width: 30%">Expense Name</th>
                                        <th class="text-left" style="width: 40%">Purpose/Note</th>
                                        <th class="text-right" style="width: 90px">Amount Tk</th>
                                        <th style="width: 56px">Select</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($expense_data)
                                        @foreach ($expense_data as $item)
                                            @php
                                                $title = $item->expense_type == 'salary_expense' ? 'Salary' : $item->expense_type;
                                                $note = $item->expense_type == 'salary_expense' ? $item->employee->name : $item->purpose;
                                                $amount = $item->amount + $item->other_charge;
                                            @endphp
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td class="text-left">{{date('d-m-Y', strtotime($item->date))}}</td>
                                                <td class="text-left">{{$title}}</td>
                                                <td class="text-left">{{$note}}</td>
                                                <td class="text-right">{{formatAmount($amount)}}/-</td>
                                                <td>
                                                    <input
                                                        type="checkbox"
                                                        value="{{$item->id}}"
                                                        class="modal-checkbox form-control"
                                                        wire:model="selectedExpenseIds"
                                                    />
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>


                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Add to Page</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
    $(document).ready(function() {

        $('#datepicker33').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });

        $('#datepicker33 input[name=date]').on('change', function(e) {
            @this.set('date', e.target.value);
        });

    });
</script>
<script>
    $(document).ready(function() {
        $('#collectionModal').on('hidden.bs.modal', function () {
            var selectedCollectionIds = [];
            $('#collectionModal .modal-checkbox:checked').each(function() {
                selectedCollectionIds.push($(this).val());
            });
            Livewire.dispatch('update-collections', { selectedIds: selectedCollectionIds });
        });
        $('#paymentModal').on('hidden.bs.modal', function () {
            var selectedPaymentIds = [];
            $('#paymentModal .modal-checkbox:checked').each(function() {
                selectedPaymentIds.push($(this).val());
            });
            Livewire.dispatch('update-payments', { selectedIds: selectedPaymentIds });
        });
        $('#expenseModal').on('hidden.bs.modal', function () {
            var selectedExpenseIds = [];
            $('#expenseModal .modal-checkbox:checked').each(function() {
                selectedExpenseIds.push($(this).val());
            });
            Livewire.dispatch('update-expenses', { selectedIds: selectedExpenseIds });
        });
    });

    window.addEventListener('cash-already-exists', event => {
        Swal.fire({
            icon: 'warning',
            title: 'Already Exists',
            text: 'This date already exists! Select another day!',
        });
    });
</script>
@endpush
@push('styles')
<style>
    .table {
        table-layout: fixed;
    }
</style>
@endpush
