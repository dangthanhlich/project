$(document).ready(function() {
    $('#cas012-form').validate({
        rules: {
            'collect_plan_date': {
                required: true,
                date: true,
            },
            'case_qty': {
                checkNumeric: true,
                maxlength: 9,
            },
            'collect_plan_memo': {
                maxlength: 255,
            },
            'empty_case_qty': {
                checkNumeric: true,
                maxlength: 9,
            },
            'bag_qty': {
                checkNumeric: true,
                maxlength: 9,
            },
            'plan_date_adjusted_flg[]': {
                checkValueList: [1],
            }
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('name') === 'plan_date_adjusted_flg[]') {
                error.appendTo(element.parent().parent().parent().parent());
            } else {
                error.appendTo(element.parent());
            }
        },
        submitHandler: function(form) {
            window.SAFE_LEAVE = true;
            form.submit();
        },
    });
    $('[name="plan_date_adjusted_flg[]"]').on('change', function() {
        $("#cas012-form").validate().element(this);
    });
});
