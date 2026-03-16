@section('page-title', 'Supplier Checkout')

<div class="col-md-12 col-sm-12">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_title p-3">
                    <div class="header-title d-flex align-items-center">
                        <h2>Supplier Checkout</h2>
                        <div class="ms-auto">
    <a href="{{ route('live.supplier.create') }}" class="cursor-pointer me-2" title="Back to Edit">
        <i class="fa fa-arrow-left"></i>
    </a>
    <a href="{{ route('supplier.index') }}" class="cursor-pointer" title="Close">
        <i class="fa fa-close"></i>
    </a>
</div>
                    </div>
                </div>

                <div class="x_content p-3">
                    {{-- No error summary needed on checkout --}}

                    {{-- Supplier information displayed in two columns like the add form --}}
                    <div class="row">
                        {{-- LEFT COLUMN --}}
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            {{-- Photo --}}
                            <div class="item form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel">Photo</label>
                                <div class="col-md-8 col-sm-8">
                                    @if(empty($supplier['photo']))
                                        <p class="form-control-plaintext">No Image Found!</p>
                                    @else
                                        <img src="{{ asset($supplier['photo']) }}" class="img-thumbnail img-responsive" alt="Logo" width="250" height="320">
                                    @endif
                                </div>
                            </div>

                            {{-- Company Name --}}
                            <div class="item form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel">Company Name</label>
                                <div class="col-md-8 col-sm-8">
                                    <p class="form-control-plaintext">{{ $supplier['company_name'] ?? '' }}</p>
                                </div>
                            </div>

                            {{-- Owner Name --}}
                            <div class="item form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel">Owner Name</label>
                                <div class="col-md-8 col-sm-8">
                                    <p class="form-control-plaintext">{{ $supplier['owner_name'] ?? '' }}</p>
                                </div>
                            </div>

                            {{-- Officer Name --}}
                            <div class="item form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel">Officer Name</label>
                                <div class="col-md-8 col-sm-8">
                                    <p class="form-control-plaintext">{{ $supplier['officer_name'] ?? '' }}</p>
                                </div>
                            </div>

                            {{-- Address --}}
                            <div class="item form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel">Address</label>
                                <div class="col-md-8 col-sm-8">
                                    <p class="form-control-plaintext">{{ $supplier['address'] ?? '' }}</p>
                                </div>
                            </div>

                            {{-- Mobile Number --}}
                            <div class="item form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel">Mobile Number</label>
                                <div class="col-md-8 col-sm-8">
                                    <p class="form-control-plaintext">{{ $supplier['mobile'] ?? '' }}</p>
                                </div>
                            </div>

                            {{-- Officer Number (phone) --}}
                            <div class="item form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel">Officer Number</label>
                                <div class="col-md-8 col-sm-8">
                                    <p class="form-control-plaintext">{{ $supplier['phone'] ?? '' }}</p>
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="item form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel">Email</label>
                                <div class="col-md-8 col-sm-8">
                                    <p class="form-control-plaintext">{{ $supplier['email'] ?? '' }}</p>
                                </div>
                            </div>

                            {{-- Ledger Page --}}
                            <div class="item form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel">Ledger Page</label>
                                <div class="col-md-8 col-sm-8">
                                    <p class="form-control-plaintext">{{ $supplier['ledger_page'] ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT COLUMN --}}
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            {{-- Dealer Code --}}
                            <div class="item form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel">Dealer Code</label>
                                <div class="col-md-8 col-sm-8">
                                    <p class="form-control-plaintext">{{ $supplier['dealer_code'] ?? '' }}</p>
                                </div>
                            </div>

                            {{-- Dealer Area --}}
                            <div class="item form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel">Dealer Area</label>
                                <div class="col-md-8 col-sm-8">
                                    <p class="form-control-plaintext">{{ $supplier['dealer_area'] ?? '' }}</p>
                                </div>
                            </div>

                            {{-- Security --}}
                            <div class="item form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel">Security</label>
                                <div class="col-md-8 col-sm-8">
                                    <p class="form-control-plaintext">{{ $supplier['security'] ?? '' }}</p>
                                </div>
                            </div>

                            {{-- Credit Limit --}}
                            <div class="item form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel">Credit Limit</label>
                                <div class="col-md-8 col-sm-8">
                                    <p class="form-control-plaintext">{{ $supplier['credit_limit'] ?? '' }}</p>
                                </div>
                            </div>

                            {{-- Advance Payment --}}
                            <div class="item form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel">Adv. Payment</label>
                                <div class="col-md-8 col-sm-8">
                                    <p class="form-control-plaintext">{{ $supplier['advance_payment'] ?? '' }}</p>
                                </div>
                            </div>

                            {{-- Previous Due --}}
                            <div class="item form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel">Previous Due</label>
                                <div class="col-md-8 col-sm-8">
                                    <p class="form-control-plaintext">{{ $supplier['previous_due'] ?? '' }}</p>
                                </div>
                            </div>

                            {{-- Condition --}}
                            <div class="item form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel">Condition</label>
                                <div class="col-md-8 col-sm-8">
                                    <p class="form-control-plaintext">{{ $supplier['condition'] ?? '' }}</p>
                                </div>
                            </div>

                            {{-- Starting Date --}}
                            <div class="item form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align add_supplier_lebel">Starting Date</label>
                                <div class="col-md-8 col-sm-8">
                                    <p class="form-control-plaintext">{{ $supplier['starting_date'] ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ln_solid"></div>

                    {{-- Action buttons (same style as add form) --}}
                    <div class="item form-group">
                        <div class="col-md-12 col-sm-12 text-center">
                            <a href="{{ route('supplier.index') }}" class="btn btn-danger" type="button">Cancel</a>
                            <button class="btn btn-warning" type="reset" wire:click="clear">Reset</button>
                            <button type="submit" class="btn btn-success" wire:click="submit()">Submit</button>
                        </div>
                    </div>
                </div> {{-- end x_content --}}
            </div> {{-- end x_panel --}}
        </div>
    </div>
</div>