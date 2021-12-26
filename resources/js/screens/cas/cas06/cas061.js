$(document).ready(function () {
    // init variables
    const d001Message = 'してもよろしいですか？'
    $('#cas061-form').validate({
        ignore: ".ignore",
        rules: {
            'case_no': {
                checkNumeric: true,
                maxlength: 7,
            },
            'car_no': {
                required: true,
                checkNumeric: true,
            },
            'case_picture_2': {
                required: true
            }
        },
        submitHandler: function(form) {
            window.SAFE_LEAVE = true;
            form.submit();
        }
    });

    // click button 削除
    let carIdsDelete = [];
    $('.delete-btn').click(function(e) { 
        Swal.fire({
            text: "削除" + d001Message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#51bcda',
            cancelButtonColor: '#fbc658',
            confirmButtonText: 'OK',
            cancelButtonText: 'キャンセル',
            allowEscapeKey: false,
            allowEnterKey: false,
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                carIdsDelete.push($(this).data('id'));
                $('#cars-delete').val(JSON.stringify(carIdsDelete));
                $(this).parent().parent().toggleClass('none');
            }
        })
    });

    // click 削除 button when add car
    $('#delete-add-btn').click(function() {
        $('#car-no').addClass('ignore');
        $('#car-no').attr("disabled", true);
        $('#car-no').removeClass('error-message').next('div.error-message').remove();
    });

    // click 車台追加 button
    $('#add-car-btn').click(function() {
        $('#car-no').attr("disabled", false);
        $('#car-no').removeClass('ignore');
    });

    // click button 荷姿写真 open camera 
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
            document.getElementById('case-picture-2').value = dataURL;
            $('#case-picture-2').valid();
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

    // click 取消 button to cancel case, temp_case
    $('#cancel-case').click(function() {
        Swal.fire({
            text: "取消" + d001Message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#51bcda',
            cancelButtonColor: '#fbc658',
            confirmButtonText: 'OK',
            cancelButtonText: 'キャンセル',
            allowEscapeKey: false,
            allowEnterKey: false,
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                let data = $(this).data('id').split('_');
                var caseId = data[0];
                var flag = data[1];
                $.ajax({
                    url: $('#route').data('cancelCase'),
                    context:$(this),
                    type: "POST",
                    data: {
                        case_id: caseId,
                        flag: flag
                    },
                    beforeSend: function() {
                        $('#loading').css('display', 'block');
                    },
                    complete: function() {
                        $('#loading').css('display', 'none');
                    },
                    success: function (res) { 
                        if (res) {
                            window.location.href = $('#route').data('case060');;
                        }
                    }
                });
            }
        })
    });

    // click 保存 save button to creat new temp_case, contract
    $(document).on('click', '#submit-btn', function (e) {
        if ($('#cas061-form').valid()) {
            $('#loading').css('display', 'block');
        }
    });
});