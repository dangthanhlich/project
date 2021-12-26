/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************!*\
  !*** ./resources/js/custom.js ***!
  \********************************/
$(function () {
  /**
   * main common object
   * include all common function and variables
   */
  var _common = {}; // bind to window variable, make it usable everywhere

  $.extend(window, {
    _common: _common
  });
  /**
   * show loading
   */

  function showLoading() {
    $('#loading').css('display', 'block');
  }

  _common.showLoading = showLoading;
  /**
   * hide loading
   */

  function hideLoading() {
    $('#loading').css('display', 'none');
  }

  _common.hideLoading = hideLoading;
});
$(document).ready(function () {
  /**
   * Clear form search
   */
  $('.btn-clear-search').click(function () {
    var closestForm = $(this).closest('form');
    var radioElement = closestForm.find('.i-radio');
    var dateElement = closestForm.find('.datepicker');
    closestForm.trigger('reset');
    closestForm.find('input:text, input:password, input:file, input[type="month"], textarea').val('');
    closestForm.find('.i-radio, .i-checkbox').closest('div').removeClass('checked');
    closestForm.find('.i-radio, .i-checkbox').removeAttr('checked');
    closestForm.find('select').each(function () {
      var optVal = $(this).find('option:first').val();
      $(this).val(optVal);
      $(this).trigger('change');
    });
    closestForm.find('.chosen-select').val('').trigger('chosen:updated'); // default checked for radio input

    if (radioElement.closest('.check').data('default')) {
      radioElement.each(function () {
        if ($(this).val() == radioElement.closest('.check').data('default')) {
          $(this).attr('checked', true);
          $(this).closest('div').addClass('checked');
          $(this).trigger('change');
        }
      });
    } // default data for date input


    dateElement.each(function () {
      if ($(this).data('default') && $(this).data('is-default')) {
        $(this).val($(this).data('default'));
      } else {
        $(this).val('');
      }
    });
    $('form').valid();
    $.ajax({
      method: 'get',
      url: $(this).data('url'),
      data: {
        'screen': $(this).data('screen')
      },
      dataType: 'json',
      success: function success(response) {}
    });
  }); // Change paginate menu

  $('.paginate_menu').on('change', function () {
    var limit = $(this).val();
    var url = new Url(window.location.href); // Set limit

    delete url.query['page'];
    url.query.limit = limit;
    window.location.href = url.toString();
  }); // Change paginate page

  $('.pagination li a').on('click', function (e) {
    var $this = $(this);
    var href = $this.attr('href'); // Get current href attribute

    if (href) {
      var url = new Url(href); // Get limit

      var limit = $('.paginate_menu').val();

      if (limit) {
        url.query.limit = limit;
      }

      window.location.href = url.toString();
      e.preventDefault();
    }
  });
  $('.custom-data-table').DataTable({
    paging: false,
    searching: false,
    bInfo: false,
    ordering: false,
    autoWidth: false,
    language: {
      sEmptyTable: '該当するデータがありません。'
    }
  });
  $('.datepicker').datepicker({
    autoHide: true,
    language: 'ja-JP',
    format: 'yyyy/mm/dd',
    date: new Date()
  }).on('change', function () {
    $(this).valid();
  });
  $(window).scroll(function () {
    if ($(window).scrollTop() + $(window).height() == $(document).height()) {
      if ($('#pagination-box').length > 0) {
        $('#pagination-box').removeClass('pagination-border-box');
      }
    } else {
      $('#pagination-box').addClass('pagination-border-box');
    }
  });
  var changeTrackingForm = $('.form-add-edit');
  setTimeout(function () {
    if (changeTrackingForm.length > 0) {
      changeTrackingForm.data('originalValue', changeTrackingForm.serialize()); // On load save form current state
    }
  }, 150);
  setTimeout(function () {
    if (changeTrackingForm.length > 0) {
      var backConfirmation = function backConfirmation(e) {
        // stop redirect
        e.preventDefault();
        e.returnValue = '';
        var el = e.target;
        var formChange = false;

        if (changeTrackingForm.data('originalValue') !== changeTrackingForm.serialize()) {
          formChange = true;
        } // form data has't been changed


        if (!formChange) {
          window.SAFE_LEAVE = true;
          location.href = e.currentTarget.href;
        } // form data changed


        if (formChange) {
          Swal.fire({
            text: "編集中の情報が破棄されますがよろしいですか？",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#51bcda',
            cancelButtonColor: '#fbc658',
            confirmButtonText: 'OK',
            cancelButtonText: 'キャンセル',
            allowEscapeKey: false,
            allowEnterKey: false,
            allowOutsideClick: false
          }).then(function (result) {
            if (result.isConfirmed) {
              window.SAFE_LEAVE = true;
              location.href = e.currentTarget.href;
            } else {
              $(window).bind('beforeunload');
            }
          });
        }
      }; // backConfirmation


      $('body').delegate('a:not(".dropdown-toggle, .not-tracking"), button:not(".btn-submit, .not-tracking")', 'click', backConfirmation);
      $('body').on('.btn-submit', 'click', function () {// window.SAFE_LEAVE = true;
      }); // setup default, native browser dialog

      $(window).on('beforeunload', function () {
        if (!window.SAFE_LEAVE && changeTrackingForm.data('originalValue') !== changeTrackingForm.serialize()) {
          return '編集中の情報は保存されませんが、よろしいですか？'; // show confirm dialog (IE11)
        } // else do nothing, process with normal operation

      });
    }
  }, 800);
});
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  },
  cache: false
});
/******/ })()
;