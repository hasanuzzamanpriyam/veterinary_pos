
@section('page-title', 'Update Supplier Following Date')

<div class="col-md-12 col-sm-12 d-flex flex-column justify-content-center ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Update Supplier Following Date</h2>
            </div>
        </div>
        <div class="x_content p-3">
            <form  wire:submit.prevent="updateSupplierFollowUpdate()" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                @csrf
                <div class="col-lg-12 col-md-12 col-sm-12">

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
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
                    <div class="row justify-content-center">
                        <div class="col-lg-12 col-md-12 col-sm-12">

                            <div class="row justify-content-center">
                                <div class="col-lg-12 col-md-12 col-sm-12 p-1">
                                    <div class="form-group d-flex align-items-center gap-2">
                                        <label class="rounded d-block border follow-lebel px-2 py-2 m-0" for="company_name">Supplier Name</label>
                                        <input type="text"  name="company_name" readonly id="company_name"  wire:model="company_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 p-1">
                                    <div class="form-group d-flex align-items-center gap-2">
                                        <label class="rounded d-block border follow-lebel px-2 py-2 m-0" for="address">Address</label>
                                        <input type="text"  name="address" readonly id="address" wire:model="address" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 p-1">
                                    <div class="form-group d-flex align-items-center gap-2">
                                        <label class="rounded d-block border follow-lebel px-2 py-2 m-0" for="mobile">Mobile</label>
                                        <input type="text"  name="mobile" readonly id="mobile" wire:model="mobile" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 p-1">
                                    <div class="form-group d-flex align-items-center gap-2">
                                        <label class="rounded d-block border follow-lebel px-2 py-2 m-0">Total Due</label>
                                        <input type="text"  name="previous_due" readonly wire:model="balance" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 p-1">
                                    <div class="form-group d-flex align-items-center gap-2 ">
                                        <label class="rounded d-block border follow-lebel px-2 py-2 m-0" class="" for="date">Paying Date</label>
                                        <div class="input-group date" id="datepicker33">
                                            <input name="date" type="text" id="date" class="form-control" wire:model="date" placeholder="dd-mm-yyyy">
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 p-1">
                                    <div class="form-group d-flex align-items-center gap-2">
                                        <label class="rounded d-block border follow-lebel px-2 py-2 m-0" for="payment">Payment</label>
                                        <input type="text" name="payment" id="payment" wire:model.lazy="payment" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 p-1">
                                    <div class="form-group d-flex align-items-center gap-2">
                                        <label class="rounded d-block border follow-lebel px-2 py-2 m-0" for="remarks">Remarks</label>
                                        <input type="text" name="remarks" id="remarks" wire:model.lazy="remarks" class="form-control">
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="item form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12  text-center">
                            <a href="{{route('supplier.follow.index')}}" class="btn btn-danger" type="button">Cancel</a>
                            <button type="submit" class="btn btn-success">Update</button>
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

        $('#datepicker33').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });

        $('#datepicker33').on('changeDate', function (e) {
            var date = e.format(0, "dd-mm-yyyy");
            @this.set('date', date);
        });

    });

    </script>

@endpush

