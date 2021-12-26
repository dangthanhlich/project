$(document).ready(function() {
    $('#cas030-form').validate({
        rules: {
            'management_no': {
                number: true,
            },
        },
    });
});
