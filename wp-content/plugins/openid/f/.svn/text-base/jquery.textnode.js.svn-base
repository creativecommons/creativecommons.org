(function(jQuery){

	jQuery.textnode = {
		hasText : 
		function(a) {
			for (var i=0; i<a.childNodes.length; i++) {
				if (a.childNodes[i].nodeType == 3) return true;
			} 
		},

		addText : 
		function(a, input, settings) { 
			settings = jQuery.extend({replace:false},settings);

			if (input == null || input == '') return;
			if (a.childNodes == 0) { a.html(input); return; }

			var children = [];
			// have to wrap text in a single element in order 
			// to get all the child nodes (including text)
			var nodes=jQuery('<i>'+input+'</i>')[0].childNodes;

			for (var i = a.childNodes.length-1; i >= 0; i--){ 
				if (a.childNodes[i].nodeType == 3) {
					for (var j = nodes.length-1; j>=0; j--) {
						children.unshift(nodes[j]);
					}
					if (!settings.replace)
						children.unshift(a.childNodes[i]);
				} else {
					children.unshift(a.childNodes[i]);
				}
			} 
			jQuery(a).empty();
			for (var i=0; i<children.length; i++) {
				jQuery(a).append(children[i]);
			}
		} 
	};

	jQuery.extend(jQuery.expr[':'], { 
		hastext: "jQuery.textnode.hasText(a)"
	});

	jQuery.fn.appendToText = function(input) { 
		return this.each(function(){ 
			jQuery.textnode.addText(this, input, {replace:false});
		}) 
	}; 

	jQuery.fn.replaceText = function(input) { 
		return this.each(function(){ 
			jQuery.textnode.addText(this, input, {replace:true});
		}) 
	}; 

})(jQuery);
