$(document).ready(function() {
    $('#mst022-form').validate({
        rules: {
            'office_code': {
                required: true,
                checkNumeric: true,
                maxlength: 12,
            },
            'office_name': {
                maxlength: 60,
            },
            'office_name_kana': {
                checkKatakana: true,
                maxlength: 120,
            },
            'office_address_zip': {
                maxlength: 8,
            },
            'office_address_pref': {
                maxlength: 4,
            },
            'office_address_city': {
                maxlength: 20,
            },
            'office_address_town': {
                maxlength: 15,
            },
            'office_address_block': {
                maxlength: 20,
            },
            'office_address_building': {
                maxlength: 31,
            },
            'office_tel': {
                maxlength: 13,
            },
            'office_fax': {
                maxlength: 13,
            },
            'pic_name': {
                maxlength: 60,
            },
            'pic_name_kana': {
                maxlength: 120,
            },
            'pic_tel': {
                maxlength: 13,
            },
        },
        submitHandler: function(form) {
            window.SAFE_LEAVE = true;
            form.submit();
        },
    });
});
