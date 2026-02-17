@section('page-title', 'Customer Checkout')

<div class="container">
    <div class="customer-area">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <h5 class="text-center single_p_title">Checkout</h5>

                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12">
                        <div class="back_button mb-2">
                            <a href="{{route('live.customer.create')}}" class="btn btn-md btn-primary float-right"> <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                        </div>

                    </div>

                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h2 class="text-dark text-center">Customer Information</h2>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="logo text-center my-3">
                            @if(empty($customer['photo']))
                            <h4>No Image Found!</h4>
                            @else
                            <img src="{{asset($customer['photo'])}}" class="img-thumbnail img-responsive" alt="Logo" width="250" height="320">
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12">
                        <table class="customer-data table table-striped table-sm">
                                <tr><th>Customer Name</th><td>{{$customer['name']}}</td></tr>
                                <tr><th>Company Name</th><td>{{$customer['company_name']}}</td></tr>
                                <tr><th>Father Name</th><td>{{$customer['father_name']}}</td></tr>
                                <tr><th>Address</th><td>{{$customer['address']}}</td></tr>
                                <tr><th>NID Number</th><td>{{$customer['nid']}}</td></tr>
                                <tr><th>Date of Birth</th><td>{{$customer['birthday']}}</td></tr>
                                <tr><th>Mobile Number</th><td>{{$customer['mobile']}}</td></tr>
                                <tr><th>Phone Number</th><td>{{$customer['phone']}}</td></tr>
                            </table>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-12">
                            <table class="customer-data table table-striped table-sm">
                                <tr><th>Email</th><td>{{$customer['email']}}</td></tr>
                                <tr><th>Ledger Page</th><td>{{$customer['ledger_page']}}</td></tr>
                                <tr><th>Price Group</th><td>{{$price_groups->name ?? ''}}</td></tr>
                                <tr><th>Customer Type</th><td>{{$customer['type']}}</td></tr>
                                <tr><th>Security</th><td>{{$customer['security']}}</td></tr>
                                <tr><th>Credit Limit</th><td>{{$customer['credit_limit']}}</td></tr>
                                <tr><th>Adv. Collection</th><td>{{$customer['advance_payment']}}</td></tr>
                                <tr><th>Previous Due</th><td>{{$customer['previous_due']}}</td></tr>
                                <tr><th>Starting Date</th><td>{{$customer['starting_date']}}</td></tr>
                        </table>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12">
                        <h2 class="text-dark text-center">Guarantor Information</h2>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="logo text-center my-3">
                            @if(empty($customer['guarantor_photo']))
                            <h4>No Image Found!</h4>
                            @else
                            <img src="{{asset($customer['guarantor_photo'])}}" class="img-thumbnail img-responsive" alt="Logo" width="250" height="320">
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12">
                        <table class="customer-data table table-striped table-sm">
                                <tr><th>Guarantor Name</th><td>{{$customer['guarantor_name']}}</td></tr>
                                <tr><th>Company Name</th><td>{{$customer['guarantor_company_name']}}</td></tr>
                                <tr><th>Father Name</th><td>{{$customer['guarantor_father_name']}}</td></tr>
                                <tr><th>Address</th><td>{{$customer['guarantor_address']}}</td></tr>
                                <tr><th>NID Number</th><td>{{$customer['guarantor_nid']}}</td></tr>
                            </table>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-12">
                            <table class="customer-data table table-striped table-sm">
                                <tr><th>Date of Birth</th><td>{{$customer['guarantor_birthday']}}</td></tr>
                                <tr><th>Email</th><td>{{$customer['guarantor_email']}}</td></tr>
                                <tr><th>Mobile Number</th><td>{{$customer['guarantor_mobile']}}</td></tr>
                                <tr><th>Phone Number</th><td>{{$customer['guarantor_phone']}}</td></tr>
                                <tr><th>Security</th><td>{{$customer['guarantor_security']}}</td></tr>
                                <tr><th>Remarks</th><td>{{$customer['guarantor_remarks']}}</td></tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="item form-group">
                            <div class="col-md-12 col-sm-12 text-center">
                                <button class="btn btn-danger" type="button" wire:click="cancel">Cancel</button>
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
