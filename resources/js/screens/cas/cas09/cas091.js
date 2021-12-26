$(document).ready(function() {
    $('#btn-confirmed').click(function() {
        var countUnchecked = $('#car-list .car:not(.checkedRow)').length;
        if (countUnchecked > 0) {
            alert('個数確認が未完了の車台があります。');
            return false;
        }
    });
});
