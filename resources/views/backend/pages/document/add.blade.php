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
                        <form class="form" id="add-document" method="POST" action="{{ route('document.save-add') }}"
                            autocomplete="off">@csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Title
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="title" class="form-control"
                                        placeholder="Please enter title" autocomplete="off">
                                </div>

                                <div class="form-group">
                                    <label>Slug Category
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select2 category" id="category" name="category">
                                        <option value="">Please select slug category</option>
                                        @foreach ($slug_category as $key => $value)
                                            <option value="{{ $value['id'] }}">{{ $value['category'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>SubTitle
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select2 subTitle" id="subTitle" name="subTitle">
                                        <option value="">Please select subTitle</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Topic
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="topic" class="form-control"
                                        placeholder="Please enter topic" autocomplete="off">
                                </div>

                                <div class="form-group">
                                    <label>SubTopic
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="subTopic" class="form-control"
                                        placeholder="Please enter subTopic" autocomplete="off">
                                </div>

                                <div class="form-group">
                                    <label>Content
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="content" class="form-control" placeholder="Please enter content" autocomplete="off"></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Reference
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="reference" class="form-control"
                                        placeholder="Please enter reference" autocomplete="off">
                                </div>

                                <div class="form-group">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <div class="radio-inline" style="margin-top:10px">
                                        <label class="radio radio-lg radio-success">
                                            <input type="radio" name="status" class="radio-btn" value="A"
                                                checked="checked" />
                                            <span></span>Active</label>
                                        <label class="radio radio-lg radio-danger">
                                            <input type="radio" name="status" class="radio-btn" value="I" />
                                            <span></span>Inactive</label>
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
