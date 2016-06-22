/*
 * CC0
 * zero_chooser.js
 * 
 * copyright 2007-2008, Creative Commons, Nathan R. Yergler
 * licensed to the public under the GNU GPL v2
 * 
 */
YAHOO.namespace("cc.zero");

YAHOO.cc.zero._show_panel = function(e, index) {

    e.preventDefault();

    // push the current item onto the stack
    YAHOO.cc.zero.chooser_path.push(this.getLayout().activeItem);

    // show the specified item
    this.getLayout().setActiveItem(index);

    this.getBottomToolbar().items.get('move-back').setDisabled(false);

} // _show_panel

YAHOO.cc.zero._back = function() {

    // pop the last place off the stack
    last = YAHOO.cc.zero.chooser_path.pop();

    // show the last page
    YAHOO.cc.zero.chooser.getLayout().setActiveItem(last);

    // see if we need to disable the back button
    if (YAHOO.cc.zero.chooser_path.length == 0) {
	YAHOO.cc.zero.chooser.getBottomToolbar().items.get('move-back').setDisabled(true);
    }

} // _back

YAHOO.cc.zero._enable_button = function(e, btn_id) {

    Ext.get(btn_id).dom.disabled = !(e.target.checked);

} // _enable_button

YAHOO.cc.zero.enable_button_assertion = function(e) {

   // enable the continue button if one or more of the reason 
   // checkboxes is selected.
   var disabled = true;

   function is_checked(el, scope, index) {

       // bail out if we've already found a reason to enable
       if (!disabled) return;

       // special case the "other" check box
       if (el.dom.id == 'enable_other_assertion_reason') {

	   if (Ext.get("other_assertion_reason").dom.value.trim() != '') {
	       disabled = false;
	   }

       } else if (el.dom.checked) {
	   disabled = false;
       }
   } // is_checked

   Ext.select(".reason").each(is_checked);

   Ext.get("details-submit").dom.disabled = disabled;

} // enable_button_assertion

YAHOO.cc.zero.on_show_form = function(e) {

    if (Ext.get("confirm_details"))
	Ext.get("confirm_details").dom.checked = false;

    Ext.get("details-submit").dom.disabled= true;

} // on_show_form

YAHOO.cc.zero.on_show_results = function(e) {

    // get the HTML + RDFa
    var mgr = Ext.get("results-preview").getUpdater();

    mgr.formUpdate("form-details", "/license/get-rdfa",
		   false, function (el, success, response) {
		       if (success) {
		   Ext.get("results-html").dom.value = response.responseText;
		       }
		   });

} // on_show_results

function swapbutton(buttonurl) {
  //document.getElementById('licensebutton').setAttribute('src',buttonurl);
  e = document.getElementById('results-html');
  var codetocopy = e.value;
  var newcodetocopy = codetocopy.replace(/src=".*?"/, 'src="'+buttonurl+'"');
  e.value = newcodetocopy;  

  Ext.get("results-preview").dom.innerHTML = newcodetocopy;
}

YAHOO.cc.zero.init = function() {

    YAHOO.cc.zero.chooser_path = new Array();

    YAHOO.cc.zero.chooser = new Ext.Panel({
        layout:'card',
	activeItem: 0, 
	renderTo:'zero-wizard',
	autoWidth:true,
	height:450,
	border:false,
	defaults: {
            // applied to each contained panel
	    border:false,
	    bodyStyle:"padding:10px",
	    title:' ',
	},

	bbar: [
        {
            id: 'move-back',
            text: '<< Back',
            handler: YAHOO.cc.zero._back,
            disabled: true
        },
	       ],

	// the panels (or "cards") within the layout
	items: [
    {contentEl:'page-welcome', id:'page-welcome',},
    {contentEl:'page-details', id:'page-details'},
    {contentEl:'page-confirmation', id:'page-confirmation'},
    {contentEl:'page-results', id: 'page-results', autoScroll:true},
    {contentEl:'page-bailout', id: 'page-bailout'},
	       ]
	});

    // initalize the title/headers
    // -- we do this here so Zope can handle i18n
    for (i = 0; i < YAHOO.cc.zero.chooser.items.length; i++) {
	var current = YAHOO.cc.zero.chooser.items.get(i);

	hd = current.getEl().down("div .hd");
	if (hd && hd.dom.innerHTML) {
	    current.setTitle(hd.dom.innerHTML);
	    hd.remove();
	} // if a .hd was found

    } // for each panel...

    // init the path buttons
    Ext.get("do-begin").on("click", 
	     YAHOO.cc.zero._show_panel.createDelegate(YAHOO.cc.zero.chooser, 
						      ['page-details'], 1));

    // forward-movement handlers
    Ext.get("details-submit").on("click",
       YAHOO.cc.zero._show_panel.createDelegate(YAHOO.cc.zero.chooser, 
				['page-confirmation'], 1));

    Ext.get("confirm-submit").on("click",
       YAHOO.cc.zero._show_panel.createDelegate(YAHOO.cc.zero.chooser, 
						['page-results'], 1));
    Ext.get("decline-submit").on("click",
       YAHOO.cc.zero._show_panel.createDelegate(YAHOO.cc.zero.chooser, 
						['page-bailout'], 1));

    // add listeners for the "agreement" checkboxes
    if (Ext.get("confirm_details"))
	Ext.get("confirm_details").on("click",
	      YAHOO.cc.zero._enable_button.createDelegate(this, 
							  ["details-submit"], 
							  1));
	
    Ext.select(".reason").on("click", YAHOO.cc.zero.enable_button_assertion);

    if (Ext.get("enable_other_assertion_reason")) {

	Ext.get("other_assertion_reason").on("keyup", 
				 YAHOO.cc.zero.enable_button_assertion);
	Ext.get("other_assertion_reason").dom.disabled = true;
	Ext.get("enable_other_assertion_reason").on("click",
            function (e) {
                Ext.get("other_assertion_reason").dom.disabled =
		    !e.target.checked;
						    });

    }

    // add panel-show listeners
    YAHOO.cc.zero.chooser.items.get('page-results').on("show", 
					  YAHOO.cc.zero.on_show_results);
    YAHOO.cc.zero.chooser.items.get('page-details').on('show',
					      YAHOO.cc.zero.on_show_form);
				     
} // init

// hook for initialization
Ext.EventManager.onDocumentReady(YAHOO.cc.zero.init);

