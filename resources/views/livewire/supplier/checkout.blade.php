@section('page-title', 'Supplier Checkout')

<div class="container">
    <div class="customer-area">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <h5 class="text-center single_p_title">Supplier Create</h5>

                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12">
                        <div class="back_button mb-2">
                            <a href="{{route('live.supplier.create')}}" class="btn btn-md btn-primary float-right"> <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                        </div>

                    </div>

                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h2 class="text-dark text-center">Supplier Information</h2>
                    </div>
                    {{-- @dump($supplier) --}}
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="logo text-center my-3">
                            @if(empty($supplier['photo']))
                            <h4>No Image Found!</h4>
                            @else
                            <img src="{{asset($supplier['photo'])}}" class="img-thumbnail img-responsive" alt="Logo" width="250" height="320">
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12">
                        <table class="customer-data table table-striped table-sm">
                                <tr><th>Company Name</th><td>{{$supplier['company_name']}}</td></tr>
                                <tr><th>Owner Name</th><td>{{$supplier['owner_name']}}</td></tr>
                                <tr><th>Officer Name</th><td>{{$supplier['officer_name']}}</td></tr>
                                <tr><th>Address</th><td>{{$supplier['address']}}</td></tr>
                                <tr><th>Mobile Number</th><td>{{$supplier['mobile']}}</td></tr>
                                <tr><th>Officer Number</th><td>{{$supplier['phone']}}</td></tr>
                                <tr><th>Email</th><td>{{$supplier['email']}}</td></tr>
                                <tr><th>Ledger Page</th><td>{{$supplier['ledger_page']}}</td></tr>
                            </table>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-12">
                            <table class="customer-data table table-striped table-sm">
                                <tr><th>Dealer Code</th><td>{{$supplier['dealer_code']}}</td></tr>
                                <tr><th>Dealer Area</th><td>{{$supplier['dealer_area']}}</td></tr>
                                <tr><th>Security</th><td>{{$supplier['security']}}</td></tr>
                                <tr><th>Credit Limit</th><td>{{$supplier['credit_limit']}}</td></tr>
                                <tr><th>Adv.Payment</th><td>{{$supplier['advance_payment']}}</td></tr>
                                <tr><th>Previous Due</th><td>{{$supplier['previous_due']}}</td></tr>
                                <tr><th>Condition</th><td>{{$supplier['condition']}}</td></tr>
                                <tr><th>Starting Date</th><td>{{$supplier['starting_date']}}</td></tr>
                        </table>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="item form-group">
                            <div class="col-md-12 col-sm-12 text-center">
                                <a href="{{route('supplier.index')}}" class="btn btn-danger" type="button">Cancel</a>
                                <button class="btn btn-warning" type="reset" wire:click="clear">Reset</button>
                                <button type="submit" class="btn btn-success" wire:click="submit()">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
