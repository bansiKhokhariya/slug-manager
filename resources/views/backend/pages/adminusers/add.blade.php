@extends('backend.layout.app')
@section('section')
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <!--begin::Card-->
                <div class="card card-custom gutter-b example example-compact">
                    <div class="card-header">
                        <h3 class="card-title">{{ $header['title'] }}</h3>
                    </div>
                     <!--begin::Form-->
                     <form class="form" id="add-admin-users" method="POST" action="{{ route('admin-users.save-add') }}" autocomplete="off">@csrf
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>First Name
                                        <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="first_name"  class="form-control" placeholder="Enter first name" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last Name
                                        <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="last_name" class="form-control" placeholder="Enter last name" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email
                                        <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="email"  class="form-control" placeholder="Enter email" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone Number
                                        <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="phone_number" class="form-control" placeholder="Enter phone number" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password
                                        <span class="text-danger">*</span>
                                        </label>
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>New Confirm Password
                                        <span class="text-danger">*</span>
                                        </label>
                                        <input type="password" name="new_confirm_password" class="form-control" placeholder="Enter Confirm Password" autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <div class="radio-inline" style="margin-top:10px">
                                            <label class="radio radio-lg radio-success" >
                                            <input type="radio" name="status" class="radio-btn" value="A" checked="checked"/>
                                            <span></span>Active</label>
                                            <label class="radio radio-lg radio-danger" >
                                            <input type="radio" name="status" class="radio-btn" value="I"/>
                                            <span></span>Inactive</label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary mr-2 submitbtn green-btn">Submit</button>
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Card-->

            </div>

        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->
@endsection
