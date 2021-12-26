// check radioの見た目変更(iCheck.jsライブラリの設定)
$(function () {
  //iCheck for checkbox and radio inputs
  $('input[type="checkbox"].minimal-blue, input[type="radio"].minimal-blue').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass: 'iradio_minimal-blue'
  })
});

// chosen
$(".chosen-select").chosen({
  no_results_text: "検索結果が存在しません",
  search_contains: true,
});

//datatables
//検索画面用
$(document).ready(function () {
  var table = $('#table1').removeAttr('width').DataTable({
    // 件数切替
    lengthChange: false,
    // 検索
    searching: false,
    // 検索結果件数
    info: true,
    // ソート
    ordering: false,
    // ページング
    paging: true,
    // 横スクロール
    scrollX: true,
    // 横スクロール時、カラム数の固定
    // fixedColumns: {
    //   leftColumns: 3
    // },
    //縦スクロールを行うheight
    scrollY: "700px",
    // 縦スクロール時、件数が足りなければheight自動調整
    scrollCollapse: true,
    // 言語
    language: {
      "sInfo": " _TOTAL_ 件中 _START_ 件から _END_ 件まで表示",
      "oPaginate": {
        "sFirst": "先頭",
        "sPrevious": "前",
        "sNext": "次",
        "sLast": "最終"
      }
    },

  })
});

//SP画面用
$(document).ready(function () {
  var table = $('#tableSP1').removeAttr('width').DataTable({
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
    // 横スクロール時、カラム数の固定
    // fixedColumns: {
    //   leftColumns: 3
    // },
    //縦スクロールを行うheight
    scrollY: "250px",
    // 縦スクロール時、件数が足りなければheight自動調整
    scrollCollapse: true,
    // 言語
    language: {
      "sInfo": " _TOTAL_ 件表示",
      "sInfoEmpty": "_TOTAL_ 件表示",
      "sEmptyTable": '該当するデータがありません。',
      "oPaginate": {
        "sFirst": "先頭",
        "sPrevious": "前",
        "sNext": "次",
        "sLast": "最終"
      }
    },
  })
  var table = $('#tableSP2').removeAttr('width').DataTable({
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
    // 横スクロール時、カラム数の固定
    // fixedColumns: {
    //   leftColumns: 3
    // },
    //縦スクロールを行うheight
    scrollY: "250px",
    // 縦スクロール時、件数が足りなければheight自動調整
    scrollCollapse: true,
    // 言語
    language: {
      "sInfo": " _TOTAL_ 件表示",
      "oPaginate": {
        "sFirst": "先頭",
        "sPrevious": "前",
        "sNext": "次",
        "sLast": "最終"
      }
    },
  })
  var table = $('#tableSP3').removeAttr('width').DataTable({
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
    // 横スクロール時、カラム数の固定
    // fixedColumns: {
    //   leftColumns: 3
    // },
    //縦スクロールを行うheight
    scrollY: "250px",
    // 縦スクロール時、件数が足りなければheight自動調整
    scrollCollapse: true,
    // 言語
    language: {
      "sInfo": " _TOTAL_ 件表示",
      "oPaginate": {
        "sFirst": "先頭",
        "sPrevious": "前",
        "sNext": "次",
        "sLast": "最終"
      }
    },
  })
  var table = $('#tableCheck').removeAttr('width').DataTable({
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
    // 横スクロール時、カラム数の固定
    // fixedColumns: {
    //   leftColumns: 3
    // },
    //縦スクロールを行うheight
    // scrollY: "200px",
    // 縦スクロール時、件数が足りなければheight自動調整
    // scrollCollapse: true,
    // 言語
    language: {
      "sInfo": " _TOTAL_ ケース紐付済",
      "sInfoEmpty": "_TOTAL_ ケース紐付済",
      "sEmptyTable": '該当するデータがありません。',
      "oPaginate": {
        "sFirst": "先頭",
        "sPrevious": "前",
        "sNext": "次",
        "sLast": "最終"
      }
    },
  })
});


// 一覧のチェックボックスをヘッダー部で一括更新
$(function () {
  $('#all').on("click", function () {
    $('.list').prop("checked", $(this).prop("checked"));
  });
});


// スクロールとハイライト
$(document).ready(function () {
  $('#search').click(function () {
    document.getElementById("searchResult").scrollIntoView(true);
    document.getElementById("searchResult").style.backgroundColor = "#ffff8e";
    document.getElementById("result").style.display = "block"
  });
});


// table、行のハイライト
$(document).ready(function () {
  $('.rowCheck').click(function () {
    $(this).parent().parent().toggleClass('checkedRow');
  });
});

// table、行の非表示
$(document).ready(function () {
  $('.rowDelete').click(function () {
    $(this).parent().parent().toggleClass('none');
  });
});


// 次へ
$(document).ready(function () {
  $('.buttonNext').click(function () {
    $('.noneContent').removeClass('none');
    $('.hiddenContent').removeClass('hidden');
    $('.beforeContent').toggleClass('none');
  });
});

$(document).ready(function () {
  $('.buttonNext2').click(function () {
    $('.noneContent2').removeClass('none');
    $('.hiddenContent2').removeClass('hidden');
    $('.beforeContent2').toggleClass('none');
  });
});

$(document).ready(function () {
  $('.buttonNext3').click(function () {
    $('.noneContent3').removeClass('none');
    $('.hiddenContent3').removeClass('hidden');
    $('.beforeContent3').toggleClass('none');
  });
});

// 戻す
$(document).ready(function () {
  $('.buttonBack').click(function () {
    $('.noneContent').toggleClass('none');
    $('.hiddenContent').toggleClass('hidden');
    $('.beforeContent').removeClass('none');
  });
});

$(document).ready(function () {
  $('.buttonBack2').click(function () {
    $('.noneContent2').toggleClass('none');
    $('.hiddenContent2').toggleClass('hidden');
    $('.beforeContent2').removeClass('none');
  });
});

$(document).ready(function () {
  $('.buttonBack3').click(function () {
    $('.noneContent3').toggleClass('none');
    $('.hiddenContent3').toggleClass('hidden');
    $('.beforeContent3').removeClass('none');
  });
});

// ボタン切り替え
$(document).ready(function () {
  // $('.toggle').click(function () {
  //   $(this).parent().children('.btn').toggleClass('none');
  // });
});

// ラジオボタンでの項目表示 / 非表示の切り替え
// 汎用
$(document).ready(function () {
  $('input[id=radio1]').on('ifChecked', function () {
    $('.content2').addClass('none');
    $('.content1').removeClass('none');
  });
  $('input[id=radio2]').on('ifChecked', function () {
    $('.content1').addClass('none');
    $('.content2').removeClass('none');
  });
});

// 権限
$(document).ready(function () {
  $('input[id=NW]').on('ifChecked', function () {
    $('.SYcontent').addClass('none');
    $('.SDcontent').addClass('none');
    $('.RPcontent').addClass('none');
    $('.JAcontent').addClass('none');
    $('.NWcontent').removeClass('none');
  });
  $('input[id=SY]').on('ifChecked', function () {
    $('.NWcontent').addClass('none');
    $('.SDcontent').addClass('none');
    $('.RPcontent').addClass('none');
    $('.JAcontent').addClass('none');
    $('.SYcontent').removeClass('none');
  });
  $('input[id=SD]').on('ifChecked', function () {
    $('.NWcontent').addClass('none');
    $('.SYcontent').addClass('none');
    $('.RPcontent').addClass('none');
    $('.JAcontent').addClass('none');
    $('.SDcontent').removeClass('none');
  });
  $('input[id=RP]').on('ifChecked', function () {
    $('.NWcontent').addClass('none');
    $('.SYcontent').addClass('none');
    $('.SDcontent').addClass('none');
    $('.JAcontent').addClass('none');
    $('.RPcontent').removeClass('none');
  });
  $('input[id=JA]').on('ifChecked', function () {
    $('.NWcontent').addClass('none');
    $('.SYcontent').addClass('none');
    $('.SDcontent').addClass('none');
    $('.RPcontent').addClass('none');
    $('.JAcontent').removeClass('none');
  });
});


// 日付指定
$(function () {
  // 当日
  var today = moment().format('YYYY-MM-DD');
  if (document.getElementById('today')) {
    document.getElementById('today').value = today;
  }

  // 先月1日
  var startlm = moment().subtract(1, 'months').startOf('month').format('YYYY-MM-DD');
  if (document.getElementById('startlastmonth')) {
    document.getElementById('startlastmonth').value = startlm;
  }

  // 先月末
  var endlm = moment().subtract(1, 'months').endOf('month').format('YYYY-MM-DD');
  if (document.getElementById('endlastmonth')) {
    document.getElementById('endlastmonth').value = endlm;
  }

  // 今月末
  var endm = moment().endOf('month').format('YYYY-MM-DD');
  if (document.getElementById('endmonth')) {
    document.getElementById('endmonth').value = endm;
  }

  // 来月末
  var endnm = moment().add(1, 'months').endOf('month').format('YYYY-MM-DD');
  if (document.getElementById('endnextmonth')) {
    document.getElementById('endnextmonth').value = endnm;
  }
});
