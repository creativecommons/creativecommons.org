$( document ).ready(function() {
  $('#language-selector-block select').change(function(e) {
    var language = $(e.target).val();
    var deedPath = window.location.pathname.split('/');
    deedPath.pop();
    deedPath.push('deed.' + language);
    window.location.href = deedPath.join('/');
  });
});