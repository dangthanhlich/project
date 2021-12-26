$(document).ready(function() {
    checkAllChecked();
    /**
     * Check all checked
     */
    function checkAllChecked() {
        var isEnalbed = false;
        var isChecked = false;
        $('#cas031-table tbody tr').each(function(key, element) {
            if ($(element).find('td :checkbox').is(':enabled')) {
                isEnalbed = true;
            }
            if ($(element).find('td :checkbox:enabled').is(':checked')) {
                isChecked = true;
            }
        });
        // check disabled select all
        if (isEnalbed) {
            $('#select-all').prop('disabled', false);
        } else {
            $('#select-all').prop('disabled', true);
        }
        // set checked select all
        if (!isChecked) {
            $('#select-all').prop('checked', false);
        }
        // check disabled button download
        if (isChecked || $('#select-all').is(':checked')) {
            $('#btn-download').prop('disabled', false);
        } else {
            $('#btn-download').prop('disabled', true);
        }
    }

    // select all checkbox event
    $('#select-all').on('click', function() {
        $('#cas031-table :checkbox:enabled').prop('checked', this.checked);
        checkAllChecked();
    });

    // checkboxies event
    $('#cas031-table :checkbox').on('change', function() {
        checkAllChecked();
    });
});