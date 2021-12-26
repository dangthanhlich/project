$(document).ready(function() {
    var setUserStatusUrl = $('#setUserStatusUrl').val();
    function setStatus(flag, buttonDiv) {
        $.ajax({
            url: setUserStatusUrl,
            type: 'POST',
            dataType: 'json',
            data: {
                user_id: buttonDiv.data('id'),
                status: flag,
            },
            success: function (result) {
                // no error
                if (!result.hasError) {
                    if (flag === 1) {
                        buttonDiv.text('有効');
                        buttonDiv.addClass('btn-warning');
                        buttonDiv.addClass('active-user');
                        buttonDiv.removeClass('btn-danger');
                        buttonDiv.removeClass('disable-user');
                    }
                    if (flag === 0) {
                        buttonDiv.text('無効');
                        buttonDiv.addClass('btn-danger');
                        buttonDiv.addClass('disable-user');
                        buttonDiv.removeClass('btn-warning');
                        buttonDiv.removeClass('active-user');
                    }
                }
                _common.hideLoading();
            },
            error: function() {
                _common.hideLoading();
            }
        });
    }

    $(document).on('click', '.active-user', function() {
        _common.showLoading();
        setStatus(0, $(this));
    });

    $(document).on('click', '.disable-user', function() {
        _common.showLoading();
        setStatus(1, $(this));
    });

});
