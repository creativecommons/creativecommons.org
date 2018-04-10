(function ($) {
  $(function() {

    // Make .usage-considerations collapsibles
    $('.usage-considerations').each(function() {
      var $trigger = $('<a href="#" class="cc-collapsible__trigger"><span><img src="/images/deed/svg/deed_arrow_orange.svg" /></span></a>');

      $trigger.click(function() {
        $(this).parent().toggleClass('collapsed');
        return false;
      });

      $(this)
        .addClass('cc-collapsible collapsed')
        .append($trigger);
    });

    // Language selector
    $('#language-selector-block select').change(function(e) {
      var destination = $(e.target).val();
      var deedPath = window.location.pathname.split('/');
      deedPath.pop();
      deedPath.push(destination);
      window.location.href = deedPath.join('/');
    });

  });
}(jQuery));
