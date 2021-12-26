$(document).ready(function() {
    $('#all,.list').click(function() {
      if ($('.list:checked').length > 0) {
          $('#btn-download').prop('disabled', false);
      } else {
          $('#btn-download').prop('disabled', true);
      }
    });
});