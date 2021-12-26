$('#btn-save-073').click(function() {

    let caseNoIsValid = isValidCaseNo($('#case_no').val().trim())
    let caseId = $(this).data('id')
    let carNoIsValid  = true
    let carNoErrorArr = []
    let cars = []
    
    let caseNoErrorEl = $('.car-no-error')
    caseNoErrorEl.hide()
    caseNoErrorEl.text('')

    if ($('.car-id-number').length === 0) {
        caseNoErrorEl.show()
        caseNoErrorEl.text('車台番号は必須項目です。')
        return false
    }

    $('.car-id-number').each(function() {
        cars.push({
            id: $(this).data('carid'),
            car_no: $(this).val()
        })

        carNoIsValid = isValidCarNo($(this).val(), $(this).data('id'))

        if (!carNoIsValid) {
            carNoErrorArr.push(carNoIsValid)
        }
    })

    if (caseNoIsValid && carNoErrorArr.length === 0) {
        $.ajax({
            url: `/case/handleCas073/${caseId}`,
            type: "POST",
            data: {
                case_no: $('#case_no').val().trim(),
                cars: cars,
            },
            success: function (res) {
                if (res.status) {
                    window.location = res.cas_070_screen
                }
            },
            error: function(err) {
                let errors = err.responseJSON.errors

                if (errors[`case_no`]) {
                    isValidCaseNo($('#case_no').val().trim(), errors[`case_no`][0])
                }

                $('.car-id-number').each(function(index) {
                    if (errors[`cars.${index}.car_no`]) {
                        isValidCarNo($(`.car-id-number-${index}`).val(), index, errors[`cars.${index}.car_no`][0])
                    }
                })
            }
        })
    }
})

$('.add-car-no').click(function() {
    let qty = $('#list-car-no tr').length

    $('#list-car-no').append(`
        <tr>
            <td>
                <input type="tel" class="form-control car-id-number-${qty} car-id-number" value="" data-id="${qty}" data-carid="" />
                <span class="error car-no-${qty}-error"></span>
            </td>
        </tr>
    `)
})

$('#case_no').on('blur change', function() {
    isValidCaseNo($(this).val().trim())
})

$(document).on('blur change', '.car-id-number', function() {
    isValidCarNo($(this).val(), $(this).data('id'))
})

$('.back-cancel').click(function() {
    let url = $(this).data('url')
    
    Swal.fire({
        text: "編集中の情報が破棄されますがよろしいですか？",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#51bcda',
        cancelButtonColor: '#fbc658',
        confirmButtonText: 'OK',
        cancelButtonText: 'キャンセル',
        allowEscapeKey: false,
        allowEnterKey: false,
        allowOutsideClick: false
    }).then(function (result) {
        if (result.isConfirmed) {
            window.SAFE_LEAVE = true
            location.href = url
        } else {
            $(window).bind('beforeunload')
        }
    });
})

function isValidCaseNo(value, message = null) { 
    $('.case-no-error').hide()
    $('.case-no-error').text('')
    $('#case_no').css('border', '1px solid #ccc')

    if (!value) {
        $('.case-no-error').show()
        $('.case-no-error').text(message ? message : 'ケース番号は必須項目です。')
        $('#case_no').css('border', '1px solid red')
        return false
    }

    const pattern = /^[0-9]+$/;

    if (!pattern.test(value)) {
        $('.case-no-error').show()
        $('.case-no-error').text(message ? message : 'ケース番号は半角数字で入力してください。')
        $('#case_no').css('border', '1px solid red')
        return false
    }

    if (value.length > 7) {
        $('.case-no-error').show()
        $('.case-no-error').text(message ? message : `ケース番号は「7」文字以下で入力してください。（現在${value.length}文字）`)
        $('#case_no').css('border', '1px solid red')
        return false
    }

    if (message) {
        $('.case-no-error').show()
        $('.case-no-error').text(message)
        $('#case_no').css('border', '1px solid red')
    }

    return true
}

function isValidCarNo(value, carId, message = null) {
    let errorMessage = $(`.car-no-${carId}-error`)
    let inputCarNoEl = $(`.car-id-number-${carId}`)
    let carNos = []

    $('.car-id-number').each(function() {
        if ($(this).val().trim()) {
            carNos.push($(this).val().trim())
        }
    })

    errorMessage.hide()
    errorMessage.text('')
    inputCarNoEl.css('border', '1px solid #ccc')

    if (isDuplicate(carNos, value)) {
        errorMessage.show()
        errorMessage.text(message ? message : `入力された車台番号は既に使用されています。`)
        inputCarNoEl.css('border', '1px solid red')
        return false
    }

    if (!value) {
        errorMessage.show()
        errorMessage.text(message ? message : '車台番号は必須項目です。')
        inputCarNoEl.css('border', '1px solid red')
        return false
    }

    if (value.length > 50) {
        errorMessage.show()
        errorMessage.text(message ? message : `車台番号は「50」文字以下で入力してください。（現在${value.length}文字）`)
        inputCarNoEl.css('border', '1px solid red')
        return false
    }

    return true
}

function isDuplicate(arr, value) {
    return arr.some((element, index) => {
        return arr.indexOf(element) !== index && value == element
    })
}
