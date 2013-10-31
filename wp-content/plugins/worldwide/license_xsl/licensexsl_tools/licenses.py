#!/usr/bin/python
"""
licenses.py
$Id: licenses.py 5589 2007-03-15 12:04:34Z nyergler $

Maintenance script for licenses.xml; adds licenses, versions and jurisdictions
as needed.

Original script developed by Will Frank;
updated by Nathan Yergler to use new XML schema and explicit options.

(c) 2005, Creative Commons, Will Frank, Nathan R. Yergler
licensed to the public under the GNU GPL version 2.
"""

import sys
import os
from optparse import OptionParser

from lxml import etree

# *******************************************************************
# * command line option support

def makeOpts():
    """Define an option parser and return it."""
    
    usage = "usage: %prog [options] [<version>] [<jurisdiction>]"
    parser = OptionParser(usage)

    parser.add_option( '-i', '--info',    dest='infoOnly',
                       action="store_true",
                       help="Only add jurisdiction information, not licenses.",
                       )

    parser.add_option( '-f', '--file', dest='filename',
                       help='Specify the file to update (defaults '
                       'to licenses.xml)')
    parser.add_option( '-c', '--codes', dest='licenses',
                       help='License codes to add, comma delimited '
                       '(defaults to primary six)',
                       )

    # jurisdiction information options
    parser.add_option( '--launched', dest='launched',
                       action="store_true",
                       help="Mark the specified jurisdiction as launched.",
                       )
    parser.add_option( '--not-launched', dest='launched',
                       action='store_false',
                       help="Mark the specified jurisdiction as not launched.",
                       )
    parser.add_option( '--lang', dest='langs',
                       help="Comma delimited list of languages for the "
                       "specified jurisdiction",
                       )
    parser.add_option( '--uri', dest='juris_uri',
                       help="The URI of the jurisdiction specific web page.",
                       )
    
    parser.set_defaults(filename='licenses.xml',
                        licenses="by-nc,by,by-nc-nd,by-nc-sa,by-sa,by-nd",
                        infoOnly=False,
                        langs=[])
    
    return parser

def isfloat(var):
    """Return True if var can be cast to a float, otherwise return False."""
    try:
        float(var)
        return True
    except ValueError, e:
        return False
        
def checkArgs(opts, args):
    """Examines the arguments left over after extracting options, and makes
    sure enough have been passed in.  If an error is found, print an error
    message and return None.  Otherwise return a dictionary of arguments
    mapped to their logical names."""

    result = {}
    remaining = list(args)

    if opts.infoOnly == False:
        # we need a jurisdiction and version
        version_arg = [n for n in remaining if isfloat(n)]
        if len(version_arg) == 0:
            print "You must specify a license version to add."
            return None
        elif len(version_arg) > 1:
            print "Ambiguous version arguments: %s" % ",\n ".join(version_arg)
            return None
        else:
            result['version'] = version_arg[0]
            remaining.remove(result['version'])

    # should be one remaining argument, the jurisdiction
    if len(remaining) > 1:
        print "Ambiguous jurisdiction arguments: %s" % \
              ",\n ".join(remaining)
        return None

    result['jurisdiction'] = remaining[0]

    return result

# * 
# *******************************************************************

def addLicense(filename, jurisdiction, version, licenses):

    #Let's begin. First check: Find the six active licenses.

    tree=etree.ElementTree(file=filename)
    root=tree.getroot()
    
    firstit=root.getiterator('license')
    for v in firstit:
        found=0
        if v.get('id') in licenses:
            #When you find an active license, 
            # now check for an existing jurisdiction.
            print "Adding to "+v.get('id')+" license..."
            tempit=v.getiterator('jurisdiction')
            for w in tempit:
                if w.get('id')==jurisdiction:
                    #If it's there, add in the new version.
                    found=1
                    print "Adding version %s to existing jurisdiction %s." % \
                          (version, jurisdiction)
                    newelem=w.makeelement('version',None)
                    newelem.set('id',version)
                    j=''
                    if jurisdiction!='-':
                        j=jurisdiction+'/'
                    newelem.set('uri',"http://creativecommons.org/licenses/"+v.get('id')+'/'+version+'/'+j)
                    newelem.tail='\n'
                    w.append(newelem)
            if found==0:
                #If it's not there, add a new jurisdiction too.
                print "Creating new jurisdiction "+jurisdiction
                newelem=v.makeelement('jurisdiction',None)
                newelem.set('id',jurisdiction)
                newelem.text='\n'
                v.append(newelem)
                print "Adding version "+version+" to new jurisdiction "+jurisdiction
                newelem2=newelem.makeelement('version',None)
                newelem2.set('id',version)
                j=''
                if jurisdiction!='-':
                    j=jurisdiction+'/'
                newelem2.set('uri',"http://creativecommons.org/licenses/"+v.get('id')+'/'+version+'/'+j)
                newelem2.tail='\n'
                newelem.append(newelem2)



    #And write the new file.
    fileopen=open(filename,'w')
    tree.write(fileopen)

def addInfo(filename, jurisdiction, uri, languages, launched):

    tree=etree.ElementTree(file=filename)
    root=tree.getroot()

    j_node = tree.xpath('/license-info/jurisdictions/jurisdiction-info')
    j_node = [n for n in j_node
              if n.xpath('./@id="%s"' % jurisdiction)]

    # check if the jurisdiction doesn't exist at all yet
    if len(j_node) == 0:
        # add the jurisdiction node, defaulting to not launched
        print "Adding jurisdiction-info for %s..." % jurisdiction
        jurisdiction_parent = tree.xpath('/license-info/jurisdictions')[0]
        j_node = jurisdiction_parent.makeelement('jurisdiction-info',
                                                 {'launched':'false'})
        j_node.set('id', jurisdiction)
        
        jurisdiction_parent.append(j_node)
    else:
        j_node = j_node[0]

    # set the other values, if they were specified
    if launched is not None:
        print "Updating launched status for %s..." % jurisdiction
        
        j_node.set('launched', str(launched).lower())
        
    if languages != []:
        print "Updating languages for %s..." % jurisdiction
        
        lang_nodes = j_node.xpath('./languages')
        if len(lang_nodes) > 0:
            # a languages node already exists, remove it
            for ln in lang_nodes:
                j_node.remove(ln)
                
        # make a new languages node
        lang_node = j_node.makeelement('languages', None)
        lang_node.text = languages
        j_node.append(lang_node)

    if uri is not None:
        print "Updating URI for %s..." % jurisdiction
        
        uri_nodes = j_node.xpath('./uri')
        if len(uri_nodes) > 0:
            # a languages node already exists, remove it
            for u_node in uri_nodes:
                j_node.remove(u_node)
                
        # make a new languages node
        uri_node = j_node.makeelement('uri', None)
        uri_node.text = uri
        j_node.append(uri_node)
    
    #And write the new file.
    fileopen=open(filename,'w')
    tree.write(fileopen)

def main():
    """Run the licenses tool."""
    parser = makeOpts()
    opts, args = parser.parse_args()

    # make sure the appropriate commandline args were passed
    argdict = checkArgs(opts, args)

    if argdict is None:
       parser.print_help()
       sys.exit(1)
        
    if not(opts.infoOnly):
        addLicense(opts.filename,
                   argdict['jurisdiction'],
                   argdict['version'],
                   opts.licenses.split(','))
    else:
        addInfo(opts.filename,
                argdict['jurisdiction'],
                opts.juris_uri,
                opts.langs,
                opts.launched)

if __name__ == '__main__':
    main()
