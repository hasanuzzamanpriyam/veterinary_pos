@extends('layouts.admin')

@section('page-title')
Customer Add
@endsection

@section('main-content')

    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>ADD Customer <small>Add your customer from here</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a class="dropdown-item" href="#">Settings 1</a>
                                </li>
                                <li><a class="dropdown-item" href="#">Settings 2</a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form action="" method="" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                        @csrf

                        <div class="row">
                            <div class="item form-group col-6">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Customer Id <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="text" id="customer_id" name="customer_id" required="required" class="form-control ">
                                </div>
                            </div>
                            <div class="item form-group col-6 ">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Ledger Page No<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="number" id="ledger_pg_no" name="ledger_pg_no" required="required" class="form-control">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="item form-group col-6">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Customer Name <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" id="customer_name" name="customer_name" required="required" class="form-control ">
                                </div>
                            </div>

                            <div class="item form-group col-6 ">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Price Group<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" id="price_group" name="price_group" required="required" class="form-control">
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="item form-group col-6 ">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Company Name <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" id="company_name" name="company_name" required="required" class="form-control">
                                </div>
                            </div>

                            <div class="item form-group col-6 ">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Advance Payment<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="number" id="advance_payment" name="advance_payment" required="required" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="item form-group col-6 ">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Father Name <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" id="father_name" name="father_name" required="required" class="form-control">
                                </div>
                            </div>

                            <div class="item form-group col-6 ">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Old Due<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="number" id="old_due" name="old_due" required="required" class="form-control">
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="item form-group col-6 ">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">NID Number<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="number" id="customer_photo" name="customer_photo" required="required" class="form-control">
                                </div>
                            </div>

                            <div class="item form-group col-6 ">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Security<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" id="Security" name="Security" required="required" class="form-control">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="item form-group col-6 ">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Address<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" id="address" name="address" required="required" class="form-control">
                                </div>
                            </div>
                            <div class="item form-group col-6 ">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Credit Limit<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="number" id="credit_limit" name="credit Limit" required="required" class="form-control">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="item form-group col-6 ">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Email<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="email" id="email" name="email" required="required" class="form-control">
                                </div>
                            </div>
                            <div class="item form-group col-6 ">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Customer Photo <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="file" id="customer_photo" name="customer_photo" required="required" class="form-control">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="item form-group col-6 ">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Phone Number<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" id="phone" name="phone" required="required" class="form-control">
                                </div>
                            </div>
                            <div class="item form-group col-6 ">
                                <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Remarks</label>
                                <div class="col-md-6 col-sm-6 ">
                                        <textarea type="text" name="remarks" id="remarks" cols="10" rows="2"></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="item form-group col-6 ">
                                <label class="col-form-label col-md-3 col-sm-3 label-align">Date Of Birth <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input id="birthday" name="birthday" class="date-picker form-control" placeholder="dd-mm-yyyy" type="text" required="required" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)">
                                    <script>
                                        function timeFunctionLong(input) {
                                            setTimeout(function() {
                                                input.type = 'text';
                                            }, 6000);
                                        }
                                    </script>
                                </div>
                            </div>

                        </div>
                        <div class="ln_solid"></div>
                        <div class="item form-group">
                            <div class="col-md-6 col-sm-6 offset-md-3">
                                <button class="btn btn-primary" type="button">Cancel</button>
                                <button class="btn btn-primary" type="reset">Reset</button>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
