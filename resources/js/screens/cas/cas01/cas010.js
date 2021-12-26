$(document).ready(function() {
    var lstSchedulePickedUp = $('#lst-schedule-picked-up').val() !== null
        ? JSON.parse($('#lst-schedule-picked-up').val()) : null;
    
    // 集荷予定 event change
    $('[name="schedule_picked_up"]').on('change', function() {
        handleScheduleChecked($(this).val());
    });
    handleScheduleChecked();

    /**
     * handle schedule checked
     */
    function handleScheduleChecked(elementVal = null) {
        var elementVal = elementVal != null ? elementVal : $('[name="schedule_picked_up"]:checked').val();
        if (elementVal == lstSchedulePickedUp['UNREGISTERED_ONLY']) {
            $('#row-collect-plan-date').hide();
            $('#collect-plan-date-from').val(moment().format('YYYY-MM-DD'));
            $('#collect-plan-date-to').val('');
        } else {
            $('#row-collect-plan-date').show();
        }
    }

    $('#cas010-form').validate({
        rules: {
            'collect_plan_date_from': {
                date: true,
            },
            'collect_plan_date_to': {
                date: true,
            },
        },
    });
});
