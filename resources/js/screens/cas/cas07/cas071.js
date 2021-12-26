const pattern = /^[0-9]+$/;
const mismatchQtyLabel = ['短絡不良数量', '過分解数量', '付属品数量', 'M式未ロック数量', 'M式未収納数量', 'その他数量']
var photoNumberEl = $('.photo-number')

function countPhoto() {
    var total = 0
    
    photoNumberEl.each(function () {
        var numberOfPhoto = parseInt($(this).val())

        if (!Number.isNaN(numberOfPhoto)) {
            total += numberOfPhoto;
        }
    })

    $('.total-photo-of-cars').text(total)
}

$(document).ready(function () {
    countPhoto()

    photoNumberEl.on('blur change keypress', function () {
        let keyNumber = $(this).data('qtykey')
        isValidCarQty($(this), $(this).val().trim(), keyNumber)
        countPhoto();
    });

    // open camera 
    $('button.btn-upload-photo-car').click(function() {
        $('#camera-modal').modal('show');
        $('#btnScreenshot').data('carid', $(this).data('id'))
        $('#btnScreenshot').data('keycar', $(this).data('keyofcar'))

        const video = document.querySelector("#video-popup");
        const canvas = document.querySelector("#canvas-popup");

        let videoStream = null
        let useFrontCamera = false;
        const ratio = 2/3;

        setTimeout(function() {
            const widthVideo = $('#modal-content-camera').width()
            video.width = widthVideo - 50;
            video.height = widthVideo * ratio;
        }, 200);

        const constraints = {
            video: {
                width: {
                    ideal: 600
                },
                height: {
                    ideal: 400
                }
            },
        };

        // use front camera
        document.getElementById("btnChangeCamera").addEventListener("click", function() {
            useFrontCamera = !useFrontCamera;
            
            init();
        });

        // use front camera
        document.getElementById("close-popup").addEventListener("click", function() {
            stopVideoStream();
            $('#camera-modal').modal('hide');
        }); 

        // click capture button
        $('#btnScreenshot').click(function() {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext("2d").drawImage(video, 0, 0);
            let dataURL = canvas.toDataURL("image/jpeg", 1);
            let carId   = $(this).data('carid')
            let keyOfCar = $(this).data('keycar')

            $(`#car-picture-id-${carId}`).attr('src', dataURL)
            $(`#car-picture-id-${carId}`).attr('needupload', '')
            $(`#car-picture-id-${carId}`).parent().removeClass('none')
            $('#camera-modal').modal('hide');
            $(this).removeData('carid')

            isValidCarPhoto($(this), dataURL, keyOfCar)
        });

        $('#camera-modal').on('hidden.bs.modal', function() {
            $(this).removeData('carid')
            stopVideoStream();
        });

        // $('.remove-car-picture').click(function() {
        //     let carId = $(this).data('id')
        //     $(`#car-picture-id-${carId}`).attr('src', '')
        //     $(`#car-picture-id-${carId}`).attr('needupload', '')
        //     $(`#car-picture-id-${carId}`).parent().addClass('none')
        // })

        function stopVideoStream() {
            if (videoStream) {
                videoStream.getTracks().forEach((track) => {
                    track.stop();
                });
            }
        }

        async function init() {
            stopVideoStream()
            constraints.video.facingMode = useFrontCamera ? "user" : "environment"

            try {
                videoStream = await navigator.mediaDevices.getUserMedia(constraints)
                video.srcObject = videoStream
            } catch (error) {
                console.log(error)
            }
        }

        init()
    });

    $('.mismatch-type-input').on('blur change keypress', function() {
        $('input.mismatch-type-input').each(function() {
            isValidMismatchType($(this).val().trim(), $(this).data('number'))
        })
    })

    $('.minimal-blue').change(function() {
        if ($(this).val() == 2) {
            $('.mismatch-type-input').css('border', '1px solid #ddd')
            $('.mismatch-type-input').parent().find('span').text('')

            if ($('.mismatch-type-input[data-id!=""]').length === 0) {
                $('.mismatch-type-input').val('')
            }
        }
    })

    // Save on 071
    $('#btn-save-on-071').click(function(event) {
        event.preventDefault()

        let cars = []
        let mismatchTypes = []
        let isMismatch = $('.is-mismatch:checked').val() ? $('.is-mismatch:checked').val() : ''

        let errCarQty = []
        let errCarPhoto = []

        photoNumberEl.each(function (index, car) {

            let carId = $(this).data('id');

            if (!isValidCarQty($(this), $(this).val().trim(), index)) {
                errCarQty.push(false)
            }

            if (!isValidCarPhoto($(this), $(`#car-picture-id-${carId}`).attr('src').trim(), index)) {
                errCarPhoto.push(false)
            }

            cars.push({
                id: carId,
                qty: $(this).val().trim(),
                picture: $(`#car-picture-id-${carId}`).attr('src'),
                need_upload: $(`#car-picture-id-${carId}`).attr('needupload') == 1 ? false : true,
            })
        })

        let errMismatchType = []

        $('input.mismatch-type-input').each(function(index, item) {
            let number = index + 1

            mismatchTypes.push({
                id: $(`#mismatch_type_${number}`).data('id'),
                mismatch_type_number: number,
                mismatch_type: $(`#mismatch_type_${number}`).val(),
                mismatch_qty: $(`#mismatch_type_${number}`).val(),
            })

            if (! isValidMismatchType($(this).val().trim(), $(this).data('number'))) {
                errMismatchType.push(false)
            }
        })

        if (isValidMismatchOption() && errCarQty.length === 0 && errCarPhoto.length === 0 && errMismatchType.length === 0) {
            $.ajax({
                url: "/case/handleCas071",
                type: "POST",
                data: {
                    case_id: $('#case-id-value').val(),
                    cars: cars,
                    is_mismatch: isMismatch,
                    mismatch_types: mismatchTypes,
                },
                success: function (res) {
                    $('.is_mismatch_error').text('')
                    $('.mismatch-type-input').css('border', '1px solid #ddd')
                    $('.mismatch-type-input').parent().find('span').text('')
                    $('.validate-message').html('')
                    $('.validate-message').addClass('none')

                    if (isMismatch == 2) {
                        $('input.mismatch-type-input').val('')
                    }

                    if (res.status) {
                        window.location = res.cas_072_screen
                    }

                    if (!res.status) {
                        $('.validate-message').html(res.message)
                        $('.validate-message').removeClass('none')
                        $('html, body').animate({ scrollTop: 0 }, 300)   
                    }
                },
                error: function(err) {
                    let errors = err.responseJSON.errors
                    let messageErrorCarPhoto = []
                    let messageErrorCarQty = []
                    let messageErrorIsMismatch = []
                    let messageErrorMismatchType = []

                    $('.is_mismatch_error').text('')
                    $('.mismatch-type-input').css('border', '1px solid #ddd')
                    $('.mismatch-type-input').parent().find('span').text('')

                    // Error mismatch qty
                    $('.mismatch-type-input').each(function(index, item) {
                        if (errors[`mismatch_types.${index}.mismatch_qty`]) {
                            $(this).css('border', '1px solid red')
                            $(this).parent().find('span').text('いずれかの選択' + errors[`mismatch_types.${index}.mismatch_qty`][0])

                            if (messageErrorMismatchType.indexOf() < 0) {
                                messageErrorMismatchType.push(mismatchQtyLabel[index] + errors[`mismatch_types.${index}.mismatch_qty`][0])
                            }
                        }
                    })

                    // Error not upload and car qty
                    photoNumberEl.each(function (index, item) {
                        if (errors[`cars.${index}.picture`]) {
                            if (messageErrorCarPhoto.indexOf(errors[`cars.${index}.picture`][0]) < 0) {
                                messageErrorCarPhoto.push(errors[`cars.${index}.picture`][0])
                            }
                        }

                        if (errors[`cars.${index}.qty`]) {
                            if (messageErrorCarQty.indexOf(errors[`cars.${index}.qty`][0]) < 0) {
                                messageErrorCarQty.push(errors[`cars.${index}.qty`][0])
                            }
                        }
                    })

                    // Error is mismatch
                    if (errors[`is_mismatch`]) {
                        $('.is_mismatch_error').text(errors[`is_mismatch`][0])
                        messageErrorIsMismatch.push(errors[`is_mismatch`][0])
                    }

                    $('.validate-message').html(messageErrorCarQty.concat(messageErrorCarPhoto, messageErrorIsMismatch, messageErrorMismatchType).join('<br/>'))
                    $('.validate-message').removeClass('none')
                    $('html, body').animate({ scrollTop: 0 }, 300)
                }
            });
        }
    })

    // Update Case Status
    $('.btn-set-case-status').click(function(event) {
        event.preventDefault()
        let caseId = $('#case-id-value').val()

        $.ajax({
            url: `/case/update-case-status/${caseId}`,
            type: "post",
            data: {},
            success: function (res) {
                window.location = res.cas_070_screen
            },
            error: function(err) {

            }
        })
    })

    // Return case
    $('.btn-return-case').click(function() {
        let caseId = $('#case-id-value').val()
        let reasonContent = $('.case-return-type').val().trim()

        $('.error-case-return-type').text('')
        $('.case-return-type').css('border', '1px solid #DDDDDD')

        if (!isValidReason(reasonContent)) {
            return false
        }

        $.ajax({
            url: `/case/return-case/${caseId}`,
            type: "post",
            data: {
                return_reason: reasonContent
            },
            success: function (res) {
                $('#modalreturn').modal('hide');
                window.location = res.cas_070_screen
            },
            error: function(err) {
            }
        })

    })

    $('.case-return-type').on('blur change', function() {
        isValidReason($(this).val().trim())
    })

    $('#modalreturn').on('hidden.bs.modal', function() {
        $('.case-return-type').val('')
        $('.error-case-return-type').text('')
        $('.case-return-type').css('border', '1px solid #DDDDDD')
    });
});

function isValidReason(reasonContent)
{
    if (!reasonContent) {
        $('.case-return-type').css('border', '1px solid red')
        $('.error-case-return-type').text('返品理由は必須項目です。')
        return false
    }
    
    if (reasonContent.length > 255) {
        $('.case-return-type').css('border', '1px solid red')
        $('.error-case-return-type').text(`返品理由は255文字以下で入力してください。（現在${reasonContent.length}文字）`)
        return false
    }

    return true
}

function isValidCarQty(el, carQty, keyNumber)
{
    $(`.car-qty-${keyNumber}-error`).text('')
    $(`.car-qty-${keyNumber}-error`).hide()
    el.css('border', '1px solid #ddd')

    if (!carQty) {
        el.css('border', '1px solid red')
        $(`.car-qty-${keyNumber}-error`).show()
        $(`.car-qty-${keyNumber}-error`).parent().show()
        $(`.car-qty-${keyNumber}-error`).text('回収個数いずれかの選択は必須項目です。')
        return false
    }

    if (carQty.length > 9) {
        el.css('border', '1px solid red')
        $(`.car-qty-${keyNumber}-error`).show()
        $(`.car-qty-${keyNumber}-error`).parent().show()
        $(`.car-qty-${keyNumber}-error`).text(`回収は「9」文字以下で入力してください。（現在${carQty.length}文字）`)
        return false
    }

    if (!pattern.test(carQty)) {
        el.css('border', '1px solid red')
        $(`.car-qty-${keyNumber}-error`).show()
        $(`.car-qty-${keyNumber}-error`).parent().show()
        $(`.car-qty-${keyNumber}-error`).text('回収個数は半角数字で入力してください。')
        return false
    }

    return true
}

function isValidCarPhoto(el, carPhoto, keyNumber)
{
    $(`.car-photo-${keyNumber}-error`).text('')
    $(`.car-photo-${keyNumber}-error`).hide()

    if (!carPhoto) {
        $(`.car-photo-${keyNumber}-error`).show()
        $(`.car-photo-${keyNumber}-error`).parent().show()
        $(`.car-photo-${keyNumber}-error`).text('荷札と個数がわかるように写真を撮影し、アップロードしてください。')
        return false
    }

    return true
}

function isValidMismatchType(typeValue, numberError)
{
    $(`.mismatch-type-number-${numberError}`).css('border', '1px solid #DDD')
    $(`.error-type-${numberError}`).text('')
    let haveValue = ''

    $('.mismatch-type-input').each(function(item) {
        if ($(this).val().trim()) {
            haveValue = $(this).val().trim()
        }
    })

    if (!typeValue && !haveValue) {
        $(`.mismatch-type-number-${numberError}`).css('border', '1px solid red')
        $(`.error-type-${numberError}`).text(mismatchQtyLabel[numberError] + 'いずれかの選択は必須項目です。')
        return false
    }

    if (typeValue.length > 9) {
        $(`.mismatch-type-number-${numberError}`).css('border', '1px solid red')
        $(`.error-type-${numberError}`).text(mismatchQtyLabel[numberError] + `は「9」文字以下で入力してください。（現在${typeValue.length}文字）`)
        return false
    }

    const pattern = /^[0-9]+$/;
    if (typeValue && !pattern.test(typeValue)) {
        $(`.mismatch-type-number-${numberError}`).css('border', '1px solid red')
        $(`.error-type-${numberError}`).text(mismatchQtyLabel[numberError] + 'は半角数字で入力してください。')
        return false
    }

    return true
}

function isValidMismatchOption() {
    let isMismatch = $('.is-mismatch:checked').val() ? $('.is-mismatch:checked').val() : ''
    $('.is_mismatch_error').text('')

    console.log(isMismatch);

    if (!isMismatch) {
        $('.is_mismatch_error').text('未合致区分の選択は必須項目です。')
        return false
    }

    return true
}