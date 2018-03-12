$( document ).ready(function() {
  $('#language-selector-block select').change(function(e) {
    var language = $(e.target).val();
    var currentPath = window.location.pathname;
    var newPath = currentPath;
    if (currentPath.substr(-3, 1) == '.') {
      newPath = currentPath.substr(0, currentPath.length -3);
    }
    newPath = newPath + '.' + language;
    if (!(currentPath == '/licenses/by/4.0/deed' && newPath == '/licenses/by/4.0/deed.en')) {
      window.location.href = newPath;
    }
  });
});