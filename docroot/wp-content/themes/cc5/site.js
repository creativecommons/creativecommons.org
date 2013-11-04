function bannerHtml() {
    var banner = new Array(4); for (i = 0; i < banner.length; ++i) banner[i] = new Array(5);
	
	banner[0][0] = "&utm_medium=sbanner_1_elizabeth"; banner[0][1] = "elizabeth-stark.jpg"; banner[0][2] = "Creative Commons exemplifies the potential<br/>for making knowledge available to the world."; banner[0][3] = "Elizabeth Stark, founder of the Open Video Alliance"; banner[0][4] = "elizabeth-stark";
	
	banner[1][0] = "&utm_medium=sbanner_1_neeru"; banner[1][1] = "neerukhosla.jpg"; banner[1][2] = "To make a difference in education, we have to ensure that all students<br/>have access to information that is relevant to them."; banner[1][3] = "Neeru Khosla, founder of CK12 Foundation"; banner[1][4] = "neeru-khosla";

	banner[2][0] = "&utm_medium=sbanner_1_mitchell"; banner[2][1] = "mitchellbaker.jpg"; banner[2][2] = "Creative Commons provides an easy way for people<br/> to choose to collaborate in creative activities."; banner[2][3] = "Mitchell Baker, chair of the Mozilla Foundation"; banner[2][4] = "mitchell-baker";
	
	banner[3][0] = "&utm_medium=sbanner_1_josh"; banner[3][1] = "joshsommer.jpg"; banner[3][2] = "Creative Commons is grease to the wheels of science.<br/>It is a source of hope to me in the race to outrun my disease."; banner[3][3] = "Josh Sommer, executive director of the Chordoma Foundation"; banner[3][4] = "josh-sommer";
	
	return banner;
}
function thundercats() {
	if (location.href.match(/http\:(.*)?creativecommons.org\/$/)  || (navigator.userAgent.indexOf("MSIE 7") > -1) || (navigator.userAgent.indexOf("MSIE 6") > -1)) return;

    var i = Math.floor(Math.random() * 4);
    var banners = bannerHtml();

    var d = document.createElement("div");
    var mainContent = document.getElementById("globalWrapper");

	var href = '<a href="https://creativecommons.net/superheroes/'+ banners[i][4] +'?utm_campaign=superhero&utm_source=ccorg' + banners[i][0] + '" style="color:#000; text-decoration:none;">';

    d.setAttribute('style', 'font-size: 22px; font-family: "helvetica neue", arial, sans-serif; line-height:1; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.4); color: #000; padding: 7px 0 2px 0; border-bottom: 1px solid rgb(120, 159, 44); margin-top: -1px; background: #c5deed; background:-webkit-gradient(linear, left top, left bottom, from(#d5eefd), to(#a1c6dd)); background:-moz-linear-gradient(center top, #c5deed, #91b6cd); z-index:1000;');
   	d.innerHTML = '<div style="display:inline-block;">'+ href +'<img src="https://creativecommons.net/sites/default/files/' + banners[i][1] +'" height="85" style="vertical-align:middle; margin: 5px; -webkit-border-radius:5px; -moz-border-radius:5px;" border="0"/></a></div> <div style="display:inline-block; text-align:left; line-height:1.25; vertical-align:middle; margin-left: 15px;"><strong><span>'+ href +'<span style="display:inline-block; text-indent:-0.5em;">&#8220;</span>'+ banners[i][2] +'&#8221;</span></strong> &mdash; <em style="color:#c01100;">Read More</em></a><br/><em style="font-size:75%;">&mdash; '+ banners[i][3] +'</em></div>';
   	mainContent.parentNode.insertBefore(d, mainContent);
}

if (typeof window.addEventListener !== 'undefined') {
    window.addEventListener('load', thundercats, false);
} else {
    window.attachEvent('onload', thundercats);
}



if (typeof YAHOO != "undefined") { 

YAHOO.namespace("cc.site");

// convenience function for creating help tool tips
YAHOO.cc.site.init_help_item = function(help_anchor) { 

    var link_id = help_anchor.id;
    var help_id = 'help_' + link_id;
	
	// Make sure help_id doesn't have the sideitem class
	YAHOO.util.Dom.removeClass(help_id, "sideitem");

    // make sure we have an array to hold the list of panels
    if (!YAHOO.cc.site.help_panels) {
			YAHOO.cc.site.help_panels = new Array();
    }
	
	 /* Align to the bottom right of the last column, 
	    default to bottom left */
	 if (YAHOO.util.Dom.hasClass(help_id, "topright")) {
		var theContext = [help_anchor, 'tr', 'br'];
	 } else {
		var theContext = [help_anchor, 'tl', 'bl'];
	 }
	 
	 if (help_id == "help_international_list") {
		var panelWidth = "180px";
	} else {
		var panelWidth = "280px";
	}
		
    // create the new panel and position it
    var new_panel = new YAHOO.widget.Panel(help_id, 
                            {close: false, title:false, 
			   visible: false, 
			   draggable: false, 
			   width: panelWidth, 
				effect: {effect:YAHOO.widget.ContainerEffect.FADE,duration:0.05},
			   context: theContext
			    } ); 

    var item_idx = YAHOO.cc.site.help_panels.push(new_panel) - 1;

    YAHOO.cc.site.help_panels[item_idx].render();

	// hideTimeout code adapted from http://jqueryfordesigners.com/coda-popup-bubbles/
	var hideDelay = 150;
	var hideTimeout = null;
	
	var showDelay = 500;
	var showTimeout = null;
	
	
   // connect the event handlers
	function showPanel(e) {
		if (hideTimeout) clearTimeout(hideTimeout);
		if (showTimeout) clearTimeout(showTimeout);
		
		showTimeout = setTimeout(function() {
			showTimeout = null;
			
		   YAHOO.cc.site.help_panels[item_idx].show();
		}, showDelay);

	
		YAHOO.util.Event.preventDefault(e);	   
	}
	
	function hidePanel(e) {
		if (hideTimeout) clearTimeout(hideTimeout);
		if (showTimeout) clearTimeout(showTimeout);
						
		hideTimeout = setTimeout(function() {
			hideTimeout = null;
							
		 	YAHOO.cc.site.help_panels[item_idx].hide();
		   
			YAHOO.util.Event.preventDefault(e);			
		}, hideDelay);
						
	}
	
   YAHOO.util.Event.addListener(link_id, "mouseover", showPanel);
	YAHOO.util.Event.addListener(link_id, "mouseout", hidePanel);
	
	YAHOO.util.Event.addListener(help_id, "mouseover", showPanel);
	YAHOO.util.Event.addListener(help_id, "mouseout", hidePanel);
		
} // init_help_text

var searchValue = "Search site";

function wakeSearch(e) {
		var search = document.getElementById("s");

		if (search.value == searchValue) {
			search.value = "";
			YAHOO.util.Dom.replaceClass(search, "inactive", "active");
			document.getElementById("searchsubmit").disabled = "";
		}	
	return false;
}
function resetSearch(e) {
	var search = document.getElementById("s");
	if (search.value == "") {
		search.value = searchValue;
		YAHOO.util.Dom.replaceClass(search, "active", "inactive");
		document.getElementById("searchsubmit").disabled = "disabled";
	}
	
	return false;
}

YAHOO.cc.site.init = function() {
    // initialization for help pop-ups

	YAHOO.util.Dom.getElementsByClassName('helpLink', null, null,
											YAHOO.cc.site.init_help_item);
	
	resetSearch();
	YAHOO.util.Event.addListener("s", "click", wakeSearch);
	YAHOO.util.Event.addListener("s", "blur", resetSearch);


} // init

YAHOO.util.Event.onDOMReady(YAHOO.cc.site.init);

}

