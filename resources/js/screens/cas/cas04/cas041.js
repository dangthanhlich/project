$(document).ready(function() {
    // $('.datepicker').datepicker({
    //     language: 'ja-JP',
    //     format: 'yyyy/mm/dd',
    // }).on('change', function () {
    //     $(this).valid();
    // });

    $('#cas041-form').validate({
        rules: {
            'receive_plan_date': {
                required: true,
                date: true,
            },
            'case_qty': {
                checkNumeric: true,
                maxlength: 9,
            },
            'receive_plan_memo': {
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
        },
        submitHandler: function(form) {
            window.SAFE_LEAVE = true;
            form.submit();
        }
    });
});
