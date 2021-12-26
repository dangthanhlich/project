$(document).ready(function () {
    var delCarIds = []; 
    $('#table-com022').on('click', '.btn-del-car', function(e) {
        _common.showConfirmDialog('取消してもよろしいですか？').then((result) => {
            if (result.isConfirmed) {
                var row = $(this).closest('tr');
                var carId = row.data('car_id');
                if (carId > 0 && !delCarIds.includes(carId)) {
                    delCarIds.push(carId);
                }
                row.remove();
            }
        })
    })

    $('#btn-add-car').click(function(e) {
        var row = $('#add-car-row').clone();
        row.removeAttr('id');
        row.removeClass('none')
        row.children().eq(0).html('<input name="new_car_nos[]" type="text" class="form-control" value="">');
        row.children().eq(1).html('<input name="new_car_qtys[]" type="text" class="form-control" value="">');
        $('#table-com022').find('tbody').append(row);
    })

    $('#form-com022').submit(function(e) {
        e.preventDefault();
        _common.showConfirmDialog('対象荷姿のステータスが「検品前」に戻ります。よろしいですか？')
        .then((result) => {
            if (result.isConfirmed) {
                var ele = $(this)
                delCarIds.forEach(function(item) {
                    ele.append('<input type="hidden" name="del_car_ids[]" value="' + item + '">');
                });
                e.currentTarget.submit();
            }
        })
    })
});
