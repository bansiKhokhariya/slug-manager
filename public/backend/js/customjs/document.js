var Document = (function () {
    var list = function(){
        var dataArr = {};
        var columnWidth = { "width": "5%", "targets": 0 };
        var arrList = {
            'tableID': '#document-list',
            'ajaxURL': baseurl + "document/ajaxcall",
            'ajaxAction': 'getdatatable',
            'postData': dataArr,
            'hideColumnList': [],
            'noSortingApply': [0, 8],
            'noSearchApply': [0, 8],
            'defaultSortColumn': [0],
            'defaultSortOrder': 'DESC',
            'setColumnWidth': columnWidth
        };
        getDataTable(arrList);

        $('body').on('change', '.all-check', function(){
            if ($(this).prop('checked')==true){
                $('.single-check:visible').prop('checked', true);
            }else{
                $('.single-check:visible').prop('checked', false);
            }
        });

        $('body').on('change', '.single-check', function(){
            var temp_master = true;
            $('.single-check').each(function () {
                if($(this).prop('checked') != true) {
                    temp_master = false;
                }

                if(temp_master){
                    $(".all-check").prop('checked', true);
                }else{
                    $(".all-check").prop('checked', false);
                }
            });
        });

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
                url:baseurl + "document/ajaxcall",
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
                url:baseurl + "document/ajaxcall",
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
                url:baseurl + "document/ajaxcall",
                data: { 'action': 'common-activity', 'data': data },
                success: function(data) {
                    $("#loader").show();
                    handleAjaxResponse(data);
                }
            });
        });

    }

    var addDocument = function () {
        $(".select2").select2();
        var form = $("#add-document");
        var rules = {
            category: { required: true },
            title: { required: true },
            subTitle: { required: true },
            topic: { required: true },
            subTopic: { required: true },
            content: { required: true },
            reference: { required: true },
        };
        var message = {
            category: {
                required: "Please select slug category",
            },
            title: {
                required: "Please enter title",
            },
            subTitle: {
                required: "Please enter subTitle",
            },
            topic: {
                required: "Please enter topic",
            },
            subTopic: {
                required: "Please enter subTopic",
            },
            content: {
                required: "Please enter content",
            },
            reference: {
                required: "Please enter reference",
            },
        };
        handleFormValidateWithMsg(form, rules, message, function (form) {
            handleAjaxFormSubmit(form, true);
        });

        $('body').on("change",".category",function(){
            var category = $(this).val();
            if(category == null || category == ''){
            }else{
                var data = { category: category, _token: $('#_token').val() };
                $("#loader").show();
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val(),
                    },
                    url: baseurl + "document/ajaxcall",
                    data: { 'action': 'change-category', 'data': data },
                    success: function(data) {
                        $('#loader').hide();
                        var output = JSON.parse(data);
                        var temp_html = '';
                        var html =' <option value="">Please select subTitle</option>';
                        for (var i = 0; i < output.length; i++) {
                            temp_html = '<option value="' + output[i].id + '">' + output[i].slug + '</option>';
                            html = html + temp_html;
                        }
                        $('#subTitle').html(html);
                    },
                    complete: function(){
                        $('#loader').hide();
                    }
                });
            }
        });

    };

    var editDocument = function(){
        $(".select2").select2();
        var form = $("#edit-document");
        var rules = {
            editId: { required: true },
            category: { required: true },
            title: { required: true },
            subTitle: { required: true },
            topic: { required: true },
            subTopic: { required: true },
            content: { required: true },
            reference: { required: true },
        };
        var message = {
            editId: {
                required: "Please add ",
            },
            category: {
                required: "Please select slug category",
            },
            title: {
                required: "Please enter title",
            },
            subTitle: {
                required: "Please enter subTitle",
            },
            topic: {
                required: "Please enter topic",
            },
            subTopic: {
                required: "Please enter subTopic",
            },
            content: {
                required: "Please enter content",
            },
            reference: {
                required: "Please enter reference",
            },
        };
        handleFormValidateWithMsg(form, rules, message, function (form) {
            handleAjaxFormSubmit(form, true);
        });

        $('body').on("change",".category",function(){
            var category = $(this).val();
            if(category == null || category == ''){
            }else{
                var data = { category: category, _token: $('#_token').val() };
                $("#loader").show();
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val(),
                    },
                    url: baseurl + "document/ajaxcall",
                    data: { 'action': 'change-category', 'data': data },
                    success: function(data) {
                        $('#loader').hide();
                        var output = JSON.parse(data);
                        var temp_html = '';
                        var html =' <option value="">Please select subTitle</option>';
                        for (var i = 0; i < output.length; i++) {
                            temp_html = '<option value="' + output[i].id + '">' + output[i].slug + '</option>';
                            html = html + temp_html;
                        }
                        $('#subTitle').html(html);
                    },
                    complete: function(){
                        $('#loader').hide();
                    }
                });
            }
        });
    }

    return {
        init: function () {
            list();
        },
        add: function () {
            addDocument();
        },
        edit: function () {
            editDocument();
        },
    };
})();
