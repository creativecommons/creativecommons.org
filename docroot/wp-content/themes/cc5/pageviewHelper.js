/* Google Analytics Coercion 
 * We technically lose people when they go to parts of the site that are on subdomains
 * _trackPageview will turn "exits" into "next pages" to help the analytics numbers along
 * especially on the homepage
 */

if (typeof(_gat) == "object") {
	var analytics = "UA-2010376-1";

	jQuery("a[href ^= 'http://search.creativecommons.org']").click(function() { 
		var pageTracker = _gat._getTracker(analytics);
		pageTracker._trackPageview("/search.creativecommons.org");
		return true;
	});


	jQuery("a[href ^= 'https://creativecommons.net']").click(function() { 
		var pageTracker = _gat._getTracker(analytics);
		var supportPage = this.href.slice(35);

		pageTracker._trackPageview("/creativecommons.net" + supportPage);
		return true;
	});


	jQuery("a[href ^= 'http://planet.creativecommons.org']").click(function() { 
		var pageTracker = _gat._getTracker(analytics);
		pageTracker._trackPageview("/planet.creativecommons.org");
		return true;
	});


	jQuery("a[href ^= 'http://sciencecommons.org']").click(function() { 
		var pageTracker = _gat._getTracker(analytics);
		pageTracker._trackPageview("/sciencecommons.org");
		return true;
	});
}

