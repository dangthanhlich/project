$(document).ready(function () {
    $('#pal010-form').validate({
        rules: {
            'pallet_no': {
                required: true,
                checkNumeric: true,
                maxlength: 6,
            },
            'case_no': {
                checkNumeric: true,
                maxlength: 7,
            }
        },
        submitHandler: function(form) {
            window.SAFE_LEAVE = true;
            form.submit();
        }
    });

    $(document).on('click', '#pallet-search', function (e) {
        if ($('#pal010-form').valid()) {
            $('#loading').css('display', 'block');
        }
    });

    // search like with param in ケース番号
    var keyArr = [];
    var textSearchTemp = ''
    $('#case-search').click(function () {
        var caseNoValue = document.getElementById('case-no').value;
        var lastLoop = [];
        $(".case-table tr").each(function(idx, element) {
            var data = $(element).find("td:first span").text();
            var matchedIndex = data.indexOf(caseNoValue);
            if (matchedIndex > 0) {
                lastLoop.push(idx);
            }
        });
        $(".case-table tr").each(function(index, ele) {
            var data = $(ele).find("td:first span").text();
            var matchedIndex = data.indexOf(caseNoValue);
            if (matchedIndex > 0) {
                if (keyArr.includes(index)) {
                    if (textSearchTemp === caseNoValue) {
                        if (lastLoop.length == keyArr.length) {
                            keyArr = [];
                        }
                        if (keyArr.length > 0) {
                            return;
                        }
                    }
                    keyArr = [];
                }
                if ($('#searchResult').length > 0) {
                    $('#searchResult').css({ 'background-color' : '' });
                    $('#searchResult').removeAttr('id');   
                }
                $(ele).attr("id","searchResult");
                document.getElementById("searchResult").scrollIntoView(true);
                document.getElementById("searchResult").style.backgroundColor = "#ffff8e";
                document.getElementById("result").style.display = "block";
                if (lastLoop.length > 1) {
                    keyArr.push(index);
                }
                textSearchTemp = caseNoValue;
                return false;
            } else {
                if (lastLoop.length == 0) {
                    if ($('#searchResult').length > 0) {
                        $('#searchResult').css({ 'background-color' : '' });
                        $('#searchResult').removeAttr('id');   
                    }
                }
            }
        });
    }); // table、行のハイライト

    // click 紐付
    $('.case-table').delegate('.add-btn', 'click', function() {
        // remove tr 該当するデータがありません
        if ($('.dataTables_empty').length) {
            $('.dataTables_empty').parent().remove();
        } 
        var rowElm = $(this).parent().parent();
        var caseId = rowElm.find('button').data('id');
        var rowCount = $('.case-table').find('tbody tr').length;
        $('#tableSP1_info').html(rowCount - 1 + ' 件表示');
        if (rowCount == 1) {
            var trInfoEmpty = '<tr class="odd">' + 
                                 '<td valign="top" colspan="2" class="dataTables_empty">該当するデータがありません。</td>' +
                              '</tr>'
            $('.case-table').find('tbody').append(trInfoEmpty);
        }
        // replace 紐付 button to 削除 button
        var deleteBtn = '<button type="button" class="btn btn-danger btn-round btn-mini delete-btn" data-id='+caseId+'>解除</button>';
        rowElm.find('td:nth-of-type(2)').html(deleteBtn);
        $(".pallet-table tbody").append(rowElm);
        // display total tr in 紐付済ケース table
        var rowCountPallet = $('.pallet-table').find('tbody tr').length;
        $('#tableCheck_info').html(rowCountPallet + ' ケース紐付済');
    });

    // click 削除
    $('.pallet-table').delegate('.delete-btn', 'click', function() {
        // remove tr 該当するデータがありません
        if ($('.dataTables_empty').length) {
            $('.dataTables_empty').parent().remove();
        } 
        var rowElm = $(this).parent().parent();
        var caseId = rowElm.find('button').data('id');
        var rowCount = $('.pallet-table').find('tbody tr').length;
        $('#tableCheck_info').html(rowCount - 1 + ' ケース紐付済');
        if (rowCount == 1) {
            var trInfoEmpty = '<tr class="odd">' + 
                                 '<td valign="top" colspan="2" class="dataTables_empty">該当するデータがありません。</td>' +
                              '</tr>'
            $('.pallet-table').find('tbody').append(trInfoEmpty);
        }
        // replace 削除 button to 紐付 button
        var addBtn = '<button type="button" class="btn btn-info btn-round btn-mini add-btn" data-id='+caseId+'>紐付</button>';
        rowElm.find('td:nth-of-type(2)').html(addBtn);
        $(".case-table tbody").append(rowElm);
        // display total tr in ケース番号 table
        var rowCountCase = $('.case-table').find('tbody tr').length;
        $('#tableSP1_info').html(rowCountCase + ' 件表示');
    });

    //click QR読み取り button
    const qrCodeReader = new QRCode('canvas', 'video');
    var btnClick;
    // start QR reader on click
    $('.qr-btn').click(function() {
        btnClick = $(this);
        $('#qr-modal').modal('show');
    });

    // start QR reader after modal loaded
    $('#qr-modal').on('shown.bs.modal', function() {
        qrCodeReader.start(function(detectedCodes) {
            textFormFill(detectedCodes[0].rawValue, btnClick);
            $('#qr-modal').modal('hide');
        });
    });

    // stop QR reader on hide modal
    $('#qr-modal').on('hidden.bs.modal', function() {
        qrCodeReader.stop();
    });

    // start NFC reader on click
    $('.nfc-btn').click(function() {
        btnClick = $(this);
        Nfc.init();
        Nfc.startScanning(
            function(message, serialNumber) {
                const record = message.records[0];
                const textDecoder = new TextDecoder(record.encoding);
                textFormFill(textDecoder.decode(record.data), btnClick);
            },
            function() {
                alert('Cannot read data from this NFC tag');
            }
        );
    });

    /**
     * Fill qr-code to text input
     * 
     * @param {string} code
     */
     const palletNoMaxLength = 6;
     const caseNoMaxLength = 7;
     function textFormFill(code, btnClick) {
        if (code && !isNaN(code)) {
            var inputText = btnClick.parent().parent().find('input[type=text]').attr("name");
            if (inputText === 'pallet_no') {
                if (code.length === palletNoMaxLength) {
                    $('[name="pallet_no"]').val(code);
                    $('#pallet-search').click();
                }
            }
            if (inputText === 'case_no') {
                if (code.length === caseNoMaxLength) {
                    $('[name="case_no"]').val(code);
                    $('#case-search').click();
                }
            }
        }
    }

    // click 完了 button to insert/update pallet_case table
    $(document).on('click', '#btn-submit', function (e) {
        if (!$('#pallet-no').valid()) {
            $('#pallet-no').focus();
            return false;
        }
        // 紐付済ケース
        var idPalletTable = [];
        var trPalletTable = $('.pallet-table').find('tbody tr');
        trPalletTable.each(function(idx, ele){
            if ($(ele).find('button.delete-btn').data('id')) {
                idPalletTable.push($(ele).find('button.delete-btn').data('id'));
            }
        });
        // ケース番号
        var idCaseTable = [];
        var trCaseTable = $('.case-table').find('tbody tr');
        trCaseTable.each(function(idx, ele){
            if ($(ele).find('button.add-btn').data('id')) {
                idCaseTable.push($(ele).find('button.add-btn').data('id'));
            }
        });
        // get pallet_id
        var palletId = $('#pallet-id').val();
        // total case constraint with pallet_case
        var totalCase = $('#total-case-constraint').val();
        $.ajax({
            url: $('#route').data('processPalletCase'),
            context:$(this),
            type: "POST",
            data: {
                idPalletTable: idPalletTable,
                idCaseTable: idCaseTable,
                palletId: palletId,
                totalPalletCase: totalCase
            },
            beforeSend: function() {
                $('#loading').css('display', 'block');
            },
            complete: function() {
                $('#loading').css('display', 'none');
            },
            success: function (res) { 
                if (res) {
                    window.location.href = $('#route').data('pal010');;
                } else {
                    $('.alert-danger').remove();
                    var errDiv = '<div class="alert alert-danger">' +
                                    '<ul>' +
                                        '<li>これ以上紐づけることはできません。紐づけることができるケースは最大25個です。</li>' +
                                    '</ul>' +
                                '</div>'
                    $('.content:first').prepend(errDiv);
                    window.scrollTo(0, 0);
                }
            }
        });
    });
});