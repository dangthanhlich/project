$(document).ready(function() {
    // set base64 image to form and show next state 
    $('#buttonNext').click(function() {
        $('.sign').toggleClass('derivery');
        $('.sign').toggleClass('stockyard');
        $('#sign_tr_2').val(mamDraw.canvas.toDataURL('image/jpeg'));
        clearCanvas();
    });
    // set base64 image to form and submit
    $('#buttonSubmit').click(function() {
        $('#sign_sy').val(mamDraw.canvas.toDataURL('image/jpeg'));
        $('#cas051-form').submit();
    });
    // init draw canvas
    var mamDraw = [];
    mamDraw.isMouseDown = false;
    mamDraw.position = [];
    mamDraw.position.x = 0;
    mamDraw.position.y = 0;
    mamDraw.position.px = 0;
    mamDraw.position.py = 0;
    //初期設定
    mamDraw.canvas = document.getElementById('drawcanvas');
    mamDraw.canvas.addEventListener('touchstart', onDown);
    mamDraw.canvas.addEventListener('touchmove', onMove);
    mamDraw.canvas.addEventListener('touchend', onUp);
    mamDraw.canvas.addEventListener('mousedown', onMouseDown);
    mamDraw.canvas.addEventListener('mousemove', onMouseMove);
    mamDraw.canvas.addEventListener('mouseup', onMouseUp);
    window.addEventListener('mousemove', StopShake);
    mamDraw.context = mamDraw.canvas.getContext('2d');
    mamDraw.context.strokeStyle = '#000000';
    mamDraw.context.lineWidth = 5;
    mamDraw.context.lineJoin = 'round';
    mamDraw.context.lineCap = 'round';
    clearCanvas();
    $('.clearCanvas').click(clearCanvas);

    function StopShake(event) {
        mamDraw.isMouseDown = false;
        event.stopPropagation();
    }
    function onDown(event) {
        let currentButtonId = $('.sign').hasClass('derivery') ? '#buttonNext' : '#buttonSubmit';
        $(currentButtonId).prop('disabled', false);
        mamDraw.isMouseDown = true;
        mamDraw.position.px = event.touches[0].pageX - event.target.getBoundingClientRect().left - mamGetScrollPosition().x;
        mamDraw.position.py = event.touches[0].pageY - event.target.getBoundingClientRect().top - mamGetScrollPosition().y;
        mamDraw.position.x = mamDraw.position.px;
        mamDraw.position.y = mamDraw.position.py;
        drawLine();
        event.preventDefault();
        event.stopPropagation();
    }
    function onMove(event) {
        if (mamDraw.isMouseDown) {
            mamDraw.position.x = event.touches[0].pageX - event.target.getBoundingClientRect().left - mamGetScrollPosition().x;
            mamDraw.position.y = event.touches[0].pageY - event.target.getBoundingClientRect().top - mamGetScrollPosition().y;
            drawLine();
            mamDraw.position.px = mamDraw.position.x;
            mamDraw.position.py = mamDraw.position.y;
            event.stopPropagation();
        }
    }
    function onUp(event) {
        mamDraw.isMouseDown = false;
        event.stopPropagation();
    }
    function onMouseDown(event) {
        let currentButtonId = $('.sign').hasClass('derivery') ? '#buttonNext' : '#buttonSubmit';
        $(currentButtonId).prop('disabled', false);
        mamDraw.position.px = event.clientX - event.target.getBoundingClientRect().left;
        mamDraw.position.py = event.clientY - event.target.getBoundingClientRect().top;
        mamDraw.position.x = mamDraw.position.px;
        mamDraw.position.y = mamDraw.position.py;
        drawLine();
        mamDraw.isMouseDown = true;
        event.stopPropagation();
    }
    function onMouseMove(event) {
        if (mamDraw.isMouseDown) {
            mamDraw.position.x = event.clientX - event.target.getBoundingClientRect().left;
            mamDraw.position.y = event.clientY - event.target.getBoundingClientRect().top;
            drawLine();
            mamDraw.position.px = mamDraw.position.x;
            mamDraw.position.py = mamDraw.position.y;
            event.stopPropagation();
        }
    }
    function onMouseUp(event) {
        mamDraw.isMouseDown = false;
        event.stopPropagation();
    }
    function drawLine() {
        mamDraw.context.strokeStyle = '#000000';
        mamDraw.context.lineWidth = 5;
        mamDraw.context.lineJoin = 'round';
        mamDraw.context.lineCap = 'round';
        mamDraw.context.beginPath();
        mamDraw.context.moveTo(mamDraw.position.px, mamDraw.position.py);
        mamDraw.context.lineTo(mamDraw.position.x, mamDraw.position.y);
        mamDraw.context.stroke();
    }
    function clearCanvas() {
        let currentButtonId = $('.sign').hasClass('derivery') ? '#buttonNext' : '#buttonSubmit';
        $(currentButtonId).prop('disabled', true);
        mamDraw.context.fillStyle = 'rgb(255,255,255)';
        mamDraw.context.fillRect(
            0, 0,
            mamDraw.canvas.getBoundingClientRect().width,
            mamDraw.canvas.getBoundingClientRect().height
        );
    }
    function mamGetScrollPosition() {
        return {
            'x': document.documentElement.scrollLeft || document.body.scrollLeft,
            'y': document.documentElement.scrollTop || document.body.scrollTop,
        };
    }
});
