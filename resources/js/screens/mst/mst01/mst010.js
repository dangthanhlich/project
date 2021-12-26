$(document).ready(function() {
    $('#btn-search').click(function() {
        $('.form-search').submit();
        $(this).prop('disabled', true);
    });
});