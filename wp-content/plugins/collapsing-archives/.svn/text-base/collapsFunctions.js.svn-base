/*  Collapse Functions, version 1.7
 *
 *--------------------------------------------------------------------------*/
String.prototype.trim = function() {
  return this.replace(/^\s+|\s+$/g,"");
}

function createCookie(name,value,days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
  } else {
    var expires = "";
  }
  document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') {
      c = c.substring(1,c.length);
    }
    if (c.indexOf(nameEQ) == 0) {
      return c.substring(nameEQ.length,c.length);
    }
  }
  return null;
}

function eraseCookie(name) {
  createCookie(name,"",-1);
}
function collapsAddLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}
function autoExpandCollapse(collapsClass) {
  var cookies = document.cookie.split(';');
  var cookiePattern = new RegExp(collapsClass+'(-[0-9]+|List-[0-9]+-[0-9]+|List-[0-9]+)');
  var classPattern = new RegExp('^' + collapsClass);
  var hide = collapsClass + ' ' + 'collapse'
  var show = collapsClass + ' ' + 'expand'
  for (var cookieIndex=0; cookieIndex<cookies.length; cookieIndex++) {
    var cookieparts= cookies[cookieIndex].split('=');
    
    var cookiename=cookieparts[0].trim();
    if (cookiename.match(cookiePattern)) {
      var cookievalue=cookieparts[1].trim();
      var expand= document.getElementById(cookiename);
      if (expand) {
        var thisli = expand.parentNode;
        for (var childI=0; childI< thisli.childNodes.length; childI++) {
          if (thisli.childNodes[childI].nodeName.toLowerCase() == 'span') {
            theSpan=thisli.childNodes[childI];
            if (theSpan.className.match(classPattern)) {
              if ((theSpan.className == show && cookievalue ==1) ||
                  (theSpan.className == hide && cookievalue ==0)) {
                var theOnclick=theSpan.onclick+"";
                var matches=theOnclick.match(/.*\(event, ?"([^"]*)", ?"([^"]*)".*\)/);
                var expand=matches[1].replace(/\\u25BA/, '\u25BA');
                expand=expand.replace(/\\u25B6/, '\u25B6');
                var collapse=matches[2].replace(/\\u25BC/, '\u25BC');
                collapse=collapse.replace(/\\u2014/, '\u2014');
                expandCollapse(theSpan,expand,collapse,0,collapsClass);
              }
            }
          }
        } 
      }
    }
  }
}

function expandCollapse( e, expand,collapse, animate, collapsClass ) {
  var classPattern= new RegExp('^' + collapsClass);
  if (expand=='expandImg') {
    expand=expandSym;
  }
  if (collapse=='collapseImg') {
    collapse=collapseSym;
  }
  if( e.target ) {
    src = e.target;
  } else if (e.className && e.className.match(classPattern)) {
    src=e;
  } else {
    try {
      src = window.event.srcElement;
    } catch (err) {
    }
  }

  srcList = src.parentNode;
  if (src.nodeName.toLowerCase() == 'img' ||
      src.parentNode.nodeName.toLowerCase() == 'h2') {
    srcList = src.parentNode.parentNode;
    src=src.parentNode;
  } else if (src.parentNode.parentNode.nodeName.toLowerCase() == 'h2') {
    src=src.parentNode;
    srcList = src.parentNode.parentNode;
  }
  if (srcList.nodeName.toLowerCase() == 'span') {
    srcList= srcList.parentNode;
    src= src.parentNode;
  }
  if (srcList.nodeName.toLowerCase() == 'h2') {
    srcList=srcList.parentNode;
  }
  childList = null;

  for( i = 0; i < srcList.childNodes.length; i++ ) {
    if( srcList.childNodes[i].nodeName.toLowerCase() == 'ul' ) {
      childList = srcList.childNodes[i];
    }
  }
  var hide = collapsClass + ' ' + 'collapse'
  var show = collapsClass + ' ' + 'expand'
  var theSpan = src.childNodes[0];
  var theId= childList.getAttribute('id');
  if (theSpan.className!='sym') {
    theSpan = theSpan.childNodes[0];
    theId = childList.childNodes[0].getAttribute('id');
  }
  if( src.getAttribute( 'class' ) == hide ) {
    createCookie(theId,0,7);
    src.setAttribute('class',show);
    src.setAttribute('title','click to expand');
    theSpan.innerHTML=expand;
    if (animate==1) {
      jQuery(childList).hide('blind', '', 500);
    } else {
      childList.style.display = 'none';
    }
    if (collapsItems[theId]) {
      childList.innerHTML='<li></li>';
    }
  } else {
    createCookie(theId,1,7);
    src.setAttribute('class',hide);
    src.setAttribute('title','click to collapse');
    theSpan.innerHTML=collapse;
      //alert(collapsItems[theId]);
    if (collapsItems[theId]) {
      childList.innerHTML=collapsItems[theId];
    }
    if (animate==1) {
      jQuery(childList).show('blind', '', 500);
    } else {
      childList.style.display = 'block';
    }
  }

  if( e.preventDefault ) {
    e.preventDefault();
  }

  return false;
}

