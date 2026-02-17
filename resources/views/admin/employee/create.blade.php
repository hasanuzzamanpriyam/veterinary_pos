@extends('layouts.admin')

@section('page-title')
Employee Add
@endsection

@section('main-content')
{{-- Image plugin css --}}
<link rel="stylesheet" href="{{asset('assets/css/dropify.min.css')}}" />

    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <div class="header-title d-flex align-items-center gap-2 p-3">
                    <h2>Add New Employee</h2>
                    <a href="#" class="mr-auto ml-3 cursor-pointer" onclick="history.back()"><i class="fa fa-close"></i></a>
                </div>
            </div>
            <div class="x_content">
                <br />
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{route('employee.store')}}" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left employee_add_form">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">

                            <div class="item form-group d-flex justify-content-center">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" for="name">Employee Name <span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <input type="text" id="name" name="name"  class="form-control">
                                </div>
                            </div>
                            <div class="item form-group d-flex justify-content-center">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" for="father_name">Father Name <span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <input type="text" id="father_name" name="father_name"  class="form-control">
                                </div>
                            </div>
                            <div class="item form-group d-flex justify-content-center">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" for="last-name">Address<span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <textarea type="text" name="address" id="address" cols="5" rows="1"  class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="item form-group d-flex justify-content-center">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" for="mobile">Mobile Number<span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <input type="text" id="mobile" name="mobile"  class="form-control">
                                </div>
                            </div>
                            <div class="item form-group d-flex justify-content-center">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" for="email">Email<span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <input type="text" id="email" name="email"  class="form-control">
                                </div>
                            </div>
                            <div class="item form-group d-flex justify-content-center">
                                <label class="col-form-label col-md-4 col-sm-3 label-align" for="nid">NID Number<span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <input type="number" id="nid" name="nid"  class="form-control">
                                </div>
                            </div>
                            <div class="item form-group d-flex justify-content-center">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" for="photo">Photo <span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <input type="file" id="photo" name="photo"  class="dropify form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="item form-group d-flex justify-content-center">
                                <label class="col-form-label col-md-4 col-sm-4 label-align">Date Of Birth <span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <div class="input-group date" id="datepicker33">

                                        <input name="birthday" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="item form-group d-flex justify-content-center">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" for="joining_date">Joining Date<span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    {{-- <input id="joining_date" name="joining_date" class="form-control" placeholder="dd-mm-yyyy" type="text"  type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)"> --}}

                                    <div class="input-group date" id="datepicker_jdate">

                                        <input name="joining_date" type="text" class="form-control" placeholder="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="item form-group d-flex justify-content-center">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" for="designation">Designation<span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    {{-- <input type="text" id="designation" name="designation"  class="form-control"> --}}

                                    <select name="designation" id="designation" class="form-control">
                                        <option value="">Select Designation</option>
                                        @foreach ($designations as $designation)
                                            <option value="{{$designation->designation_title}}">{{$designation->designation_title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="item form-group d-flex justify-content-center">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" for="salary_amount">Salary Amount<span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <input type="number" id="salary_amount" name="salary_amount"  class="form-control">
                                </div>
                            </div>

                            <div class="item form-group d-flex justify-content-center">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" for="bonus_amount">Bonus/Others Tk<span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <input type="number" id="bonus_amount" name="bonus_amount"  class="form-control">
                                </div>
                            </div>

                            <div class="item form-group d-flex justify-content-center">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" for="security">Security<span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <input type="text" id="security" name="security"  class="form-control">
                                </div>
                            </div>

                            <div class="item form-group d-flex justify-content-center">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" for="remarks">Remarks<span class=""></span>
                                </label>
                                <div class="col-md-8 col-sm-8">
                                    <textarea type="text" name="remarks" id="remarks" cols="10" rows="1"  class="form-control"></textarea>
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="item form-group">
                        <div class="col-md-6 col-sm-12  text-center">
                            <a href="{{route('employee.index')}}" class="btn btn-danger" type="button">Cancel</a>
                            <button class="btn btn-warning" type="reset">Reset</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- image plugin js--}}
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/dropify.min.js')}}"></script>
        <script type="text/javascript">

            $('.dropify').dropify({
                messages: {
                    'default': 'Drag and drop',
                    'remove':  'Remove',
                }
            });

            $(document).ready(function () {
                $('#datepicker33').datepicker({
                    format: 'dd-mm-yyyy',
                    autoclose: true,
                    todayHighlight: true
                });

                $('#datepicker_jdate').datepicker({
                    format: 'dd-mm-yyyy',
                    autoclose: true,
                    todayHighlight: true
                });

            });
        </script>

@endsection
