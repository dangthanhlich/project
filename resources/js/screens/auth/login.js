$(document).ready(function() {
    $('#login-form').validate({
        submitHandler: function(form) {
            $('.btn-submit').attr('disabled', true);
            form.submit();
        }
        // rules: {
        //     'loginId': {
        //         required: true,
        //     },
        //     'password': {
        //         required: true,
        //     }
        // },
    });

    
});
