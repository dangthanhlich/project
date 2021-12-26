$(document).ready(function () {
    // open file browser
    $('button[name="btn_import_excel"]').click(function (e) {
        e.preventDefault();
        $('input[name="excel_import"]').click();
    });
    // submit form when selected file
    $('input[name="excel_import"]').change(function () {
        if ($(this).val()) {
            $('#rep010-form').submit();
        }
    });
});
