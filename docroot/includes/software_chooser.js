// CC Software License Chooser

YAHOO.namespace("cc.software");

YAHOO.cc.software.update_license = function(e) {

	// post the fields, get the HTML+RDFa

	var dedication_form = document.getElementById('form-details');
	YAHOO.util.Connect.setForm(dedication_form);

	var callback = {
	    success : function(o) {
		document.getElementById('metadata-html').value = o.responseText;
		document.getElementById('metadata-preview').innerHTML = o.responseText;
	    },

	    failure : function(o) { alert(o.statusText) },
	};

	var conn = YAHOO.util.Connect.asyncRequest('GET', 
					      '/license/get-rdfa', callback);

} // update_license

YAHOO.cc.software.init = function() {

    // init the two path buttons
    var pathButtonGroup = new YAHOO.widget.ButtonGroup("pathbuttongroup");
    pathButtonGroup.on("checkedButtonChange",
		       function(e) {
			   document.getElementById('license-uri').value = 
			       e.newValue.get('value');
		       }
		       );

    pathButtonGroup.on("checkedButtonChange", 
		       YAHOO.cc.software.update_license);

    pathButtonGroup.set('checkedButton', pathButtonGroup.getButton(0));

    // call update initially to set the default

} // init

// hook for initialization
YAHOO.util.Event.onDOMReady(YAHOO.cc.software.init);
