@section('page-title', 'Bank '. $view .' update' )
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title px-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Update {{ucfirst($view)}}</h2>
                <a href="" class="mr-auto ml-3 cursor-pointer"><i class="fa fa-close"></i></a>
            </div>
        </div>
        <div class="x_content px-3">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="list-group">
                        @foreach ($errors->all() as $error)
                            <li class="list-group-item list-group-item-danger text-center py-2">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form wire:submit.prevent="store" enctype="multipart/form-data" data-parsley-validate class="form-horizontal form-label-left collection_from fz-form fz-form-style-2">
                @csrf
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-6">
                        <div class="row justify-content-end">
                            <div class="search-area col-lg-12 col-md-12 col-sm-12 text-left purchase_return_entry_supplier_col">
                                <div wire:ignore class="row">
                                    <div class="col-md-3">
                                        <label class="py-1 border entry-lebel collection_entry_lebel" for="customer">Bank</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="d-block py-1 border" class="form-control" disabled>
                                            <option value="">Select Bank</option>
                                            @foreach ($banks as $bank )
                                                <option value="{{ $bank->id }}" @selected($bank->id == $bank_id)>
                                                    {{ $bank->name }} -
                                                    {{ $bank->branch }} -
                                                    {{ $bank->account_no }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row collection-form-area d-flex justify-content-center">
                    <div class="col-lg-3 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label class="border py-1 collection_entry_lebel" for="supplier_name">Bank Name</label>
                            <input type="text" value="{{ $bank_info->name ?? '' }}" class="form-control" required readonly>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label class="border py-1 collection_entry_lebel" for="Branch Name">Branch Name</label>
                            <input type="text" value="{{ $bank_info->branch ?? '' }}" class="form-control" required readonly>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label class="border py-1 collection_entry_lebel" for="supplier_name">Account No</label>
                            <input type="text" value="{{ $bank_info->account_no ?? '' }}" class="form-control" required readonly>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label class="border py-1 collection_entry_lebel" for="supplier_name">Prev Balance</label>
                            <input type="text" value="{{ formatAmount($prev_balance ?? 0) }}" class="form-control text-right" required readonly>
                        </div>
                    </div>
                </div>
                <div class="row collection-form-area d-flex justify-content-center">
                    <div class="col-lg-3 col-md-2 col-sm-12">
                        <div class="form-group ">
                            <label class="border py-1 collection_entry_lebel" for="date">Date</label>
                            <div class="input-group date" id="datepicker">
                                <input name="date" wire:model="date" type="text" class="form-control" placeholder="dd-mm-yyyy" required>
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label  class="border py-1 collection_entry_lebel" for="prepare">Deposit Methods</label>
                            <select type="text" name="payment_by" wire:model="payment_by" wire:change="handleChange('payment_by', $event.target.value)"  class="form-control" required>
                                <option value="">Select Option</option>
                                @foreach($payment_types as $payment_type)
                                    <option value="{{strtolower($payment_type)}}">{{$payment_type}}</option>
                                @endforeach
                            </select>

                            @if (in_array($payment_by, $list_of_banking_service))
                                <select type="text" name="account_title" wire:model="payment_by_bank" wire:change="handleChange('payment_by_bank', $event.target.value)" class="form-control">
                                    <option value="">Select Account Title</option>
                                    @foreach($banks as $bank)
                                        <option value="{{$bank->title}}">{{$bank->title}}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label  class="border py-1 collection_entry_lebel" for="prepare">Remarks</label>
                            <input type="text" name="remarks" wire:model="remarks"  id="remark" class="form-control">
                        </div>

                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label  class="border py-1 collection_entry_lebel" for="prepare">Amount</label>
                            <input type="text" value={{formatAmount($amount)}} wire:keyup="amoutCalculation($event.target.value)" class="form-control text-right" required>
                        </div>
                    </div>
                </div>
                <div class="row collection-form-area d-flex justify-content-center">
                    <div class="col-lg-3 col-md-2 col-sm-12"></div>
                    <div class="col-lg-4 col-md-2 col-sm-12"></div>
                    <div class="col-lg-3 col-md-2 col-sm-12"></div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label  for="prepare" class="collection_entry_lebel py-1">Balance</label>
                            <input type="text" name="total_balance" readonly value="{{formatAmount($total)}}" id="current_due" class="form-control text-right">
                        </div>
                    </div>
                </div>
                <div class="item form-group">
                    <div class="col-md-12 col-sm-12 text-center">
                        <button class="btn btn-danger" type="reset" onClick="window.location.reload()">Reset</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function(){
    $('#datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true
    });

    $('#datepicker input[name=date]').on('change', function(e) {
        @this.set('date', e.target.value);
    })
});
</script>
@endpush

