var Adminusers = function(){
    var list = function(){
        var dataArr = {};
        var columnWidth = { "width": "5%", "targets": 0 };
        var arrList = {
            'tableID': '#admin-users-list',
            'ajaxURL': baseurl + "admin-users/ajaxcall",
            'ajaxAction': 'getdatatable',
            'postData': dataArr,
            'hideColumnList': [],
            'noSortingApply': [0, 6],
            'noSearchApply': [0, 6],
            'defaultSortColumn': [0],
            'defaultSortOrder': 'DESC',
            'setColumnWidth': columnWidth
        };
        getDataTable(arrList);

        $("body").on("click", ".delete-records", function() {
            var id = $(this).data('id');
            setTimeout(function() {
                $('.yes-sure:visible').attr('data-id', id);
            }, 500);
        })

        $('body').on('click', '.yes-sure', function() {
            var id = $(this).attr('data-id');
            var data = { id: id, 'activity': 'delete-records', _token: $('#_token').val() };
                $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val(),
                },
                url:baseurl + "admin-users/ajaxcall",
                data: { 'action': 'common-activity', 'data': data },
                success: function(data) {
                    $("#loader").show();
                    handleAjaxResponse(data);
                }
            });
        });

        $("body").on("click", ".deactive-records", function() {
            var id = $(this).data('id');
            setTimeout(function() {
                $('.yes-sure-deactive:visible').attr('data-id', id);
            }, 500);
        })

        $('body').on('click', '.yes-sure-deactive', function() {
            var id = $(this).attr('data-id');
            var data = { id: id, 'activity': 'deactive-records', _token: $('#_token').val() };
                $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val(),
                },
                url:baseurl + "admin-users/ajaxcall",
                data: { 'action': 'common-activity', 'data': data },
                success: function(data) {
                    $("#loader").show();
                    handleAjaxResponse(data);
                }
            });
        });

        $("body").on("click", ".active-records", function() {
            var id = $(this).data('id');

            setTimeout(function() {
                $('.yes-sure-active:visible').attr('data-id', id);
            }, 500);
        })

        $('body').on('click', '.yes-sure-active', function() {
            var id = $(this).attr('data-id');

            var data = { id: id, 'activity': 'active-records', _token: $('#_token').val() };
                $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val(),
                },
                url:baseurl + "admin-users/ajaxcall",
                data: { 'action': 'common-activity', 'data': data },
                success: function(data) {
                    $("#loader").show();
                    handleAjaxResponse(data);
                }
            });
        });
    }
    var addUsers = function(){
        // add-admin-users
        var form = $('#add-admin-users');
        var rules = {
            first_name: {required: true},
            last_name: {required: true},
            email: {required: true, email: true},
            phone_number: {required: true},
            password: {required: true},
            new_confirm_password: {required: true, equalTo: "#password"},
            status: {required: true},
        };
        var message = {

            first_name: {required: 'Please enter first name'},

            last_name: {required: 'Please enter last name'},

            email :{
                required : "Please enter email address",
                email : "Please enter vaild email address"
            },
            phone_number: {required: 'Please enter phone number'},
            password: {required: 'Please enter password'},
            new_confirm_password: {
                required: "Please enter confirm password",
                equalTo: "Password and confirmn password not match"
            },
            status: {required: "Please select admin user status",},
        }
        handleFormValidateWithMsg(form, rules,message, function(form) {
            handleAjaxFormSubmit(form,true);
        });
    }
    var editUsers = function(){
        // edit-admin-users
        var form = $('#edit-admin-users');
        var rules = {
            editId: {required: true},
            first_name: {required: true},
            last_name: {required: true},
            email: {required: true, email: true},
            phone_number: {required: true},
            status: {required: true},
        };
        var message = {
            first_name: {required: 'Please enter first name'},
            last_name: {required: 'Please enter last name'},
            email :{
                required : "Please enter email address",
                email : "Please enter vaild email address"
            },
            phone_number: {required: 'Please enter phone number'},
            status: {required: "Please select admin user status",},
        }
        handleFormValidateWithMsg(form, rules,message, function(form) {
            handleAjaxFormSubmit(form,true);
        });
    }
    return {
        init:function(){
            list();
        },
        add:function(){
            addUsers();
        },
        edit:function(){
            editUsers();
        },
    }
}();
