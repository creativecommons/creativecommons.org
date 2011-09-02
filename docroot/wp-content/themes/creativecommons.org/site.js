$(function() {

// Handle search query without page reload
// 2010-02-08 ~Alex
//
$("#search_form").submit(function(e) {
	e.preventDefault();

	var stype = $("input[name=stype]:checked");
	var query = $("input[name=q]");
	
	if (query.val().length === 0) return;

	if (stype.val() === "content") {
		window.location = "http://search.creativecommons.org/?q=" + query.val();
	} else {
		window.location = "/?s=" + query.val();
	}

});


});

