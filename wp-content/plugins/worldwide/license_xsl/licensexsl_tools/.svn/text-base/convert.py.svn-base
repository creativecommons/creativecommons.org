#!/usr/bin/python

## This program converts a PO file from using the internal ID
## to using the en string as the identifier.

try:
	import psyco
except:
	pass # slo be it

DEBUG=0
import sys
import os
if not DEBUG:
    debug_stream = open('/dev/null', 'w')
else:
    debug_stream = sys.stdout

import babel.messages.catalog
import babel.messages.pofile
import StringIO

def get_dirname_of_this_file():
    ## FYI: Totally evil
    import sys
    my_file = sys._getframe().f_code.co_filename
    return os.path.dirname(my_file)

def get_default_pofile_path():
    return os.path.join(get_dirname_of_this_file(), "../i18n/i18n_po/")

POFILE_PATH=get_default_pofile_path()

def key2canonical(key):
    # FIXME: Probably doesn't work due to API change from NY's translate.py
    # to babel.
    pofile = get_PoFile('en')
    if key not in pofile.strings:
	key = key.strip() # gosh darn it
	print 'had to strip key, hope it helped!', key
    return unicode(pofile[key], 'utf-8')

'''Input: a po fd that needs to be converted.
Output: a po file string that has the same contents as
the original PO file, but the keys have been replaced with
canonical values for those keys.'''
def pofd2converted(pofd):
    r = babel.messages.pofile.read_po(pofd)
    # r._messages is a mapping from string ID to Message object
    # Message objects should have .string gotten from them
    new_cat = babel.messages.catalog.Catalog()
    for key in r._messages:
	# In the case that the key does not exist, add the old key
	value = unicode(r[key].string)
	if value: # only bother converting those that are translated
	    try:
	        english_key = key2canonical(key)
	        new_cat.add(english_key, value)
	    except KeyError:
	        print 'sad, could not find English for', key, 'so adding it with old lame key'
	        new_cat.add(key, value)

    ## Set some other properties of new_cat
    out_fd = StringIO.StringIO()
    babel.messages.pofile.write_po(out_fd, new_cat)
    ret = out_fd.getvalue()

    unicode_test = unicode(ret, 'utf-8')
    return ret

# Code cleanup: convert to a class with __call__ maybe

pofile_cache = {}
def get_PoFile(language, use_cache = True):
    global pofile_cache

    if (not use_cache) or (language not in pofile_cache):
        pofile = os.path.join(POFILE_PATH, language, 'cc_org.po')
        ret = babel.messages.pofile.read_po(open(pofile))
    if use_cache:
        if language in pofile_cache:
            ret = pofile_cache[language]
        else:
            pofile_cache[language] = ret
    return ret

def country_id2name(country_id, language):
	# Now gotta look it up with gettext...
	po = get_PoFile(language)
	country_key = 'country.%s' % country_id
	if country_key in po:
		return po[country_key].string
	return country_id # so sad, can't find country

def extremely_slow_translation_function(s, out_lang):
	try:
		u = unicode(s)
	except:
		u = unicode(s, 'utf-8')
	# First, look through the en po for such a string
	en_po = get_PoFile('en')
	found_key = None
	for entry in en_po._messages:
		if en_po[entry].string == u:
			found_key = entry
			print >> debug_stream, 'yahoo, found', found_key
	if found_key is None:
		print >> debug_stream, 'sad, did not find match'

	real_po = get_PoFile(out_lang)
	if found_key in real_po._messages:
		return real_po[found_key].string
	# Return the version in out_lang's PO.
	return u # but if we don't find it, fail silently!
