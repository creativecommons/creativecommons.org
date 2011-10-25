import string
import re

from cc.i18n.gettext_i18n import ugettext_for_locale
from cc.i18n.util import locale_to_lower_lower


WORK_FORMATS = {
    'Other': None,
    'Audio': 'Sound',
    'Video': 'MovingImage',
    'Image': 'StillImage',
    'Interactive': 'InteractiveResource'}

ANCHOR_START_RE = re.compile("""\<a .+ href=["'].+["']\>""", re.I)
ANCHOR_END_RE = re.compile("""</a>""", re.I)


def strip_href(input_str):
    """Take input_str and strip out the <a href='...'></a> tags."""
    result = ANCHOR_START_RE.sub("", input_str)
    result = ANCHOR_END_RE.sub("", result)

    return result


def workType(format):
    if format == "":
        return "work"

    if format not in WORK_FORMATS:
        return format

    return WORK_FORMATS[format] 


def get_xmp_info(request_form, license, locale):
    ugettext = ugettext_for_locale(locale)

    # assemble the necessary information for the XMP file before rendering
    year = ('field_year' in request_form and
            request_form['field_year']) or ""
    creator = ('field_creator' in request_form and
               request_form['field_creator']) or None
    work_type = workType(('field_format' in request_form and
                          request_form['field_format']) or "")
    work_url = ('field_url' in request_form and
                request_form['field_url']) or None

    # determine the license notice
    if ('publicdomain' in license.uri):
        notice = "This %s is dedicated to the public domain." % (work_type)
        copyrighted = False
    else:
        if creator:
            notice = "Copyright %s %s.  " % (year, creator,)
        else:
            notice = ""

        i18n_work = ugettext('util.work')
        work_notice_template = string.Template(
            ugettext('license.work_type_licensed'))
        work_notice = work_notice_template.substitute(
            {'license_name': license.title(locale_to_lower_lower(locale)),
             'license_url': license.uri,
             'work_type': i18n_work})

        notice = notice + work_notice

        copyrighted = True

    return {
        'copyrighted': copyrighted,
        'notice':notice,
        'license_url':license.uri,
        'license':license,
        'work_url':work_url}
