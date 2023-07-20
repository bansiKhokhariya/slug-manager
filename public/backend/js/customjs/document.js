var Document = (function () {
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