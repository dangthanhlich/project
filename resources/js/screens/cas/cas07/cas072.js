$('a.car-item-072').click(function() {
    let carPicture = $(this).data('img')
    
    if (!carPicture) return

    $('#modalphoto').modal('show');
    $('#car-picture-main').attr('src', carPicture)
})

$('#modalphoto').on('hidden.bs.modal', function() {
    $('#car-picture-main').attr('src', '')
    $('span.car-no, span.mismatch-qty').text('')
});

$('#cas-072-report').click(function() {
    let caseId = $('#case-id').val()

    $.ajax({
        url: `/case/update-case-status/${caseId}`,
        type: "post",
        data: { screen: '072' },
        success: function (res) {
            window.location = res.cas_070_screen
        },
        error: function(err) {

        }
    })
})