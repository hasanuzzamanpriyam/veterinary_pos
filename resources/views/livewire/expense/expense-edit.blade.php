@section('page-title', 'Expense Add')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel p-3">
        <div class="x_title">
            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto">Add Expense</h2>
                <a href="{{route('expense.index')}}" class="btn btn-md btn-primary"><i class="fa fa-list" aria-hidden="true"></i> View All Expenses</a>
            </div>
        </div>
        <div class="x_content">

            <form wire:submit.prevent="update()" enctype="multipart/form-data" data-parsley-validate class="form-horizontal form-label-left" style="max-width: 500px; margin: 0 auto;">
                @csrf
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="list-group">
                        @foreach ($errors->all() as $error)
                            <li class="list-group-item list-group-item-danger text-center py-2">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="row m-auto">
                    <div class="col-12 gap-2">

                        <div class="row mb-2">
                            <div class="col-md-4 col-sm-12">
                                <label class="col-form-label label-align w-100 p-2" for="date">Date</label>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <div class="input-group date" id="expense_date_picker">
                                    <input wire:model="date" name="date" type="text" class="form-control" placeholder="dd-mm-yyyy" required>
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 col-sm-12">
                                <label class="col-form-label label-align w-100 p-2" for="expense_category_id">Expense Name</label>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <select name="expense_category_id" wire:model="expense_category_id" id="expense_category_id" class="form-control" required>
                                    <option value="">Select Option</option>
                                    @foreach($expense_categories as $expense_category)
                                        <option value="{{$expense_category->id}}">{{$expense_category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 col-sm-12">
                                <label class="col-form-label label-align w-100 p-2" for="note">Purpose/Note</label>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <input type="text" wire:model="note" id="note" class="form-control">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 col-sm-12">
                                <label class="col-form-label label-align w-100 p-2" for="amount">Amount</label>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <input type="text" wire:model="amount" id="amount" class="form-control text-right" required>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 col-sm-12">
                                <label class="col-form-label label-align w-100 p-2" for="paying_by">Paying By</label>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <select name="paying_by" wire:model="paying_by" id="paying_by" class="form-control" required>
                                    <option value="">Select Option</option>
                                    @foreach($payment_types as $payment_type)
                                        <option value="{{$payment_type}}">{{ucfirst($payment_type)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 col-sm-12">
                                <label class="col-form-label label-align w-100 p-2" for="remarks">Remarks</label>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <textarea name="remarks" wire:model="remarks" id="remarks" cols="30" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ln_solid"></div>
                <div class="item form-group">
                    <div class="col-md-12 col-sm-12 text-center">
                        <a href="{{route('expense.index')}}" class="btn btn-primary" type="button">Cancel</a>
                        <button class="btn btn-primary" type="reset">Reset</button>
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
    $('#expense_date_picker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true
    });

    $('#expense_date_picker input[name=date]').on('change', function(e) {
        @this.set('date', e.target.value);
    })
});
</script>
@endpush
