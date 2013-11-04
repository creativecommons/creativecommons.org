#!/usr/bin/python
## USAGE: convert_zpt.py $zpt_file.py
## converts it from using the old i18n:translate="key" to creating
## a text child of that node with the English version of that
## sitting inside.

import os
import convert
import re
import babel.messages.pofile

xml_hack_replacements = {}

def key2en(key):
	filename = os.path.join(convert.get_default_pofile_path(), 'icommons-en.po')
	pofile = babel.messages.pofile.read_po(open(filename))
	# pofile._messages is a mapping from string ID to Message object
	# Message objects should have .string gotten from them
	return unicode(pofile._messages[key].string)

from xml.dom.minidom import parseString
## sample input: <span i18n:translate="country.mk" />
## sample output: <span i18n:translate="">Macedonia</span>
def convert_zpt_string(s):
	try:
		u = unicode(s)
	except:
		u = unicode(s, 'utf-8')
	utf8 = u.encode('utf-8')
	dom = parseString(utf8)

	# Hand off to a DOM object-only function.
	convert_zpt_dom_elements(dom.firstChild.childNodes)

	# Hand an XML "string" back
	xml_str = unicode(dom.toxml(encoding='utf-8'), 'utf-8')
	for replaceme in xml_hack_replacements:
		xml_str.replace(replaceme.decode('utf-8'), xml_hack_replacements[replaceme].decode('utf-8'))
	return xml_str

def convert_zpt_dom_elements(elts):
	''' Input: A list of DOM elements where you want <span i18n:translate="bbq"></span>
	to become <span i18n:translate="">Barbecue</span>
	Output: None
	Side-Effect: elts are converted as described in Input'''
	# For each element, does it have an i18n:translate='' field?
	# If so, then we:
	# 0. Remove all children of the element
	# 1. store that value as the i18n_key
	# 2. remove the i18n:translate attribute
	# 3. look up the i18n_value for that key
	# 4. Turn that i18n_value into DOM elements we can insert safely into this DOM object

	for elt in elts:
		if hasattr(elt, 'attributes') and elt.attributes:
			print 'has attrib', elt
			if 'i18n:translate' not in elt.attributes.keys():
				print 'deeper on', elt
				convert_zpt_dom_elements(elt.childNodes)
				continue
			print 'didnt skip', elt
			i18n_key = elt.attributes['i18n:translate'].nodeValue
			if i18n_key:
				# Step 0
				elt.childNodes = [] # Step 0

				# Step 2
				elt.setAttribute('i18n:translate', '')
				
				# Step 3
				i18n_value_as_unicode = unicode(key2en(i18n_key))
				i18n_value_as_raw_dom_elts = i18nstring2dom_elts(i18n_value_as_unicode)
				
				# Step 4
				# Then, we modify the raw parsed i18n DOM elements
				add_translation_spans_to_dom_elts(i18n_value_as_raw_dom_elts)

				# Step 5; Insert those as children of this element
				for new_child in i18n_value_as_raw_dom_elts:
					elt.appendChild(new_child)
		else:
			convert_zpt_dom_elements(elt.childNodes)

def i18nstring2dom_elts(u):
	global xml_hack_replacements # A lookup table for what Python generates vs. what translation expects

	u = unicode(u)
	wrapped = '<xml>%s</xml>' % u.encode('utf-8')
	s_as_dom_elts = parseString(wrapped)
	original_de_xmled = de_xmled = s_as_dom_elts.toxml(encoding='utf-8').split('\n', 1)[1]

	# <evil hacks> :-)
	if '<br />' in wrapped:
		de_xmled = de_xmled.replace('<br/>', '<br />')
	if '"' in wrapped:
		de_xmled = de_xmled.replace('&quot;', '"')

	if original_de_xmled != de_xmled:
		xml_hack_replacements[original_de_xmled] = de_xmled
	# </evil>

	assert(de_xmled == wrapped)

	return s_as_dom_elts.firstChild.childNodes

def add_translation_spans_to_dom_elts(elts):
	''' This modifies the DOM that the elements belong to.
	Using the DOM promotes side-effect-based programming,
	and it would be a little silly to jam SAX into here, too.'''
	for elt in list(elts): # iterate over a copy because this
			       # modifies the list
		dom = elt.ownerDocument
		if elt.nodeType == elt.TEXT_NODE:
			# Then we must remove the sucker, and put in its place
			# a suitable set of replacements.

			# First, build those replacements.
			replacements = []
			print 'the data was', elt.data
			mixed = re.split(r'([$][{]\w*[}]|[$]\w*)', elt.data)
			for index, value in enumerate(mixed):
				if (index % 2) == 0:
					replacements.append(dom.createTextNode(value))
				else:
					print 'zoinds'
					insert_me = dom.createElement('span')
					insert_me.setAttribute('tal:omit-tag', '')
					insert_me.setAttribute('tal:content', 'fixme/baby')
					assert value[0] == '$'
					if '{' in value:
						value = value[2:-1]
					else:
						value = value[1:]
					insert_me.setAttribute('i18n:name', value)
					replacements.append(insert_me)

			# Okay, got replacements.  Now for DOM surgery.
			parent = elt.parentNode
			index = parent.childNodes.index(elt)
			# Use the old position as an anchor.
			parent.childNodes[index:index+1] = replacements
		else: # it's not text
			add_translation_spans_to_dom_elts(elt.childNodes)

## NOTE that I'm not going to handle internal translation variables yet (ever?)
## when fixing those by hand will probably be okay.
def main(filename):
	old_string = unicode(open(filename).read(), 'utf-8')
	new_string = convert_zpt_string(old_string)
	assert(type(new_string) == unicode)
	# Totally lame hacks.  First of all, swipe out the first <?xml> thing
	if new_string.split('\n')[0] == '<?xml version="1.0" encoding="utf-8"?>':
		new_string = '\n'.join(new_string.split('\n')[1:])
	assert not new_string.startswith("<?xml")
	# Secondly, make sure it ends in a newline if it did before.
	if old_string[-1] == '\n' and new_string[-1] != '\n':
		new_string += '\n'
	fd = open(filename, 'w')
	fd.write(new_string.encode('utf-8'))

if __name__ == '__main__':
	import sys
	main(sys.argv[1])
