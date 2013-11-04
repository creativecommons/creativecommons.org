/*
 * Simple XPath Compatibility Plugin for jQuery 1.1
 * By John Resig
 * Dual licensed under MIT and GPL.
 */

(function(jQuery){

	var find = jQuery.find;

	jQuery.find = function(selector, context){

		// Convert the root / into a different context
		if ( !selector.indexOf("/") ) {
			context = context.documentElement;
			selector = selector.replace(/^\/\w*/, "");
			if ( !selector )
				return [ context ];
		}

		// Convert // to " "
		selector = selector.replace(/\/\//g, " ");

		// Convert / to >
		selector = selector.replace(/\//g, ">");

		// Naively convert [elem] into :has(elem)
		selector = selector.replace(/\[([^@].*?)\]/g, function(m, selector){
			return ":has(" + selector + ")";
		});

		// Naively convert /.. into a new set of expressions
		if ( selector.indexOf(">..") >= 0 ) {
			var parts = selector.split(/>\.\.>?/g);
			var cur = jQuery(parts[0], context);

			for ( var i = 1; i < parts.length; i++ )
				cur = cur.parent(parts[i]);

			return cur.get();
		}

		return find(selector, context);
	};

})(jQuery);
