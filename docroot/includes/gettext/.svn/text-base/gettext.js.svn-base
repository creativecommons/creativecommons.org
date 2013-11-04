/**
 * gettext.js ( http://code.google.com/p/gettext-js/ )
 *
 * @author     Maxime Haineault, 2007 (max@centdessin.com)
 * @version    0.1.0
 * @licence    M.I.T
 * @example:
 *
 *   Gettext.lang = 'de';
 *   Gettext.gettext('hello %s','world');
 *   _('hello %s','world');
 *
 */

var jsGettext = Class.create();
jsGettext.prototype = {
	
	initialize: function(lang) {
		this.lang         = lang || 'en';
		this.debug        = true;
		this.LCmessages   = {};
		this.links        = [];
		this.linksPointer = 0;
		this.currentFetch = false;
		this.links        = $$('link').map(function(link){
			if (link.rel == 'gettext' && link.href && link.lang) return [link.lang, link.href];
		});
		
		new PeriodicalExecuter(function(pe) {
  			if (Gettext.linksPointer == Gettext.links.length) pe.stop();
  			else {
  				Gettext.include.apply(Gettext, Gettext.links[Gettext.linksPointer])
  				Gettext.linksPointer++;
  			}
		}, 0.5);
	},

	log: function() {
		if (typeof console != 'undefined' && console.log && this.debug) {
			console.log.apply(this,arguments);
		}
	},
	
	include: function(lang, href) {
		if (arguments[1].substring(0,7) == 'http://') {
			onCompleteCallback = this.include_complete.bind(this);
			this.currentFetch  = lang;
			new Ajax.Request(arguments[1], {
				onComplete: onCompleteCallback,
				//asynchronous: false
			});
		}
		else {
			this.LCmessages[lang] = new jsGettext.Parse(str);
			Gettext.log('Loading language: ', lang.toUpperCase(), ' (',str,')');
		}
		this.log('Loading language: ', lang.toUpperCase(), ' (',href,')');
	},
	
	include_complete: function (t) {
			this.log('Fetched language: ', this.currentFetch.toUpperCase(), ' ('+ t.responseText.length +' bytes)');
			this.LCmessages[this.currentFetch] = this.parse(t.responseText);
			this.log(_('Parsed: %d msgids, %d msgids-plurals, %d msgstrs, %d obsoletes, %d contexts, %d references %d flags, %d previous untranslateds, %d previous untranslated-plurals',[
				this.LCmessages[this.currentFetch].msgid.length,
				this.LCmessages[this.currentFetch].msgidplurals.length,
				this.LCmessages[this.currentFetch].msgstr.length,
				this.LCmessages[this.currentFetch].obsoletes.length,
				this.LCmessages[this.currentFetch].contexts.length,
				this.LCmessages[this.currentFetch].references.length,
				this.LCmessages[this.currentFetch].flags.length,
				this.LCmessages[this.currentFetch].previousUntranslateds.length,
				this.LCmessages[this.currentFetch].previousUntranslatedsPlurals.length]));
			this.currentFetch = false;
	},
	
	// This function based on public domain code. Feel free to take a look the original function at http://jan.moesen.nu/
	// ---
	// Changes made by Maxime Haineault (2007):
	// - The function is now extended to Strings instead (using prototype)
	// - The function now accept arrays as arguments, much easier to handle (using prototype)
	// - The function throw error if argument lenght doesn't equal matches count (as specified in gettex file format documentation)	
	// - Translations lookups
	// Reference: http://www.gnu.org/software/gettext/manual/gettext.html#PO-Files
	
	gettext: function() {
		if (!arguments || arguments.length < 1 || !RegExp) return;
		var str      = arguments[0];
		arguments[0] = null;
		arguments    = $A(arguments).compact();
		
		if (arguments.length == 1 && typeof arguments[0] == 'object') {
			arguments = $A(arguments[0]);
		}
		hasTokens = str.match('%','g');
		if (hasTokens && hasTokens.length != arguments.length) {
			Gettext.log('Gettext error: Arguments count ('+ arguments.length +') does not match replacement token count ('+ str.match('%','g').length +').');
			return;
		}

		// Try to find translated string
		if (Gettext.LCmessages[Gettext.lang] && Gettext.LCmessages[Gettext.lang].msgid.indexOf(str) != -1) {
			str = Gettext.LCmessages[Gettext.lang].msgstr[Gettext.LCmessages[Gettext.lang].msgid.indexOf(str)];
		}
		
		var re  = /([^%]*)%('.|0|\x20)?(-)?(\d+)?(\.\d+)?(%|b|c|d|u|f|o|s|x|X)(.*)/; //'
		var a   = b = [], i = 0, numMatches = 0;		
		while (a = re.exec(str)) {
			var leftpart   = a[1], pPad  = a[2], pJustify  = a[3], pMinLength = a[4];
			var pPrecision = a[5], pType = a[6], rightPart = a[7];
			numMatches++;
			if (pType == '%') subst = '%';
			else {
				var param = arguments[i];
				var pad   = '';
				if (pPad && pPad.substr(0,1) == "'") pad = leftpart.substr(1,1);
				else if (pPad) pad = pPad;
				var justifyRight = true;
				if (pJustify && pJustify === "-") justifyRight = false;
				var minLength = -1;
				if (pMinLength) minLength = parseInt(pMinLength);
				var precision = -1;
				if (pPrecision && pType == 'f') precision = parseInt(pPrecision.substring(1));
				var subst = param;
				if (pType == 'b')      subst = parseInt(param).toString(2);
				else if (pType == 'c') subst = String.fromCharCode(parseInt(param));
				else if (pType == 'd') subst = parseInt(param) ? parseInt(param) : 0;
				else if (pType == 'u') subst = Math.abs(param);
				else if (pType == 'f') subst = (precision > -1) ? Math.round(parseFloat(param) * Math.pow(10, precision)) / Math.pow(10, precision): parseFloat(param);
				else if (pType == 'o') subst = parseInt(param).toString(8);
				else if (pType == 's') subst = param;
				else if (pType == 'x') subst = ('' + parseInt(param).toString(16)).toLowerCase();
				else if (pType == 'X') subst = ('' + parseInt(param).toString(16)).toUpperCase();
			}
			str = leftpart + subst + rightPart;
			i++;
		}
		return str;
	},
	
	parse: function(str) {
		// #  translator-comments
		// #. extracted-comments
		// #: reference...
		// #, flag...
		// #| msgid previous-untranslated-string
		// msgid untranslated-string
		// msgstr translated-string

		rgx_msg   = new RegExp(/((^#[\:\.,~|\s]\s?)?|(msgid\s"|msgstr\s")?)?("$)?/g);
		function clean(str) {
			return str.replace(rgx_msg,'').replace(/\\"/g,'"');
		}

		po        = str.split("\n");
		head      = [];
		msgids    = [];
		strings   = [];
		obs       = [];
		tpls      = [];
		curMsgid  = -1;
		output    = { header: [], contexts: [], msgid: [], msgidplurals: [], references: [], flags: [], msgstr: [], obsoletes: [], previousUntranslateds: [], previousUntranslatedsPlurals: [] };

		for(x=0, cnt=po.length; x<cnt; ++x) {
			if (po[x].substring(0,1) == '#') {
				switch (po[x].substring(1,2)) {
					// translator-comments
					case ' ':
					// top comments
					if (curMsgid == 0) output.header.push(po[x]);
					break;

					// references
					case ':':
					if(typeof output.references[curMsgid] == 'undefined') output.references[curMsgid] = [];
					output.references[curMsgid].push(clean(po[x]));
					break;

					// msgid previous-untranslated-string
					case '|':
					if(typeof output.previousUntranslateds[curMsgid] == 'undefined') output.previousUntranslateds[curMsgid] = [];
					// previous-untranslated-string-plural
					if (po[x].substring(3,15) == 'msgid_plural') {
						output.previousUntranslateds[curMsgid].push(clean(po[x]));
					}
					else {
						output.previousUntranslatedsPlurals[curMsgid].push(clean(po[x]));
					}
					break;

					// flags
					case ',':
					if(typeof output.flags[curMsgid] == 'undefined') output.flags[curMsgid] = [];
					output.flags[curMsgid].push(clean(po[x]));
					break;

					// obsoletes
					case '~':
					if (po[x].substring(3,9) == 'msgid ') {
						curMsgid++;
						if(typeof output.msgid[curMsgid] == 'undefined') output.msgid[curMsgid] = [];
						output.msgid[curMsgid].push(clean(po[x]));
						output.obsoletes.push(curMsgid);
					}
					else if (po[x].substring(3,10) == 'msgstr ') {
						if(typeof output.msgstr[curMsgid] == 'undefined')    output.msgstr[curMsgid] = [];
						output.msgstr[curMsgid].push(clean(po[x]));
					}
					break;
				}
			}
			else {
				// untranslated-string
				if (po[x].substring(0,6) == 'msgid ') {
					if (po[x].substring(6,8) != '""')  {
						curMsgid++;
						if(typeof output.msgid[curMsgid] != 'object') output.msgid[curMsgid] = [];
						output.msgid[curMsgid].push(clean(po[x]));
					}
				}

				// untranslated-string-plural
				if (po[x].substring(0,13) == 'msgid_plural ') {
					if (!output.msgidplurals[curMsgid]) output.msgidplurals[curMsgid] = [];
					output.msgidplurals[curMsgid].push(clean(po[x]));
				}

				// translated-string
				if (po[x].substring(0,6) == 'msgstr') {
					if (po[x].substring(8,10) != '""')  {
						// translated-string-case-n
						if (po[x].substring(6,7) == '[') {}
						else {
							if (!output.msgstr[curMsgid]) output.msgstr[curMsgid] = [];
							output.msgstr[curMsgid].push(clean(po[x]));
						}
					}
				}

				// context
				if (po[x].substring(0,7) == 'msgctxt ') {
					if (!output.contexts[curMsgid]) output.contexts[curMsgid] = [];
					output.contexts[curMsgid].push(clean(po[x]));
				}
			}
		}
		return output;
	}
}
Gettext = new jsGettext();
function _() { return Gettext.gettext.apply(this,arguments); }