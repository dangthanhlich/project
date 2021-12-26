/**
 * Common Jquery validate
 */

// Form track changes
$.fn.extend({
    trackChanges: function() {
        $(this).data('serialize', $(this).serialize());
    },
    isChanged: function() {
        return $(this).serialize() != $(this).data('serialize');
    },
    preventDoubleSubmission: function () {
        $(this).on('submit', function (e) {
            var $form = $(this);

            if ($form.data('submitted') === true) {
                // Previously submitted - don't submit again
                e.preventDefault();
            } else {
                // Mark it so that the next submit can be ignored
                // ADDED requirement that form be valid
                if($form.valid()) {
                    $form.data('submitted', true);
                }
            }
        });
        // Keep chainability
        return this;
    }
});

// Change timepicker default options
if($.fn.timepicker) {
    $.extend($.fn.timepicker.defaults,{
        showMeridian: false,
        defaultTime: false
    });
}

// Change validator messages method
$.extend(jQuery.validator, {
    messages: {
        required: function (p, e) {
            return $.validator.format("{0}は必須項目です。", [$(e).data('label')]);
        },
        requiredDefault: $.validator.format("{0}は必須項目です。"),
        email: $.validator.format("メールアドレスを正しく入力してください。"),
        url: $.validator.format("URLを正しく入力してください。"),
        date: function (p, e) {
            return $.validator.format("{0}は日付を正しく入力してください。", [$(e).data('label')]);
        },
        datetime: $.validator.format("{0}は日付を正しく入力してください。"),
        date_time: $.validator.format("{0}は日時を正しく入力してください。"),
        number: function (p, e) {
            return $.validator.format("{0}は半角数字で入力してください。", [$(e).data('label')])
        },
        digits: $.validator.format("半角数字で入力してください"),
        equalTo: $.validator.format("確認用の{0}が間違っています。"),
        equalToExt: $.validator.format("確認用の{1}が間違っています。"),
        maxlength: function (p, e) {
            return $.validator.format("{0}は「{1}」文字以下で入力してください。（現在{2}文字）", [$(e).data('label'), p, $(e).val().length]);
        },
        minlength: function (p, e) {
            return $.validator.format("{0}は「{1}」文字以上で入力してください。（現在{2}文字）", [$(e).data('label'), p, $(e).val().length]);
        },
        exactlength: $.validator.format("{0}は「{1}」文字で入力してください。（現在{2}文字）"),
        rangelength: $.validator.format("{0}桁以上、{1}桁以下で入力してください。"),
        passwordrange: $.validator.format("半角英数字記号で8～20文字で入力してください。"),
        admin_passwordrange: $.validator.format("半角英数字記号で8～25文字で入力してください。"),
        passwordsameTouserid: $.validator.format("パスワードには会員IDと同じ値は使用できません。"),
        passwordisAlphanum: $.validator.format("パスワードには半角数字のみ、または半角英字のみの値は使用できません。"),
        passwordInvalid: $.validator.format("半角英字および以下の文字以外はご使用いただけません。(「#」、「$」、「%」、「(」、「)」、「*」、「+」、「-」、「.」、「/」、「:」、「;」、「?」、「@」、「[」、「]」、「_ 」、「{」、「}」、「~」)"),
        phone: $.validator.format("電話番号を正しく入力してください。"),
        latin: $.validator.format("半角英数で入力してください。"),
        character_invalid: $.validator.format("以下の文字はご使用いただけません。(「#」、「$」、「%」、「(」、「)」、「*」、「+」、「-」、「.」、「/」、「:」、「;」、「?」、「@」、「[」、「]」、「_ 」、「{」、「}」、「~」)"),
        extension: $.validator.format("ファイル形式が誤っています。{0}を選択してください。"),
        filesize: $.validator.format("ファイルのサイズ制限{0}MBを超えています。"),
        mail_max_length: $.validator.format("メールアドレスは「75」文字以下で入力してください。"),
        mail_min_length: $.validator.format("メールアドレスは「6」文字以上で入力してください。"),
        electric_mail_max_length: $.validator.format("メールアドレスは「75」文字以下で入力してください。"),
        electric_mail_min_length: $.validator.format("メールアドレスは「6」文字以上で入力してください。"),
        mail_required: $.validator.format("必須項目です。"),
        futureDate: $.validator.format("未来日を入力してください。"),
        screen_range: $.validator.format("半角英数字記号で6～75文字で入力してください。"),
        mail_valid: $.validator.format("メールアドレスを正しく入力してください。"),
        check2Byte: $.validator.format("全角で入力してください。"),
        fixedFileSize: $.validator.format("ファイルのサイズ制限10MBを超えています。"),
        checkKanji: $.validator.format("全角で入力してください。"),
        checkCharacterlatin: function (p, e) {
            return $.validator.format("{0}は半角英数で入力してください。", [$(e).data('label')]);
        },
        checkAlphabet: $.validator.format("半角英字で入力してください。"),
        checkKatakana: function (p, e) {
            return $.validator.format("{0}は全角カナで入力してください。", [$(e).data('label')]);
        },
        check2ByteHfS: $.validator.format("全角で入力してください。"),
        checkNumeric: function (p, e) {
            return $.validator.format("{0}は半角数字で入力してください。", [$(e).data('label')]);
        },
        checkKatakana1Byte2Byte: $.validator.format("﻿カナ、「）」「（」「.」「-」「/」、全角スペースで入力してください。"),
        checkValidEmailRFC: $.validator.format("メールアドレスを正しく入力してください。<br/>『.』が連続して使われている、＠の直前に『.』が入っているなど、使用できないメールアドレスがございます。別のメールアドレスをご利用ください。"),
        mail_valid_RFC: $.validator.format("メールアドレスを正しく入力してください。"),
        rangeEmail: $.validator.format("メールアドレスとは半角英数で入力してください。"),
        greaterThanDate: $.validator.format("{0}は{1}以降の日時を選択してください。"),
        greaterThanDateUpgrade: $.validator.format("{0}は{1}以降の日時を選択してください。"),
        lessThanDate: $.validator.format("{0}は{1}以降の日時を選択してください。"),
        checkTel: $.validator.format("{0}は電話(FAX)番号を正しく入力してください。"),
        checkPostCode: $.validator.format("{0}は半角数字で入力してください。"),
        checkFormatPostCode: $.validator.format("{0}は郵便番号を正しく入力してください。"),
        checkKatakana2ByteAndCharacter: $.validator.format("{0}は全角カナまたは\"・\"で入力してください。"),
        passwordEqualTo: $.validator.format("パスワードと確認用パスワードが一致しません。"),
        check1ByteSpecialChars: $.validator.format("{0}では半角英字および以下の文字以外はご使用いただけません。(「#」、「$」、「%」、「(」、「)」、「*」、「+」、「-」、「.」、「/」、「:」、「;」、「?」、「@」、「[」、「]」、「_ 」、「{」、「}」、「~」)。"),
        checkData: $.validator.format("ポート数の合計が一致しませんでした。(合計ポート数:{0} 戸数:{1})"),
        check1Byte: $.validator.format("{0}は半角数字で入力してください。"),
        checkKatakana2Byte: $.validator.format("{0}は全角カナまたは\"・\"で入力してください。"),
        digitsCustom: $.validator.format("{0}は半角数字で入力してください。"),
        date_month: $.validator.format("{0}は年月を正しく入力してください。"),
        existDataInTable: $.validator.format("選択された工事番号はすでに登録されています。"),
        portNotEqual: $.validator.format("未割当のポートが存在します。"),
        closingMonth: $.validator.format("{0}は締月の日付を入力してください。"),
        limitTousu: $.validator.format("棟情報エリアの表示可能数は50件です。超過分の棟情報は別途作成を行なってください。"),
        limitKosu: $.validator.format("新料金を選択された場合、戸数は2以上を入力してください。"),
        checkExistCons: $.validator.format("入力された工事番号はすでに存在しません。"),
        stepNotContinuous: $.validator.format("ステップは間を空けずに設定してください。"),
        stepNotStartAtOne: $.validator.format("ステップは必ず１番目から設定してください。"),
        numberOfInstalledUnitsMismatch: $.validator.format("導入戸数が一致しません。"),
        accept: $.validator.format("ファイル形式が誤っています。{0}を選択してください。"),
        checkValueList: function (p, e) {
            return $.validator.format("入力された{0}は正しくありません。", [$(e).data('label')]);
        },
    }
});

$.validator.setDefaults({
    errorClass: 'error-message',
    errorElement: 'div',
    // add default behaviour for on focus out
    onfocusout: function(element) {
        this.element(element);
    }
});
//=================================================//
// Override check length method for compatibility with PHP
$.validator.methods.minlength = function(value, element, param) {
    var length = $.isArray( value ) ? value.length : customGetLength( value, element );
    return this.optional( element ) || length >= param;
};

$.validator.methods.maxlength = function(value, element, param) {
    var length = $.isArray( value ) ? value.length : customGetLength( value, element );
    return this.optional( element ) || length <= param;
};

$.validator.methods.exactlength = function(value, element, param) {
    var length = $.isArray( value ) ? value.length : customGetLength( value, element );
    return this.optional( element ) || length === param;
};

function customGetLength(value, element) {
    if(element){
        switch ( element.nodeName.toLowerCase() ) {
            case "select":
                return $( "option:selected", element ).length;
            case "input":
                if ( checkable( element ) ) {
                    return this.findByName( element.name ).filter( ":checked" ).length;
                }
        }
    }
    // Look for any "\n" occurences
    var matches = value.match(/\n/g);
    // Duplicate count for break line (for matching with PHP)
    var addLength = matches ? matches.length : 0;
    return value.length + addLength;
}

function checkable(element) {
    return (/radio|checkbox/i).test(element.type);
}
//=================================================//

var lastLimit = new Date('2200/12/31');
var firstLimit = new Date('1700/01/01');
$.validator.methods.date = function(value, element, param) {
    var inputDate = new Date(value);
    return  value=='' || moment(value, 'YYYY/MM/DD', true).isValid() && ((firstLimit <= inputDate) && (inputDate <= lastLimit));
};

$.validator.addMethod("datetime", function(value, element, params) {
    var inputDate = new Date(value);
    return  value=='' || (moment(value, 'YYYY/MM/DD H:mm:ss',true).isValid() && ((firstLimit <= inputDate) && (inputDate <= lastLimit))) || (moment(value, 'YYYY/MM/DD H:mm',true).isValid() && ((firstLimit <= inputDate) && (inputDate <= lastLimit))) || (moment(value, 'YYYY/MM/DD',true).isValid() && ((firstLimit <= inputDate) && (inputDate <= lastLimit)));
});

$.validator.addMethod("date_time", function(value, element, params) {
    return  value=='' || moment(value, 'YYYY/MM/DD H:mm:ss',true).isValid() || moment(value, 'YYYY/MM/DD H:mm',true).isValid();
});

$.validator.addMethod("date_month", function(value, element, params) {
    return  value=='' || moment(value, 'YYYY/MM',true).isValid() || moment(value, 'YYYY/MM',true).isValid();
});

$.validator.addMethod("futureDate", function(value, element, params) {
    return value.length > 0 && moment().startOf('date').isSameOrBefore(moment(value, 'YYYY/MM/DD'));
});

$.validator.addMethod( "passwordrange", function(value, element, params) {
    return this.optional(element) || /^[0-9a-zA-Z\#\$\%\(\)\*\+\-\.\/\:\;\?\@\[\]\_\{\}\~]{8,20}$/i.test(value);
});

$.validator.addMethod( "admin_passwordrange", function(value, element, params) {
    return this.optional(element) || /^[0-9a-zA-Z\#\$\%\(\)\*\+\-\.\/\:\;\?\@\[\]\_\{\}\~]{8,25}$/i.test(value);
});

$.validator.addMethod( "screen_range", function(value, element, params) {
    return this.optional(element) || /^[0-9a-zA-Z\#\$\%\(\)\*\+\-\.\/\:\;\?\@\[\]\_\{\}\~]{6,75}$/i.test(value);
});

$.validator.addMethod("latin", function(value, element) {
    return this.optional(element) || /^[a-zA-Z0-9~`!@#$%^&*()-_=+<>?,./:;"'{}]*$/.test(value);
});

$.validator.addMethod("mail_valid", function(value, element, dependent) {
    if(($(dependent).val() !='' && value == '') || ($(dependent).val() =='' && value != '')) {
        return false;
    }
    var email = $(dependent).val().concat('@');
    email = email.concat(value);
    return this.optional(element) || /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/i.test(email);
});

$.validator.addMethod("character_invalid", function(value, element) {
    return this.optional(element) || /^[a-zA-Z0-9`!^&=<>,"']*$/.test(value);
});

$.validator.addMethod("passwordsameTouserid", function(value, element, params) {
    // bind to the blur event of the target in order to revalidate whenever the target field is updated
    // TODO find a way to bind the event just once, avoiding the unbind-rebind overhead
    var target = $(params);
    if(this.settings.onfocusout){
        target.unbind(".validate-equalTo").bind("blur.validate-equalTo", function() {
            $(element).valid();
        });
    }
    return !(value === target.val());
});

$.validator.addMethod( "passwordisAlphanum", function(value, element, params) {
    if ( this.optional(element) ) {
        return "dependency-mismatch";
    }
    // accept only spaces, digits and dashes
    return !(/^[0-9]*$/.test(value) || /^[a-zA-Z]*$/.test(value));
});

$.validator.addMethod( "passwordInvalid", function(value, element, params) {
    return this.optional(element) || /^[a-zA-Z0-9~#$%\*+\-_\[\]\\.;/{}\\:\()?@]*$/.test(value);
});

//custom validation method for file size
$.validator.addMethod("filesize", function(value, element, param) {
    return this.optional(element) || (element.files[0].size <= param *1024*1024);
});

$.validator.addMethod("fixedFileSize", function(value, element, param) {
    return value ? (this.optional(element) || (element.files[0].size <= 10 *1024*1024)) : true;
});

$.validator.addMethod("check2Byte", function(value, element) {
    //return ! value.match(/^[^\u3000-\u303f\u3040-\u309f\u30a0-\u30ff\uff00-\uff9f\u4e00-\u9faf\u3400-\u4dbf]+$/);
    if (value.length > 0)
        return((value.match(/^[^\x01-\x7E\xA1-\xDF]+$/)) ? ((value.match(/^[ｱ-ﾝﾞﾟｧ-ｫｬ-ｮｰ｡｢｣､]+$/)) ? false : true) : false);
    else
        return true;
});

$.validator.addMethod("check2ByteHfS", function(value, element) {
    for(var i=0; i<value.length; i++) {
        var unicode = value.charCodeAt(i);
        if(unicode>=0xff61 && unicode<=0xff9f){ //hankaku kana
            return false;
        } else if ((unicode >= 0x4e00 && unicode <= 0x9fcf) || // CJK統合漢字
            (unicode >= 0x3400 && unicode <= 0x4dbf) || // CJK統合漢字拡張A
            (unicode >= 0x20000 && unicode <= 0x2a6df) || // CJK統合漢字拡張B
            (unicode >= 0xf900 && unicode <= 0xfadf) || // CJK互換漢字
            (unicode >= 0x2f800 && unicode <= 0x2fa1f)||
            (unicode >=0x30a0 && unicode<=0x30ff) ||  //check kana 2 byte.
            (unicode>=0x3040 && unicode<=0x309f) || //check hiragana 2 byte.
            (unicode ==0x0020) || //space 1 byte
            (unicode ==0x3000) || //space 2 byte
            (unicode>=0xff00 && unicode<=0xfff0) //alphabet 2 byte
        ){

        } else {
            return false;
        }
    }
    return true;
});

$.validator.addMethod("rangeEmail", function(value, element, param) {
    //!#$%&'*+-/=?^_`{|}~.
    return this.optional(element) || /^[0-9a-zA-Z\#\!\$\%\(\)\*\+\-\.\/\:\;\?\'\=\`\|\&\@\^\[\]\_\{\}\~]{6,75}$/i.test(value);
});

$.validator.addMethod("checkKanji", function(value, element) {
    for(var i=0; i<value.length; i++) {
        var unicode = value.charCodeAt(i);
        if ((unicode >= 0x4e00 && unicode <= 0x9fcf) || // CJK統合漢字
            (unicode >= 0x3400 && unicode <= 0x4dbf) || // CJK統合漢字拡張A
            (unicode >= 0x20000 && unicode <= 0x2a6df) || // CJK統合漢字拡張B
            (unicode >= 0xf900 && unicode <= 0xfadf) || // CJK互換漢字
            (unicode >= 0x2f800 && unicode <= 0x2fa1f)||
            (unicode >=0x30a0 && unicode<=0x30ff) ||  //check kana 2 byte.
            (unicode>=0x3040 && unicode<=0x309f) //check hiragana 2 byte.
        ){

        } else {
            return false;
        }
    }
    return true;
});

$.validator.addMethod("checkKatakana", function(value, element) {
    for(var i=0; i<value.length; i++) {
        var unicode = value.charCodeAt(i);
        if ((unicode>=0x30a0 && unicode<=0x30ff) ||
            (unicode ==0x0020) || //space 1 byte
            (unicode ==0x3000)
        ){

        } else {
            return false;
        }
    }
    return true;
});

$.validator.addMethod("checkKatakanaV2", function(value, element) {
    for(var i=0; i<value.length; i++) {
        var unicode = value.charCodeAt(i);
        if ((unicode>=0x30a0 && unicode<=0x30ff) ||
            (unicode ==0x0020) || //space 1 byte
            (unicode ==0x3000) ||
            (unicode == 65288) || (unicode == 65289) // 2 byte - char: （）
        ){

        } else {
            return false;
        }
    }
    return true;
});

$.validator.addMethod("checkKatakana1Byte2Byte", function(value, element) {
    var result = true;
    if(value.length > 0){
        result = value.match(/^[\uFF65-\uFF9F\u30A0-\u30FF.\)\(\/\-\　]+$/) ? true : false;
    }
    return result;
});

$.validator.addMethod("checkKatakana2ByteAndCharacter", function(value, element) {
    var result = true;
    if(value.length > 0){
        result = value.match(/^[\u30A0-\u30FF]+$/) ? true : false;
    }
    return result;
});

$.validator.addMethod("checkCharacterlatin", function(value, element) {
    return this.optional(element) || /^[a-zA-Z0-9]*$/.test(value);
});

$.validator.addMethod("checkAlphabet", function(value, element) {
    return this.optional(element) || /^[a-zA-Z]*$/.test(value);
});

$.validator.addMethod("checkNumeric", function(value, element) {
    return this.optional(element) || /^[0-9]*$/.test(value);
});

$.validator.addMethod('digitsCustom', function(value, element) {
    return this.optional(element) || /^[0-9-]*$/.test(value);
});

$.validator.addMethod("checkValidEmailRFC", function(value, element) {
    var matchRules = new RegExp(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
    var latinRule = /^[a-zA-Z0-9~`!@#$%^&*()-_=+<>?,./:;"'{}]*$/.test(value);
    return this.optional(element) || (matchRules.test(value) && latinRule);
});

$.validator.addMethod("mail_valid_RFC", function(value, element, dependent ) {
    if(($(dependent).val() !='' && value == '')||($(dependent).val() =='' && value != '')){
        return false;
    }
    var email = $(dependent).val().concat('@');
    email = email.concat(value);
    var matchRules = new RegExp(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
    var latinRule = /^[a-zA-Z0-9~`!@#$%^&*()-_=+<>?,./:;"'{}]*$/.test(email);
    return this.optional(element) || (matchRules.test(email) && latinRule);
});

$.validator.addMethod("greaterThanDate",
    function(value, element, params) {
        if($(params).val().length > 0 && value.length > 0){
            if (!/Invalid|NaN/.test(new Date(value))) {
                if(new Date(value) <= new Date($(params).val())){
                    if ($(params).hasClass('error-message')) {
                        $(params).removeClass('error-message');
                        $(params).next().remove();
                    }
                }
                return new Date(value) <= new Date($(params).val());
            }

            return isNaN(value) && isNaN($(params).val())
                || (Number(value) > Number($(params).val()));
        } else {
            return true;
        }
});

$.validator.addMethod("greaterThanDateUpgrade",
    function(value, element, params) {
        if($(params).val().length > 0 && value.length > 0){
            if (moment(value, 'YYYY/MM/DD', true).isValid() && moment($(params).val(), 'YYYY/MM/DD', true).isValid()) {
                if(new Date(value) <= new Date($(params).val())){
                    if ($(params).hasClass('error-message')) {
                        $(params).removeClass('error-message');
                        $(params).next().remove();
                    }
                }
                return new Date(value) <= new Date($(params).val());
            }
        }
        return true;
    });

$.validator.addMethod("lessThanDate",
    function(value, element, params) {
        if($(params).val().length > 0 && value.length > 0){
            if (moment(value, 'YYYY/MM/DD', true).isValid() && moment($(params).val(), 'YYYY/MM/DD', true).isValid()) {
                if(new Date(value) >= new Date($(params).val())){
                    if ($(params).hasClass('error-message')) {
                        $(params).removeClass('error-message');
                        $(params).next().remove();
                    }
                }
                return new Date(value) >= new Date($(params).val());
            }
        }
        return true;
});

/**
 * jQuery Validation custom rule,
 * check format xxx-xxxx
 */
$.validator.addMethod('checkFormatPostCode', function(value, element) {
    var form      = $(element).form().attr("id");
    var postcode1 = $('#postcode-1').val();
    var postcode2 = $('#postcode-2').val();
    $("#postcode-1").change(function() {
        $("form").validate().element("#postcode-2");
    });
    $("#postcode-2").change(function() {
        $("form").validate().element("#postcode-1");
    });
    if(($(element).attr("id") == "postcode-1") && (postcode1 == '' && postcode2 != '')){
        return false;
    }
    if(($(element).attr("id") == "postcode-2") && (postcode1 != '' && postcode2 == '')){
        return false;
    }
    return true;
});

/**
 * jQuery Validation custom rule,
 * only accept 1 byte for post code
 */
$.validator.addMethod('checkPostCode', function(value, element, param) {
    if(param && !$('form').hasClass('export')){
        return this.optional(element) || /^[0-9]*$/.test(value);
    }
    return true;
});

$.validator.addMethod('checkInvoiceZipCode', function(value, element, param) {
    return this.optional(element) || /^([0-9]{3}-[0-9]{4})*$/.test(value);
});

/**
 * jQuery Validation custom rule,
 * only accept 1 byte for post code
 */
$.validator.addMethod('maxSearchLength', function(value, element, param) {
    if(param && !$('form').hasClass('export')){
        var length = $.isArray( value ) ? value.length : customGetLength( value, element );
        return this.optional(element) || length <= param;
    }
    return true;
});

/**
 * jQuery Validation custom rule,
 * only accept 1 byte and '-' for tel number
 */
$.validator.addMethod('checkTel', function(value, element, param) {
    if(param && !$('#frmDho01').hasClass('export')){
        return this.optional(element) || /^[0-9-]*$/.test(value);
    }
    return true;
});

/**
 * jQuery Validation custom rule,
 * only accept 1 byte and 20 types of character
 */
$.validator.addMethod('check1ByteSpecialChars', function(value, element) {
    return this.optional(element) || /^[a-zA-Z0-9#$%()*+\-./:;?@\[\]_{}~]*$/.test(value);
});

//custom validate.
function isInt(value) {
    return !isNaN(value) && (function(x) { return (x | 0) === x; })(parseFloat(value));
}
$.validator.addMethod('checkMaxlength', function(val, element) {
    return (val.length > 0 && val.length > $(element).data('max-length')) ? false : true;
}, function(params, element) {
    return $.validator.messages.maxlength([$(element).data('name'), $(element).data('max-length'), $(element).val().length]);
});

$.validator.addMethod('checkNumber', function(value, element) {
    return this.optional(element) || /^[0-9]*$/.test(value);
}, function(params, element) {
    return $.validator.messages.check1Byte($(element).data('name'));
});

$.validator.addMethod('checkDecimalFormat', function(val, element) {
    if(val.length === 0)
        return true;
    return !isNaN(val);
}, function(params, element) {
    return $.validator.messages.digits($(element).data('name'));
});

$.validator.addMethod('checkRequired', function(val, element) {
    return !(val === null || val === undefined || val.length === 0);
}, function(params, element) {
    return $.validator.messages.required($(element).data('name'));
});

function checkKatakana2Byte(value, element) {
    var result = true;
    if(value.length > 0){
        result = value.match(/^[・\u30a0-\u30ff　]*$/) ? true : false;
    }
    return result;
}

$.validator.addMethod("checkKatakana2ByteAndCharacter",
    function(value, element) {
        return checkKatakana2Byte(value, element);
    }
);


$.validator.addMethod("checkKatakana2ByteAndCharacterWithMessage",
    function(value, element) {
        return checkKatakana2Byte(value, element);
    },
    function(params, element) {
        return $.validator.messages.checkKatakana2Byte($(element).data('name'));
    }
);

$.validator.addMethod("checkDatetime", function(value, element, params) {
    var inputDate = new Date(value);
    return  value=='' || (moment(value, 'YYYY/MM/DD H:mm:ss',true).isValid() && ((firstLimit <= inputDate) && (inputDate <= lastLimit))) || (moment(value, 'YYYY/MM/DD H:mm',true).isValid() && ((firstLimit <= inputDate) && (inputDate <= lastLimit))) || (moment(value, 'YYYY/MM/DD',true).isValid() && ((firstLimit <= inputDate) && (inputDate <= lastLimit)));
},function(params, element) {
    return $.validator.messages.datetime($(element).data('name'));
});

$.validator.addMethod("checkDatetime2", function(value, element, params) {
    var inputDate = new Date(value);
    return  value=='' || (moment(value, 'YYYY/MM/DD H:mm:ss',true).isValid() && ((firstLimit <= inputDate) && (inputDate <= lastLimit))) || (moment(value, 'YYYY/MM/DD H:mm',true).isValid() && ((firstLimit <= inputDate) && (inputDate <= lastLimit))) || (moment(value, 'YYYY/MM/DD',true).isValid() && ((firstLimit <= inputDate) && (inputDate <= lastLimit)));
},function(params, element) {
    return $.validator.messages.date_time($(element).data('name'));
});

$.validator.addMethod("closingMonth", function (value, element, params) {
    var date = '';
    if(value != '') {
        date = value.split('/');
        date = date[0] + '/' + date[1];
    }
    var monthlyBlance = $('#monthly_balance').val();
    if(date != '' && date != monthlyBlance) {
        return false;
    }
    return true;
});

$.validator.addMethod("limitTousu", function(value, element, params) {
    return value > 50 ? false : true;
});

$.validator.addMethod("limitKosu", function(value, element, params) {
    // 新料金の場合だけチェック必須
    return (($('#price-type').val() === '1')? (value > 1): true);
});

$.validator.addMethod("checkValueList", function(value, element, params) {
    if (element.type === 'checkbox') {
        var div = $('input[name="'+element.getAttribute('name')+'"]:checked');
        var checked = true;
        div.map(function() {
            if (!params.includes(Number($(this).val()))) {
                checked = false;
            }
            return $(this).val();
        }).get();
        return checked;
    }
    return this.optional(element) || params.includes(Number(value));
});

$.validator.addClassRules({
    number: {checkNumber: true},
    decimal: {checkDecimalFormat: true},
    max_length: {checkMaxlength: true},
    required: {checkRequired: true},
    kana: {checkKatakana2ByteAndCharacterWithMessage: true},
    checkDatetime: {checkDatetime: true},
    checkDatetime2: {checkDatetime2: true},
});


$(function() {
    $("ul.pagination").find("li.active > a").bind('click', false);
});

$(function() {
    $('.list-group a').bind('click', function (e) {
        var link = $(this).attr('href');
        var pos = $('.bb-sidebar').scrollTop();
        var page = $(document).scrollTop();
        sessionStorage.setItem('sidebarPosition', pos);
        sessionStorage.setItem('pagePosition', page);
    });
});

$(document).ready(function(){
    var sidebarPosition = sessionStorage.getItem('sidebarPosition');
    var page = sessionStorage.getItem('pagePosition');
    if(parseInt(page) > 110){
        $('html, body').scrollTop(110);
    } else {
        $('html, body').scrollTop(page);
    }
    setTimeout(function (){
        $('.bb-sidebar').scrollTop(parseInt(sidebarPosition));
    }, 200);
});

function numberWithCommas(number) {
    var parts = number.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}
