$(document).ready(function () {
    $('#case040-form').validate({
        rules: {
            'receive_plan_date_from': {
                date: true,
            },
            'receive_plan_date_to': {
                date: true,
            },
        },
    })
    
    var lstSchedulePickedUp = $('#lst-schedule-picked-up').val() !== null
        ? JSON.parse($('#lst-schedule-picked-up').val()) : null;

    // 集荷予定 event change
    $('[name="schedule_picked_up"]').on('change', function () {
        handleScheduleChecked($(this).val());
    });
    handleScheduleChecked();

    /**
     * handle schedule checked
     */
    function handleScheduleChecked(elementVal = null) {
        var elementVal = elementVal != null ? elementVal : $('[name="schedule_picked_up"]:checked').val();
        if (elementVal == lstSchedulePickedUp['UNREGISTERED_ONLY']) {
            $('#row-receive-plan-date').hide();
            $('#receive-plan-date-from').val(moment().format('YYYY/MM/DD'));
            $('#receive-plan-date-to').val('');
        } else {
            $('#row-receive-plan-date').show();
        }
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        cache: false
    });

    $(document).on('click', '#updatePlan', function (event) {
        event.preventDefault();
        var id = $(this).data('id');
        $("#receive_plan_date, #receive_plan_memo").removeClass('error-message').val('');
        validator.resetForm();
        $.get('/case/CAS-040/' + id, function (data) {
            if (data.data.receive_plan_date && data.data.receive_plan_date != '0000-00-00 00:00:00') {
                $("#receive_plan_date").val(moment(data.data.receive_plan_date).format('YYYY/MM/DD'));
            }
            $("#receive_plan_memo").val(data.data.receive_plan_memo);
            $("#planCase_id").val(data.data.id);
        });
    });

    validator = $('#planCaseUpdateForm').validate({
        rules: {
            'receive_plan_date': {
                required: true,
                date: true,
            },
            'receive_plan_memo': {
                maxlength: 255,
            },
        },
        submitHandler: function(form) {
            let id = $("#planCase_id").val();
            let receive_plan_date = $("#receive_plan_date").val();
            let receive_plan_memo = $("#receive_plan_memo").val();
        $.ajax({
            url: "/case/CAS-040/" + id,
            type: "POST",
            data: {

                receive_plan_date: receive_plan_date,
                receive_plan_memo: receive_plan_memo
            },
            success: function (res) {
                if(res){
                  location.reload()
                }
            }
        })
            window.SAFE_LEAVE = true;
          
        },
    });

    // $('#planCaseUpdateForm').on('submit', function (e) {
    //     e.preventDefault();
    //     let id = $("#planCase_id").val();
    //     let receive_plan_date = $("#receive_plan_date").val();
    //     let receive_plan_memo = $("#receive_plan_memo").val();
    //     $.ajax({
    //         url: "/case/CAS-040/" + id,
    //         type: "POST",
    //         data: {

    //             receive_plan_date: receive_plan_date,
    //             receive_plan_memo: receive_plan_memo
    //         },
    //         success: function (res) {
    //             if(res){
    //               location.reload()
    //             }
    //         }
    //     })
    // })

});