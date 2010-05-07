import re
from tempfile import TemporaryFile

from zope.interface import implementer
from zope.component import adapter
from zope.publisher.interfaces import IRequest
from zope.i18n import translate

import cc.engine.i18n
from cc.engine.interfaces import ILicenseEngine
from cc.engine.xmp.interfaces import IXMPPresentation


WORK_FORMATS = {
    'Other': None,
    'Audio': 'Sound',
    'Video': 'MovingImage',
    'Image': 'StillImage',
    'Interactive': 'InteractiveResource'}


def strip_href(input_str):
    """Take input_str and strip out the <a href='...'></a> tags."""

    result = re.compile("""\<a .+ href=["'].+["']\>""", re.I).sub("", input_str)
    result = re.compile("""</a>""", re.I).sub("", result)

    return result


def work_type(format):
    if format == "":
        return "work"

    if format not in WORK_FORMATS:
        return format

    return WORK_FORMATS[format] 


def get_xmp_info(request, license):
    # assemble the necessary information for the XMP file before rendering
    year = ('field_year' in request.form and
            request['field_year']) or ""
    creator = ('field_creator' in request.form and
               request['field_creator']) or None
    work_type = work_type(('field_format' in request.form and
                          request['field_format']) or "")
    work_url = ('field_url' in request.form and
                request['field_url']) or None

    # determine the license notice
    if ('publicdomain' in license.uri):
        notice = "This %s is dedicated to the public domain." % (work_type)
        copyrighted = False
    else:
        if creator:
            notice = "Copyright %s %s.  " % (year, creator,)
        else:
            notice = ""

        i18n_work = translate('util.work', domain=cc.engine.i18n.I18N_DOMAIN)
        work_notice = strip_href(
            translate('license.work_type_licensed',
                      domain=cc.engine.i18n.I18N_DOMAIN,
                      mapping={'license_name':license.name,
                               'license_url':license.uri,
                               'work_type':i18n_work}))

        notice = notice + work_notice

        copyrighted = True

    return {
        'copyrighted': copyrighted,
        'notice':notice,
        'license_url':license.uri,
        'license':license,
        'work_url':work_url}


@adapter(ILicenseEngine, IRequest)
@implementer(IXMPPresentation)
def license_xmp_template(context, request):
    xmp_info = get_xmp_info(request, context.issue(request))
    temp_file = TemporaryFile()
    
    # assemble the XMP
    temp_file.write(u"""<?xpacket begin='' id=''?><x:xmpmeta xmlns:x='adobe:ns:meta/'>
    <rdf:RDF xmlns:rdf='http://www.w3.org/1999/02/22-rdf-syntax-ns#'>

     <rdf:Description rdf:about=''
      xmlns:xapRights='http://ns.adobe.com/xap/1.0/rights/'>
      <xapRights:Marked>%(copyrighted)s</xapRights:Marked>""" % xmp_info)

    if xmp_info['work_url'] != None:
        temp_file.write(
            """  <xapRights:WebStatement rdf:resource='%(work_url)s'/>""" % xmp_info)
        
    temp_file.write(""" </rdf:Description>

     <rdf:Description rdf:about=''
      xmlns:dc='http://purl.org/dc/elements/1.1/'>
      <dc:rights>
       <rdf:Alt>
        <rdf:li xml:lang='x-default' >%(notice)s</rdf:li>
       </rdf:Alt>
      </dc:rights>
     </rdf:Description>

     <rdf:Description rdf:about=''
      xmlns:cc='http://creativecommons.org/ns#'>
      <cc:license rdf:resource='%(license_url)s'/>
     </rdf:Description>

    </rdf:RDF>
    </x:xmpmeta>
    <?xpacket end='r'?>
    """ % xmp_info)

    return temp_file
