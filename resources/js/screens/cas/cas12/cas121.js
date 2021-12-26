$(document).ready(function () {
    $('#cas121-form').validate({
        rules: {
            'actual_qty_rp': {
                checkNumeric: true,
            },
            'mismatch_qty_1': {
                checkNumeric: true,
            },
            'mismatch_qty_2': {
                checkNumeric: true,
            },
            'mismatch_qty_3': {
                checkNumeric: true,
            },
            'allow_flg': {
                required: true,
            }
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('name') === 'allow_flg') {
                error.appendTo(element.parent().parent().parent().parent());
            } else {
                error.appendTo(element.parent());
            }
        },
        submitHandler: function(form) {
            window.SAFE_LEAVE = true;
            form.submit();
        }
    });
    $('[name="allow_flg"]').on('change', function() {
        checkDisplay(this);
    });
    checkDisplay();
    function checkDisplay(element) {
        var element = element != undefined ? $(element) : $('[name="allow_flg"]');
        element.each(function() {
            if ($(this).val() == 0) {
                $('.group-content').removeClass('d-none');
            } else {
                $('.group-content').addClass('d-none');
                $('.group-content').find('input:not([type=hidden])').each(function() {
                    $(this).val('');
                    $(this).removeClass('.error-message');
                    $('#' + $(this).attr('id') + '-error').html('');
                });
            }
        });
    }

    // click button 未合致品写真 open camera 
    $('#camera-btn').click(function() {
        $('#camera-modal').modal('show');
        const video = document.querySelector("#video-popup");
        const canvas = document.querySelector("#canvas-popup");
        const screenshotsContainer = document.querySelector("#screenshotsContainer");
        let videoStream = null
        let useFrontCamera = false; //front camera
        let videoSize = video.offsetWidth;
        const ratio = 2/3;
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
            $('.noneContent').toggleClass('none');
            $('#camera-modal').modal('hide');
        }); 
        // click capture button
        document.getElementById("btnScreenshot").addEventListener("click", function() {
            let img = document.getElementById('screenshot');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext("2d").drawImage(video, 0, 0);
            let dataURL = canvas.toDataURL("image/jpeg", 1);
            $('#img-old').hide();
            img.src = dataURL;
            document.getElementById('case-picture-4').value = dataURL;
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

    // event click button 確認完了
    $('#btn-completed').click(function() {
        var messageAllowFlg = '未合致区分の選択';
        var messageMismatch = 'いずれかの選択';
        if (!$('[name="allow_flg"]').is(':checked')) {
            $('input[name="allow_flg"]').rules('add', { 
                required: true,
                messages: {
                    required: $.validator.messages.requiredDefault(messageAllowFlg),
                }
            });
        } else {
            $('input[name="allow_flg"]').rules('remove', 'required');
            if ($('[name="allow_flg"]').val() == 0) {
                $('input[name="mismatch_qty_1"]').rules('add', { 
                    required: true,
                    messages: {
                        required: function(params, input) {
                            return $.validator.messages.requiredDefault($(input).data('label') + messageMismatch);
                        },
                    }
                });
                $('input[name="mismatch_qty_2"]').rules('add', { 
                    required: true,
                    messages: {
                        required: function(params, input) {
                            return $.validator.messages.requiredDefault($(input).data('label') + messageMismatch);
                        },
                    }
                });
                $('input[name="mismatch_qty_3"]').rules('add', { 
                    required: true,
                    messages: {
                        required: function(params, input) {
                            return $.validator.messages.requiredDefault($(input).data('label') + messageMismatch);
                        },
                    }
                });
            } else {
                $('input[name="mismatch_qty_1"]').rules('remove', 'required');
                $('input[name="mismatch_qty_2"]').rules('remove', 'required');
                $('input[name="mismatch_qty_3"]').rules('remove', 'required');
            }
        }
    });
});