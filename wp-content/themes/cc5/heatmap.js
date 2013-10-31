// http://nevyan.blogspot.com/2006/12/free-website-click-heatmap-diy.html


var xOffset, yOffset;
var tempX = 0;
var tempY = 0;

//detect browser
var IE = document.all ? true: false
if (!IE) {
    document.captureEvents(Event.MOUSEMOVE)
}

// find the position of the first item on screen and store offsets
// find the first item on screen (after body)
var firstElement = document.getElementsByTagName('body')[0].childNodes[1];

// find the offset coordinates
xOffset = findPosX(firstElement);
yOffset = findPosY(firstElement);

if (IE) {
    // In IE there's a default margin in the page body. If margin's not defined, use defaults
    var marginLeftExplorer = parseInt(document.getElementsByTagName('body')[0].style.marginLeft);
    var marginTopExplorer = parseInt(document.getElementsByTagName('body')[0].style.marginTop);

    // assume default 10px/15px margin in explorer
    if (isNaN(marginLeftExplorer)) {
        marginLeftExplorer = 10;
    }
    if (isNaN(marginTopExplorer)) {
        marginTopExplorer = 15;
    }
    xOffset = xOffset + marginLeftExplorer;
    yOffset = yOffset + marginTopExplorer;
}

// Attempting to normalize clicks for various browser widths
// Since the content area is a consistent width we can always compensate for browser width
if (window.innerWidth) {
        browserWidth = Math.ceil((1024 - window.innerWidth) / 2);
} else {
        browserWidth = Math.ceil((1024 - document.documentElement.clientWidth) / 2);
}

xOffset -= browserWidth;


// attach a handler to the onmousedown event that calls a function to store the values
document.onmousedown = getMouseXY;


// Find positions
function findPosX(obj) {
    var curleft = 0;
    if (obj.offsetParent) {
        while (obj.offsetParent) {
            curleft += obj.offsetLeft
            obj = obj.offsetParent;
        }
    } else if (obj.x) {
        curleft += obj.x;
    }
    return curleft;
}

function findPosY(obj) {
    var curtop = 0;
    if (obj.offsetParent) {
        while (obj.offsetParent) {
            curtop += obj.offsetTop
            obj = obj.offsetParent;
        }
    } else if (obj.y) {
        curtop += obj.y;
    }
    return curtop;
}

function getMouseXY(e) {
    if (IE) {
        tempX = e.clientX + document.body.scrollLeft
        tempY = e.clientY + document.body.scrollTop
    } else {
        tempX = e.pageX
        tempY = e.pageY
    }

    tempX -= xOffset;
    tempY -= yOffset;
    var url = '/wp-content/themes/cc5/heatmap.php?x=' + tempX + '&y=' + tempY;

		// Fire off a jQuery AJAX request
		jQuery.get(url);

    return true;
}
