const caseNoMaxLength = 7;
const qrCodeReader = new QRCode('canvas', 'video')

$(document).ready(function () {
    let start = 0

    // Search Case No
    $('#search-case-no').click(function () {
        let caseNo = $('#case-no-value').val()
        let oldCaseNo = localStorage.getItem('case-no-search')

        if (validateCaseNo(caseNo)) {
            let caseNoArr = getCaseNoFounded(caseNo.toLocaleLowerCase())
            localStorage.setItem('case-no-search', caseNo.toLocaleLowerCase())

            if (caseNo != oldCaseNo || start === caseNoArr.length) start = 0

            doFilter(caseNoArr, start)
            start++

            $('#result').show()
            $('.total-found-case-no').text(caseNoArr.length)
            $('.total-case-no').text($('.case-no-number').length)
        }
    });

    
    $('#case-no-value').on('blur change', function() {
        if ($.trim($(this).val().length) == 0) {
            $('table tbody tr').removeClass('found-case-no')
            localStorage.removeItem('case-no-search')
            $('#result').hide()
            $('.total-found-case-no').text(0)
        }

        validateCaseNo($.trim($(this).val()))
    })

    // Do scan QRcode
    $('#scan-qrcode').click(function() {
        $('#qr-modal').modal('show');

        $('#qr-modal').on('shown.bs.modal', function() {
            qrCodeReader.start(function(detectedCodes) {
                caseNoFormFill(detectedCodes[0].rawValue);
                $('#qr-modal').modal('hide');
            });
        });

        $('#qr-modal').on('hidden.bs.modal', function() {
            qrCodeReader.stop();
        });
    });

    // Start NFC reader on click
    $('#nfc-btn').click(function() {
        Nfc.init();
        Nfc.startScanning(
            function(message, serialNumber) {
                const record = message.records[0];
                const textDecoder = new TextDecoder(record.encoding);
                caseNoFormFill(textDecoder.decode(record.data));
            },
            function() {
                alert('Cannot read data from this NFC tag');
            }
        );
    });
});

function getCaseNoFounded(caseNo) {
    let caseNoArr = []

    $('.case-no-number').each(function(index) {
        let caseNoOfList = $.trim($(this).text()).toLocaleLowerCase()

        if (caseNoOfList.search(caseNo) >= 0) {
            $(this).parent().parent().attr('row-number', index)
            caseNoArr.push({ number: index, attributeName: 'row-number' })
        }
    })

    return caseNoArr
}

function doFilter(caseNoArr, index) {
    $('table tbody tr').removeClass('found-case-no')
    $('table tbody tr').removeAttr('id')

    if (caseNoArr.length === 0) return

    $(`tr[${caseNoArr[index].attributeName}="${caseNoArr[index].number}"]`).addClass('found-case-no')
    $(`tr[${caseNoArr[index].attributeName}="${caseNoArr[index].number}"]`).attr('id', 'searchResult')

    document.querySelector('#searchResult').scrollIntoView({
        behavior: 'smooth'
    })
}

function validateCaseNo(caseNo) {
    let isValid = true
    const pattern = /^[0-9]+$/;

    if ($.trim(caseNo).length === 0) {
        $('.case-no-invalid').html('ケース番号 は必須項目です。')
        isValid = false
    } else if (!pattern.test(caseNo)) {
        $('.case-no-invalid').html(`ケース番号は半角数字で入力してください。`)
        isValid = false
    } else if (! $.isNumeric(caseNo) || $.trim(caseNo).length > caseNoMaxLength) {
        $('.case-no-invalid').html(`ケース番号は「7」文字以下で入力してください。（現在${caseNo.length}文字）`)
        isValid = false
    }
    
    if (isValid) {
        $('.case-no-invalid').html('')
        $('#case-no-value').css('border', '1px solid #DDDDDD')
    } else {
        $('#case-no-value').css('border', '1px solid red')
    }

    return isValid
}

function caseNoFormFill(caseNo) {
    $('#case-no-value').val(caseNo);
    $('#search-case-no').click();
}