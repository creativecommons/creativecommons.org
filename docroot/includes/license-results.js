function swapbutton(buttonurl) {

  // update the text area
  e = document.getElementById('codetocopy');
  var codetocopy = e.value;
  var newcodetocopy = codetocopy.replace(/src=".*?"/, 'src="'+buttonurl+'"');
  e.value = newcodetocopy;

  // update the preview
  p = document.getElementById('results-preview');
  if (p != null) 
      p.innerHTML = e.value;
}
