$(document).ready(function() {
    var USER_TYPE_ADMIN = 1;
    var USER_TYPE_SELF_RECONCILIATION = 2;
    var USER_TYPE_OFFICE = 3;

    $('select[name="user_type"]').on('change', function () {
        $("#mst031-form").validate().element( this );
        $('.officeContent').hide();
        $('.jarpContent').hide();
        if ($(this).val() == USER_TYPE_SELF_RECONCILIATION) {
            $('.jarpContent').show();
            // jarp_type
            $('#jarp-type').rules('add', {
                required: true,
                checkValueList: [1,2],
            });
        }

        if ($(this).val() == USER_TYPE_OFFICE) {
            $('.officeContent').show();
            $('#office-code').rules('add', {
                required: true,
            });
            $('input[name="office_manager"]').rules('add', {
                checkValueList: [1],
            });
            $('input[name="trader_authority[]"]').rules('add', {
                required: true,
                checkValueList: [1,2,3,4],
            });
            $('input[name="trader_authority[]"]').on('change', function() {
                $("#mst031-form").validate().element( this );
            });
            $('input[name="office_manager"]').on('change', function() {
                $("#mst031-form").validate().element( this );
            });
        }

        // if ($(this).val() == USER_TYPE_ADMIN) {
        //     $('.jarpContent').hide();
        //     $('.officeContent').hide();
        // }
    });

    function checkUnique(column, data, id) {
        var isSuccess = false;
        var checkUniqueDataUrl = $('#checkUniqueData').val();
        $.ajax({
            url: checkUniqueDataUrl,
            type: 'GET',
            dataType: 'json',
            async: false,
            data: {
                type: column,
                dataCheck: data,
                id: id,
            },
            success: function (result) {
                if (result['hasError']) {
                    isSuccess = false;
                } else {
                    isSuccess = true;
                }
            }
        });
        return isSuccess;
    }

    $.validator.addMethod("checkLoginIdUnique", function(value, element) {
        return checkUnique('loginId', value, '');
    }, "入力されたログインIDは既に使用されています。");

    $.validator.addMethod("checkUserNameUnique", function(value, element) {
        return checkUnique('userName', value, '');
    }, "入力されたユーザー名は既に使用されています。");

    $.validator.addMethod("checkOfficeCode", function(value, element) {
        // get office_name from office_code
        var getNameOfficeUrl = $('#getNameOfficeUrl').val();
        var isSuccess = false;
        $.ajax({
            url: getNameOfficeUrl,
            type: 'GET',
            dataType: 'json',
            async: false,
            data: {
                officeCode: value
            },
            success: function (result) {
                if (result['hasError']) {
                    $('#office_name').text('');
                    isSuccess = false;
                } else {
                    $('#office_name').text(result['office_name']);
                    isSuccess = true;
                }
            }
        });
        return isSuccess;
    }, "入力された所属事業所コードは正しくありません。");

    $('#mst031-form').validate({
        rules: {
            'id-login': {
                required: true,
                maxlength: 50,
                checkLoginIdUnique: true,
            },
            'id-user': {
                required: true,
                maxlength: 50,
                // checkUserNameUnique: true,
            },
            'pass': {
                required: true,
                checkCharacterlatin: true,
                minlength: 8,
                maxlength: 20,
            },
            'user_type': {
                required: true,
                checkValueList: [1,2,3],
            },
            'email': {
                maxlength: 100,
                checkValidEmailRFC: true,
            },
            'office_code': {
                checkOfficeCode: true,
            }
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('name') === 'trader_authority[]' ||
                $(element).attr('name') === 'office_manager') {
                error.appendTo(element.parent().parent().parent().parent());
            } else {
                error.appendTo(element.parent());
            }
        },
        highlight: function(element, errorClass, validClass) {
            if (element.type === "checkbox") {
                this.findByName(element.name).addClass(errorClass).removeClass(validClass);
            }
        },
        unhighlight: function(element, errorClass, validClass) {
            if (element.type === "checkbox") {
                this.findByName(element.name).removeClass(errorClass).addClass(validClass);
            }
        },
        submitHandler: function(form) {
            window.SAFE_LEAVE = true;
            form.submit();
        },
    });

});
