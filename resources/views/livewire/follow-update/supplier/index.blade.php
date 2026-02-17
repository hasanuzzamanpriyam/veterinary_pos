@section('page-title', 'Supplier Follow Update Add')
<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2>Add Supplier Following Date</h2>
                <a href="#" class="mr-auto ml-3 cursor-pointer" onclick="history.back()"><i class="fa fa-close"></i></a>
            </div>
        </div>
        <div class="x_content p-3">
            <form wire:submit.prevent="storeSupplierFollowUpdate()"  enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                @csrf
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 p-1">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="list-group">
                                        @foreach ($errors->all() as $error)
                                            <li class="list-group-item list-group-item-danger">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 p-1">
                            <div class="form-group" wire:ignore>
                                <select type="search"  name="supplier_search" id="supplier_search" placeholder="search" class="form-control">
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{$supplier->id}}">
                                            {{$supplier->company_name}} -
                                            {{$supplier->address}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-lg-12 col-md-12 col-sm-12 p-1">
                            <div class="form-group d-flex align-items-center gap-2">
                                <label class="rounded d-block border follow-lebel px-2 py-2 m-0" for="company_name">Supplier Name</label>
                                <input type="text" name="company_name" readonly wire:model="company_name" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 p-1">
                            <div class="form-group d-flex align-items-center gap-2">
                                <label class="rounded d-block border follow-lebel px-2 py-2 m-0" for="supplier_name">Address</label>
                                <input type="text" name="address" readonly wire:model="address" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 p-1">
                            <div class="form-group d-flex align-items-center gap-2">
                                <label class="rounded d-block border follow-lebel px-2 py-2 m-0" for="supplier_name">Mobile</label>
                                <input type="text" name="mobile" readonly wire:model="mobile" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 p-1">
                            <div class="form-group d-flex align-items-center gap-2">
                                <label class="rounded d-block border follow-lebel px-2 py-2 m-0" for="supplier_name">Total Due</label>
                                <input type="text" name="previous_due" readonly wire:model="balance" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 p-1">
                            <div class="form-group d-flex align-items-center gap-2 ">
                                <label class="rounded d-block border follow-lebel px-2 py-2 m-0" class="" for="date">Paying Date</label>
                                <div class="input-group date" id="datepicker33">
                                    <input name="date" type="text" class="form-control" wire:model="date" placeholder="dd-mm-yyyy">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 p-1">
                            <div class="form-group d-flex align-items-center gap-2">
                                <label class="rounded d-block border follow-lebel px-2 py-2 m-0" for="prepare">Payment</label>
                                <input type="text" name="payment" wire:model.lazy="payment" id="payment" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 p-1">
                            <div class="form-group d-flex align-items-center gap-2">
                                <label class="rounded d-block border follow-lebel px-2 py-2 m-0" for="remarks">Remarks</label>
                                <input type="text" name="remarks" wire:model.lazy="remarks" id="remarks" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="item form-group text-center">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <a href="{{route('supplier.follow.index')}}" class="btn btn-danger" type="button">Cancel</a>
                            <button class="btn btn-warning" type="reset">Reset</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@push('scripts')
<script>
    $(document).ready(function () {
        $('#supplier_search').select2();
        $('#supplier_search').on('change', function (e) {
            @this.searchSupplier(e.target.value);
        });


        $('#datepicker33').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });

        $('#datepicker33 input[name=date]').on('change', function (e) {
            // console.log(e.target.value)
            @this.set('date', e.target.value);

        });
    });
</script>
@endpush
