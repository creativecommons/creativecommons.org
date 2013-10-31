/*
  Creative Commons Legal Code Errata Tool
  Written in 2012 by Jonathan Palecek, Creative Commons.
  
  To the extent possible under law, the author(s) have dedicated all copyright
  and related and neighboring rights to this software to the public domain
  worldwide. This software is distributed without any warranty.

  You should have received a copy of the CC0 Public Domain Dedication along
  with this software. If not, see: 
  http://creativecommons.org/publicdomain/zero/1.0/
 */

/* Fancy script loader module bootstrapper thing. */

var ANNO = {};
(function () {
    // closure to avoid namespace polution.
    var head = document.head ? 
	document.head : 
	document.getElementsByTagName("head")[0];
    var load = function (src) {
	var script = document.createElement('script');
	script.src = src;
	head.appendChild(script);
    };


    var load_path = "";
    (function () {
	// closure which determines we are, etc, and sets a few path vars.
	var path_parts = [];
	var i= -1;
	for (var ch=0; ch<window.location.pathname.length; ch+=1) {
	    var sample = window.location.pathname[ch];
	    if (sample === "/") {
		path_parts.push("");
		i += 1;
	    }
	    else {
		path_parts[i] += sample;
	    }
	}
	var load_path = "";
	if (["localhost", "127.0.0.1"].indexOf(window.location.hostname) !== -1) {
	    ANNO.DEBUG = true;
	}
	else if (window.location.hostname === "staging.creativecommons.org") {
	    load_path = "http://staging.creativecommons.org";
	    ANNO.DEBUG = true;
	}
	else {
	    load_path = "http://creativecommons.org";
	    ANNO.DEBUG = false;
	}
	errata_path = load_path + "/errata/json/";
	// FIXME: this is probably wrong:
	for (var k=1; k<path_parts.length-1; k+=1) {
	    errata_path += path_parts[k];
	    if (k !== path_parts.length-2) {
		errata_path += "_";
	    }
	}
	errata_path += ".json";
	ANNO.errata_json = function () { return errata_path };

	if (!!ANNO.DEBUG) {
	    console.warn("Assuming debug mode.")
	    console.info("load_path: '" + load_path + "'");
	    console.info("errata_json: '" + errata_path + "'");
	}
    })();

    if (ANNO.DEBUG) {
	load(load_path + "/errata/jquery.js");
	load(load_path + "/errata/errata_engine.js");
    }
})();
