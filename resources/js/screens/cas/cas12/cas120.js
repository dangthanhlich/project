$(document).ready(function() {
    var caseNoMaxLength = 7;
    var currentSearchCaseNo = '';
    var currentSearchIndex = 0;
    var currentMaxIndex = 0;
    var qrCodeReader = new QRCode('canvas', 'video');

    // init validate
    $('#form-search-case-no').validate({
        rules: {
            'search_case_no': {
                checkNumeric: true,
                maxlength: caseNoMaxLength,
            },
        },
    });

    // search case_no
    $('#search-row').on('click', function() {
        var newSearchCaseNo = $('[name="search_case_no"]').val();
        if ($('#form-search-case-no').valid() && newSearchCaseNo) {
            if (newSearchCaseNo != currentSearchCaseNo) {
                // renew search state
                currentSearchCaseNo = newSearchCaseNo;
                currentSearchIndex = 0;
                currentMaxIndex = 0;
                $('#tableData tr.trow.searchMatched').removeClass('searchMatched');
                $('#tableData tr.trow').each(function() {
                    var trCaseNo = $(this).data('case-no');
                    // search LIKE
                    if (String(trCaseNo) && String(trCaseNo).toLowerCase().indexOf(currentSearchCaseNo) > -1) {
                        $(this).addClass('searchMatched');
                        currentMaxIndex++;
                    }
                });
                currentMaxIndex -= 1;
            }
            // rotate highlight and scroll searchMatched
            $('#tableData tr.trow.searchMatchedHighlight').removeClass('searchMatchedHighlight');
            $('#tableData tr.trow.searchMatched:eq('+ currentSearchIndex + ')').addClass('searchMatchedHighlight');
            if ($('.searchMatchedHighlight').length) {
                $('.searchMatchedHighlight')[0].scrollIntoView(true);
            }
            if (currentSearchIndex >= currentMaxIndex) {
                currentSearchIndex = 0;
            } else {
                currentSearchIndex++;
            }
        } else {
            $('#tableData tr.trow.searchMatched').removeClass('searchMatched');
            $('#tableData tr.trow.searchMatchedHighlight').removeClass('searchMatchedHighlight');
        }
    });

    // start QR reader on click
    $('#qr-btn').click(function() {
        $('#qr-modal').modal('show');
    });
    // start QR reader after modal loaded
    $('#qr-modal').on('shown.bs.modal', function() {
        qrCodeReader.start(function(detectedCodes) {
            caseNoFormFill(detectedCodes[0].rawValue);
            $('#qr-modal').modal('hide');
        });
    });
    // stop QR reader on hide modal
    $('#qr-modal').on('hidden.bs.modal', function() {
        qrCodeReader.stop();
    });
    // start NFC reader on click
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

    /**
     * Fill case_no text input
     * 
     * @param {string} caseNo
     */
    function caseNoFormFill(caseNo) {
        if (caseNo && !isNaN(caseNo) && caseNo.length === caseNoMaxLength) {
            $('[name="search_case_no"]').val(caseNo);
            $('#search-row').click();
        }
    }
});