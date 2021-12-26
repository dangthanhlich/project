$(document).ready(function() {
    var USER_TYPE_ADMIN = 1;
    var USER_TYPE_SELF_RECONCILIATION = 2;
    var USER_TYPE_OFFICE = 3;

    $('select[name="user_type"]').on('change', function () {
        $('.jarpContent').hide();
        $('.officeContent').hide();
        if ($(this).val() == USER_TYPE_SELF_RECONCILIATION) {
            $('.jarpContent').show();
            $('.officeContent').hide();
        }
        if ($(this).val() == USER_TYPE_OFFICE) {
            $('.jarpContent').hide();
            $('.officeContent').show();
        }
    });

    $.ajaxSetup({ cache: false });

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

    $.validator.addMethod("checkUserNameUnique", function(value, element) {
        var id = $('#userId').val();
        return checkUnique('userName', value, id);
    }, "入力されたユーザー名は既に使用されています。");

    // get office_name from office_code
    var getNameOfficeUrl = $('#getNameOfficeUrl').val();
    $('input[name="office_code"]').on('change', function () {
        var officeCode = $(this).val();
        $.ajax({
            url: getNameOfficeUrl,
            type: 'GET',
            dataType: 'json',
            data: {
                officeCode: officeCode
            },
            success: function (result) {
                if (result['hasError']) {
                    $('#office_name').text('');
                }
                $('#office_name').text(result['office_name']);
            }
        });
    });

    $('#mst032-form').validate({
        rules: {
            'user_name': {
                required: true,
                maxlength: 50,
                // checkUserNameUnique: true,
            },
            'password': {
                checkCharacterlatin: true,
                minlength: 8,
                maxlength: 20,
            },
            'email': {
                maxlength: 100,
                // email: true,
                checkValidEmailRFC: true,
            },
        },
        submitHandler: function(form) {
            window.SAFE_LEAVE = true;
            form.submit();
        },
    });

    // jarp_type
    if ($('#jarp-type').length > 0) {
        $('#jarp-type').rules('add', {
            required: true,
        });
    }

});
