"""
makerdf.py

Assemble RDF describing all available CC licenses using licenses.xml
as a source for all canonical license URIs.

Requires RDFlib (http://rdflib.net), lxml (http://codespeak.net/lxml).

(c) 2005-2006, Nathan R. Yergler, Creative Commons.
"""

__version__ = 0.5

from rdflib.Graph import Graph
import rdflib
import lxml.etree

import sys
import StringIO
import httplib
import urllib2
from optparse import make_option, OptionParser

def initOpts():
    """Assemble the option parser."""

    option_list = [
        make_option("-v", "--verbose",
                    action="store_true", dest="verbose", default=False,
                    help=""),
        make_option("-l", "--licenses",
                    action="store", type="string", dest="licenses_xml",
                    help="Use the specified licenses file.",
                    default="licenses.xml"),
        make_option("-o", "--output",
                    action="store", type="string", dest="output_rdf",
                    help="Write the RDF to the specified file.",
                    default=""),
        ]

    usage = "%prog [-v] [-l licenses.xml] [-o output.rdf]"
    parser = OptionParser(usage=usage,
                                   version="%%prog %s" % __version__,
                                   option_list = option_list)

    return parser

def assembleRDF(instream, outstream, verbose=False):

    licenses = lxml.etree.parse(instream)
    graph = Graph('default',"http://creativecommons.org/licenses/index.rdf")
    uris = licenses.xpath('//jurisdiction/version/@uri')
    
    for uri in uris:
        if verbose:
            print >>sys.stderr, 'Retrieving %srdf...' % uri

        try:
            rdfsource = rdflib.URLInputSource('%srdf' % uri)
        except httplib.BadStatusLine, e:
            print >>sys.stderr, 'Error retrieving %srdf; bad status line.' % uri
            uris.append(uri)
            continue
        except urllib2.URLError, e:
            print >>sys.stderr, 'URL error on %srdf.' % uri
            uris.append(uri)


        if verbose:
            print >>sys.stderr, 'Parsing %srdf...' % uri

        try:
            graph.parse(rdfsource, publicID=uri)
        except Exception, e:
            print e
            uris.append(uri)

    graph.serialize(outstream)

def main():
    """Run the makerdf script."""
    optparser = initOpts()
    (options, args) = optparser.parse_args()

    output = StringIO.StringIO()
    
    assembleRDF(file(options.licenses_xml), output, options.verbose)

    if options.output_rdf:
        file(options.output_rdf, 'w').write(output.getvalue())
    else:
        print output.getvalue()
        

if __name__ == '__main__':
    main()
