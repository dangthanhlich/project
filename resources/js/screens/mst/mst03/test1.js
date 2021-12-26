/**
 * ページ読み込み時の処理
 */
$(function(){
    // サイズ調整
    // $("#sign").attr("width", $(".table").width()-20);
    // $("#sign2").attr("width", $(".table").width()-20);
    // Canvasに描かれているオブジェクトのRGBA配列の値（0 ～ 255）を集計する
    var getRGBASummary = function () {
        var canvas = $("#sign").get(0);
        var image = canvas.getContext("2d").getImageData(0, 0, canvas.width, canvas.height);
        var data = image.data;
        var sum = data.reduce(function(prev, current, i, arr) {
            return prev+current;
        });
        return sum;
    };
    // Canvasの設定
    var setOptions = function () {
        // signaturePad.penColor = $("#color").val();
        // signaturePad.backgroundColor = $("#bcolor").val();
        // signaturePad.minWidth = parseInt($("#weight").val());
        // signaturePad.maxWidth = parseInt($("#weight").val());
        signaturePad.clear();
        console.log(signaturePad);

        // 初期値を保持する
        var sum = getRGBASummary();
        $("#sign").data("RGBASummary", sum);
    };
    signaturePad = new SignaturePad($("#sign").get(0));
    setOptions();
    // $("#color, #bcolor").on("change", function() {
    //     setOptions();
    // });
    // $("#weight").on("keyup", function() {
    //     setOptions();
    // });
    $("#clearButton").on("click", function() {
        $("#color").val("black");
        $("#bcolor").val("white");
        // $("#weight").val("3");
        setOptions();
    });
    $("#saveButton").on("click", function() {
        var signaturePad2 = new SignaturePad($("#sign2").get(0));
        var url = signaturePad.toDataURL("image/png");
        signaturePad2.fromDataURL(url);
    });
    // データチェックボタンクリック
    $("#checkButton").on("click", function() {
        var org = $("#sign").data("RGBASummary");
        var sum = getRGBASummary();
        if (sum != org) {
            alert("何かしら描かれてるよ！");
            window.console&& console.log(sum + " / " + org);
        } else {
            alert("何も描かれてないよ！");
            window.console&& console.log(sum + " / " + org);
        }
    });
});
