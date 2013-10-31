"""
translate.py

Pass one or more template files through a TAL parser with some simple
locale look-up functions in the context for straightforward i18n expansion.

Copyright 2005-2007, Nathan R. Yergler, Creative Commons
Licensed to the public under the GNU GPL version 2.

Sample Usages:
--------------

translate.py --podir ../pofiles -o .. template.xml.in

   Reads .po files from ../pofiles/ and generates template.xml in the parent
   directory (..).

"""

__version__ = "$Revision: 7581 $"

import re
import sys
import os
import fnmatch
import tempfile
import subprocess
import optparse
import re
import shutil
import glob

from babel.messages.pofile import read_po
from simpletal import simpleTAL, simpleTALES

# import the ElementTree API
import lxml.etree as et
    
CVSROOT = ":pserver:anonymous@cvs.sf.net:/cvsroot/cctools"
CVSMODULE = "zope/iStr/i18n"
VARIABLE_RE = re.compile('\$\{.*?\}', re.I|re.M|re.S)

POFILE_DIR = '../i18n/i18n_po'


def fix_tags(input_string):
    """Pass the input string through an HTML parser to balance any incomplete
    tags and perform entity (specifically &) substitutions."""

    # convert & to &amp;
    input_string = re.sub('&(?!amp;)', '&amp;', input_string)
    
    tag_re = re.compile("<([\w]+)([\w\t =\"']*)>")
    match = re.match(tag_re, input_string)

    if not(match):
        return input_string
    
    # the input string contains what appears to be markup
    try:
        # try to parse as XML to see if we're well formed
        tree = et.XML(input_string)

        # valid XML
        return input_string
    
    except Exception, e:
        # not well formed XML -- probably unbalanced tags

        # try the stupid solution -- just close the first tag we find
        new_input = input_string + "</" + match.groups()[0] + ">"

        try:
            et.XML(new_input)

            return new_input
        except Exception, e:
            # OK, that didn't work... fall back to HTML
            pass
                        
        # no success -- parse as HTML, escaping namespace declarations
        tree = et.HTML(input_string.replace(':', '__'))
    else:
        return input_string

    # determine what to return --
    # if the tag matched at position 0, return the conents of the <body>
    if match and match.start() == 0:

        return et.tostring(tree.xpath('//html/body')[0]
                           )[6:-7].replace('__', ':')

    else:
        # otherwise return the contents of the first <p> in <body>

        return et.tostring(tree.xpath('//html/body/p')[0]
                           )[3:-4].replace('__', ':')

def replace_vars(value):
    """Replace gettext variable declarations with XSLT copy-of's."""
    
    match = VARIABLE_RE.search(value) 	 
    while match is not None: 	 
        if value[match.start() - 1] != '"': 	 

            #<xsl:value-of select="$license-name"/> 	 
            value = value[:match.start()] + \
                   '<xsl:copy-of select="$' + \
                    value[match.start() + 2:match.end() - 1] + \
                    '"/>' + value[match.end():]
        else:
            value = value[:match.start()] + \
                    '{$' + \
                    value[match.start() + 2:match.end() - 1] + \
                    '}' + value[match.end():]

        match = VARIABLE_RE.search(value, match.end())

    return value
                             
def lookupString(key, locale):
    global LOCALES

    if key in LOCALES[locale]:
        result = LOCALES[locale][key].string
    elif key in LOCALES['en']:
        result = LOCALES['en'][key].string
    else:
        result = key

    return fix_tags(replace_vars(result))

def loadCatalogs(source_dir):
    """Load the translation catalogs and return a dictionary mapping
    the locale code to the PoFile object."""

    langs = {}
    
    for root, dirnames, filenames in os.walk(source_dir):
        for fn in filenames:
            if fn[-3:] == '.po':

                # figure out what locale this is based on pathname
                locale = root.split(os.sep)[-1]
                print 'loading catalog for %s...' % locale
                
                msg_catalog = read_po(
                    file(os.path.abspath(os.path.join(root, fn)), 'r'))
                
                langs[locale] = msg_catalog

    return langs

def loadJurisdictions():
    """Load licenses.xml and return a sequence of launched jurisdiction
    codes."""

    # parse licenses.xml -- assumes svn checkout layout
    licenses_xml = et.parse(os.path.join(os.path.dirname(__file__),
                                         '..', 'licenses.xml'))

    # get the raw list
    codes = licenses_xml.xpath('//jurisdiction-info[@launched="true"]/@id')

    # strip out generic codes, as we don't add those to the license name
    return [n for n in codes if n not in ('', '-')]

def loadOpts():
    """Parse command line options; returns a tuple of (opts, args)."""
    parser = optparse.OptionParser(usage="%prog [options...] files",
                                   version="%%prog %s" % __version__)

    parser.add_option('--podir', dest='podir',
                     help='Directory containing .po translation files.')
    parser.add_option('-o', '--output', dest='outputDir',
                      help='Save output files to specified directory'
                      '(defaults to the same directory as input files).')
    parser.set_defaults(podir = os.path.join(os.path.dirname(
        os.path.abspath(__file__)), POFILE_DIR)
                        )
    
    return parser.parse_args()
    
def main():
    global LOCALES

    # parse command line parameters and check for sanity
    (opts, args) = loadOpts()
    if (getattr(opts, 'podir', None) is None):
        print >> sys.stderr, "You must specify --podir."
        sys.exit(1)

    # load the catalogs and jurisdiction list
    LOCALES = loadCatalogs(opts.podir)
    
    # determine our output directory
    output_dir = getattr(opts, 'outputDir', None)

    # set up our TAL context
    context = simpleTALES.Context(allowPythonPath=1)
    context.addGlobal ("locales", LOCALES.keys())
    context.addGlobal ("jurisdictions", loadJurisdictions())
    context.addGlobal ("lookupString", lookupString)

    # iterate over the specified
    for in_fn in args:
        if output_dir is None:
            # just output to the same directory
            out_fn = in_fn[:-3]
        else:
            out_fn = os.path.join(output_dir, os.path.basename(in_fn)[:-3])

        # generate a temporary intermediary file to validate the XML
        temp_fn = "%s.tmp" % out_fn

        # compile the template and write it to the temporary file
        template = simpleTAL.compileXMLTemplate (open (in_fn, 'r'))
        output = file(temp_fn, 'w')

        print 'writing to %s..' % temp_fn
        template.expand (context, output, 'utf-8')
        output.close()

        # try to clear the error log before checking validity
        try:
            et.clearErrorLog()
        except AttributeError:
            # lxml < 1.1
            pass
        
        # re-read the temp file and parse it for well-formed-ness
        try:
            print 'validating XML structure of %s...' % temp_fn
            tree = et.parse(temp_fn)

        except Exception, e:
            print
            print "An error exists in %s: " % temp_fn
            print e
            sys.exit(1)
                
        # the file was either read correctly or elementtree is not available
        print 'moving %s to %s...' % (temp_fn, out_fn)
        shutil.move(temp_fn, out_fn)

if __name__ == '__main__':

    main()
