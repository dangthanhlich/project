$(document).ready(function () {
    $('#cas060-form').validate({
        rules: {
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
    var formPopup = $('#add-temp-cases-form').validate({
        ignore: [],
        rules: {
            'temp_case_no': {
                required: true,
                checkNumeric: true,
                maxlength: 7
            },
            'case_picture_3' : {
                required: true
            }
        },
        submitHandler: function(form) {
            window.SAFE_LEAVE = true;
            form.submit();
        }
    });

    // search like with param in ケース番号
    var keyArr = [];
    var textSearchTemp = ''
    $('#search060').click(function () {
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

    // open camera 
    $('#camera-btn').click(function() {
        $('#camera-modal').modal('show');
        const video = document.querySelector("#video-popup");
        const canvas = document.querySelector("#canvas-popup");
        const screenshotsContainer = document.querySelector("#screenshotsContainer");
        const ratio = 2/3;
        let videoStream = null
        let useFrontCamera = false; //front camera
        let videoSize = video.offsetWidth;
        setTimeout(function() {
            let videoSize = video.offsetWidth;
            video.width = videoSize;
            video.height = videoSize;
        }, 200);
        const constraints = {
            video: {
                width: {
                    ideal: videoSize
                },
                height: {
                    ideal: videoSize * ratio
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
        document.getElementById("btnScreenshot").addEventListener("click", function() {
            let img = document.getElementById('screenshot');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext("2d").drawImage(video, 0, 0);
            let dataURL = canvas.toDataURL("image/jpeg", 1);
            img.src = dataURL;
            document.getElementById('case-picture-3').value = dataURL;
            $('#case-picture-3').valid();
            screenshotsContainer.prepend(img);
            $('#camera-modal').modal('hide');
        });

        $('#camera-modal').on('hidden.bs.modal', function() {
            stopVideoStream();
        });

        function stopVideoStream() {
            if (videoStream) {
              videoStream.getTracks().forEach((track) => {
                track.stop();
              });
            }
          }

        async function init() {
            stopVideoStream()
            constraints.video.facingMode = useFrontCamera ? "user" : "environment";
            try {
                videoStream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = videoStream;
            } catch (error) {
                console.log(error)
            }
        }
        init();
    });

    $(document).on('click', '#close-popup-modal', function() {
        $('#temp-case-no').val('');
        $('#case-picture-3').val('');
        $('#screenshotsContainer').toggleClass('none');
        document.getElementById('screenshot').src = '';
        formPopup.resetForm();
    })

    // add mstScrapperId to hidden input on popup
    $(document).on('click', '#addTempCase', function (event) {
        event.preventDefault();
        $("#mst-scrapper-id").val($(this).data('id'));
    });

    //click QR読み取り button
    const qrCodeReader = new QRCode('canvas', 'video', 500);
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
        if (caseNo) {
            $('[name="case_no"]').val(caseNo);
            $('#search060').click();
        }
    }

    // click 登録 button to creat new temp_case, contract
    $(document).on('click', '#submit-popup', function (e) {
        e.preventDefault();
        if ($('#add-temp-cases-form').valid()) {
            $('#submit-popup').attr("disabled", true);
            let mstScrapperId = $("#mst-scrapper-id").val();
            let tempCaseNo = $("#temp-case-no").val();
            let casePicture3 = $("#case-picture-3").val();
            $.ajax({
                url: "/case/CAS-060",
                type: "POST",
                data: {
                    mst_scrapper_id: mstScrapperId,
                    temp_case_no: tempCaseNo,
                    case_picture_3: casePicture3,
                },
                beforeSend: function() {
                    $('#modaladdcase').modal('toggle');
                    $('#loading').css('display', 'block');
                },
                complete: function() {
                    $('#loading').css('display', 'none');
                },
                success: function (res) {
                    setTimeout(function(){
                        location.reload();
                    })
                }
            });
        }
    });
});