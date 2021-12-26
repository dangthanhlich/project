$(document).ready(function() {
    $('#mst011-form').validate({
        rules: {
            'memo_jarp': {
                maxlength: 255,
            },
            'memo_tr': {
                maxlength: 255,
            }
        },
        submitHandler: function(form) {
            window.SAFE_LEAVE = true;
            form.submit();
        },
    })
});