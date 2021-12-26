$(document).ready(function() {
    // init variables
    const caseStatusVal = {
        'PICK_UP_RECEPTION': 1,
        'COLLECTED': 2,
        'BEFORE_INSPECTION': 3,
        'CHECKING_INQUIRIES': 4,
        'BEFORE_RECONFIRMING_THE_NUMBER': 5,
        'BEFORE_THE_TAKE_BACK_REPORT': 6,
        'PICK_UP_REPORT_ENTERED': 7,
        'COMPLETION_OF_TAKE_BACK_REPORT': 8,
        'RP_INSPECTED': 9,
    };
    const caseNoMaxLength = 7;
    let currentSearchCaseNo = '';
    let currentSearchIndex = 0;
    let currentMaxIndex = 0;
    let initedDataTable = false;
    let table = null;
    const qrCodeReader = new QRCode('canvas', 'video');
    let userCompanyCode = $('#data-parser').data('userCompanyCode');

    // init jquery validation
    $('#form-search-case-no').validate({
        rules: {
            'search_case_no': {
                checkNumeric: true,
                maxlength: caseNoMaxLength,
            },
        },
    });

    // on change office_code reload table
    $('#office-code').change(function() {
        // reset case_no search
        $('[name="search_case_no"]').val('');
        $('#search-row').click();
        if (!$(this).val()) {
            $('#search-card').addClass('none');
            return true;
        }
        $('#search-card').removeClass('none');
        if (!initedDataTable) {
            loadDataTable();
            initedDataTable = true;
        } else {
            table.clear().draw();
            table.ajax.reload();
        }
    }).trigger('change');

    // update case_status handle
    $('#tableData').delegate('.rowCheck', 'click', function() {
        var rowElm = $(this).parent().parent();
        var rowData = rowElm.data();
        var updateParams = {
            'case_id': rowData.caseId,
            'case_status': rowElm.hasClass('checkedRow')
                ? caseStatusVal['COLLECTED']
                : caseStatusVal['BEFORE_INSPECTION'],
            'isTempCase': rowData.isTempCase ? 1 : 0,
        };
        $.ajax({
            url: $('#route').data('updateCaseStatus'),
            type: 'POST',
            data: updateParams,
            dataType: 'json',
            beforeSend: function() {
                $('#loading').css('display', 'block');
            },
            complete: function() {
                $('#loading').css('display', 'none');
            },
            success: function(data) {
                if (data.result) {
                    rowElm.toggleClass('checkedRow');
                    countCaseStatus();
                }
            },
        });
    });

    // search case_no handle
    $('#search-row').click(function() {
        var newSearchCaseNo = $('[name="search_case_no"]').val();
        if ($('#form-search-case-no').valid() && newSearchCaseNo) {
            if (newSearchCaseNo != currentSearchCaseNo) {
                // renew search state
                currentSearchCaseNo = newSearchCaseNo;
                currentSearchIndex = 0;
                currentMaxIndex = 0;
                $('#tableData tr.trow.searchMatched').removeClass('searchMatched');
                $('#tableData tr.trow').each(function() {
                    var trCaseNo = $(this).data('caseNo');
                    // search LIKE
                    if (trCaseNo && trCaseNo.toLowerCase().indexOf(currentSearchCaseNo) > -1) {
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

    $('#case050-submit').click(function(e) {
        e.preventDefault();
        let selectedCompanyCode = '';
        let selectedOfficeCode = '';
        if ($('#office-code').val()) {
            selectedOfficeCompanyCode = $('#office-code').val();
            selectedOfficeCompanyCode = selectedOfficeCompanyCode.split('-');
            selectedOfficeCode = selectedOfficeCompanyCode[0];
            selectedCompanyCode = selectedOfficeCompanyCode[1];
        }
        if (selectedCompanyCode == userCompanyCode) {
            window.location.href = $('#route').data('com030');
        } else {
            let case051Route = $('#route').data('case051');
            case051Route = case051Route.replace('00000', selectedOfficeCode);
            window.location.href = case051Route;
        }
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
        } else {
            let invalidCaseNoMessage = $('#data-parser').data('invalidCaseNoMessage');
            alert(invalidCaseNoMessage);
        }
    }

    /**
     * Load DataTable
     */
    function loadDataTable() {
        table = $('#tableData').DataTable({
            ajax: {
                url: $('#route').data('searchByOfficeCode'),
                type: 'GET',
                data: function(data) {
                    var selectedOfficeCode = '';
                    if ($('#office-code').val()) {
                        selectedOfficeCode = $('#office-code').val();
                        selectedOfficeCode = selectedOfficeCode.split('-');
                        selectedOfficeCode = selectedOfficeCode[0];
                    }
                    data.office_code = selectedOfficeCode;
                },
                beforeSend: function() {
                    $('#loading').css('display', 'block');
                },
                complete: function() {
                    $('#loading').css('display', 'none');
                    if ($('#office-code').val()) {
                        countCaseStatus();
                    }
                }
            },
            createdRow: function(row, data, dataIndex) {
                // add attr for search
                $(row).data('caseNo', data.case_no);
                $(row).data('caseId', data.case_id);
                $(row).data('isTempCase', !!data.temp_case_id);
                $(row).addClass('trow');
                // handle row highlight
                if (data.case_status == caseStatusVal['BEFORE_INSPECTION']) {
                    $(row).addClass('checkedRow');
                }
            },
            columns: [
                {
                    render: function (data, type, item) {
                        var html = '<span class="fontbig">';
                        html += item.case_no;
                        html += '</span>';
                        return html;
                    }
                },
                {
                    render: function () {
                        return '<button type="button" class="btn btn-info btn-round btn-mini rowCheck buttonNext">OK</button>';
                    },
                }
            ],
            autoWidth: false,
            // 件数切替
            lengthChange: false,
            // 検索
            searching: false,
            // 検索結果件数
            info: true,
            // ソート
            ordering: false,
            // ページング
            paging: false,
            // 横スクロール
            scrollX: true,
            dom: "<'row'<'col-sm-6'l><'col-sm-6 right'i>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12'p>>",
            //縦スクロールを行うheight
            scrollY: "250px",
            // 縦スクロール時、件数が足りなければheight自動調整
            scrollCollapse: true,
            // 言語
            language: {
                sInfo: '_TOTAL_件表示',
                sInfoEmpty: '0件表示',
                sEmptyTable: '該当するデータがありません。'
            }
        });
    }

    /**
     * Count case_status = 3 and show message
     */
    function countCaseStatus() {
        let countRow = 0;
        if ($('#tableData tr.checkedRow')) {
            countRow = $('#tableData tr.checkedRow').length;
        }
        $('#count-case-status').html(countRow + 'ケース チェック済み');
    }

});
