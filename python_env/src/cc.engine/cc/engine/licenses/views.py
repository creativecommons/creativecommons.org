import re
import urllib

from lxml import etree
from lxml.cssselect import CSSSelector
from webob import Response, exc

from cc.engine.decorators import get_license
from cc.engine import util
from cc.i18n import ccorg_i18n_setup
from cc.i18n.util import get_well_translated_langs, negotiate_locale
from cc.license import by_code, CCLicenseError
from cc.licenserdf.tools.license import license_rdf_filename


def licenses_view(request):
    target_lang = util.get_target_lang_from_request(request)

    context = {'active_languages': get_well_translated_langs()}
    context.update(util.rtl_context_stuff(target_lang))

    # Don't cache the response for internationalization reasons
    response = Response(
        util.render_template(
            request, target_lang,
            'catalog_pages/licenses-index.html', context))
    response.headers.add('Cache-Control', 'no-cache')
    return response


def publicdomain_view(request):
    return util.plain_template_view('publicdomain/index.pt', request)


DEED_TEMPLATE_MAPPING = {
    'sampling': 'licenses/sampling_deed.pt',
    'sampling+': 'licenses/sampling_deed.pt',
    'nc-sampling+': 'licenses/sampling_deed.pt',
    'GPL': 'licenses/fsf_deed.pt',
    'LGPL': 'licenses/fsf_deed.pt',
    'MIT': 'licenses/mitbsd_deed.pt',
    'BSD': 'licenses/mitbsd_deed.pt',
    'devnations': 'licenses/devnations_deed.pt',
    'CC0': 'licenses/zero_deed.pt',
    'mark': 'licenses/pdmark_deed.pt',
    'publicdomain': 'licenses/publicdomain_deed.pt'}


# For removing the deed.foo section of a deed url
REMOVE_DEED_URL_RE = re.compile('^(.*?/)(?:deed)?(?:\..*)?$')


def license_deed_view(request):
    """
    The main and major deed generating view.
    """
    ##########################
    # Try and get the license.
    ##########################
    try:
        license = by_code(
            request.matchdict['code'],
            jurisdiction=request.matchdict.get('jurisdiction'),
            version=request.matchdict.get('version'))
    except CCLicenseError:
        license_versions = util.catch_license_versions_from_request(request)

        if license_versions:
            # If we can't get it, but others of that code exist, give
            # a special 404.
            return license_catcher(request)
        else:
            # Otherwise, give the normal 404.
            return exc.HTTPNotFound()

    ####################
    # Everything else ;)
    ####################
    # "color" of the license; the color reflects the relative amount
    # of freedom.
    if license.license_code in ('devnations', 'sampling'):
       color = 'red'
    elif license.license_code.find('sampling') > -1 or \
             license.license_code.find('nc') > -1 or \
             license.license_code.find('nd') > -1:
       color = 'yellow'
    else:
       color = 'green'

    # Get the language this view will be displayed in.
    #  - First checks to see if the routing matchdict specifies the language
    #  - Or, next gets the jurisdictions' default language if the jurisdiction
    #    specifies one
    #  - Otherwise it's english!
    if request.matchdict.has_key('target_lang'):
        target_lang = request.matchdict.get('target_lang')
    elif license.jurisdiction.default_language:
        target_lang = util.locale_to_cclicense_style(
            license.jurisdiction.default_language)
    else:
        target_lang = 'en'

    # True if the legalcode for this license is available in
    # multiple languages (or a single language with a language code different
    # than that of the jurisdiction).
    #
    # Stored in the RDF, we'll just check license.legalcodes() :)
    legalcodes = license.legalcodes(target_lang)
    if len(legalcodes) > 1 \
            or list(legalcodes)[0][2] is not None:
        multi_language = True
        legalcodes = sorted(legalcodes, key=lambda lc: lc[2])
    else:
        multi_language = False

    # Use the lower-dash style for all RDF-related locale stuff
    rdf_style_target_lang = target_lang.replace('_', '-').lower()

    license_title = None
    try:
        license_title = license.title(rdf_style_target_lang)
    except KeyError:
        # don't have one for that language, use default
        license_title = license.title()

    conditions = util.get_license_conditions(license, target_lang)

    # Find out all the active languages
    active_languages = get_well_translated_langs()
    negotiated_locale = negotiate_locale(target_lang)

    # If negotiating the locale says that this isn't a valid language,
    # let's fall back to something that is.
    if target_lang != negotiated_locale:
        base_url = REMOVE_DEED_URL_RE.match(request.path_info).groups()[0]
        redirect_to = base_url + 'deed.' + negotiated_locale
        return exc.HTTPMovedPermanently(
            location=redirect_to)

    # Use the pdtools deed macros template if CC0 or PD Mark, else use
    # standard deed macros template
    if license.license_code in ('mark', 'CC0'):
        deed_template = util.get_zpt_template(
            'macros_templates/pdtool_deed.pt',
            target_lang=target_lang)
    else:
        deed_template = util.get_zpt_template(
            'macros_templates/deed.pt',
            target_lang=target_lang)

    support_template = util.get_zpt_template(
        'macros_templates/support.pt',
        target_lang=target_lang)

    if DEED_TEMPLATE_MAPPING.has_key(license.license_code):
        main_template = util.get_zpt_template(
            DEED_TEMPLATE_MAPPING[license.license_code],
            target_lang=target_lang)
    else:
        main_template = util.get_zpt_template(
            'licenses/standard_deed.pt',
            target_lang=target_lang)

    context = {
        'request': request,
        'license_code': license.license_code,
        'license_code_quoted': urllib.quote(license.license_code),
        'license_title': license_title,
        'license': license,
        'multi_language': multi_language,
        'legalcodes': legalcodes,
        'color': color,
        'conditions': conditions,
        'deed_template': deed_template,
        'active_languages': active_languages,
        'support_template': support_template,
        'target_lang': target_lang}
    context.update(util.rtl_context_stuff(target_lang))

    return Response(main_template.pt_render(context))


@get_license
def license_rdf_view(request, license):
    rdf_response = Response(file(license_rdf_filename(license.uri)).read())
    rdf_response.headers['Content-Type'] = 'application/rdf+xml; charset=UTF-8'
    return rdf_response


@get_license
def license_legalcode_view(request, license):
    return Response('license legalcode')


@get_license
def license_legalcode_plain_view(request, license):
    parser = etree.HTMLParser()
    legalcode = etree.parse(
        license.uri + "legalcode", parser)

    # remove the CSS <link> tags
    for tag in legalcode.iter('link'):
        tag.getparent().remove(tag)

    # remove the img tags
    for tag in legalcode.iter("img"):
        tag.getparent().remove(tag)

    # remove anchors
    for tag in legalcode.iter('a'):
        tag.getparent().remove(tag)

    # remove //p[@id="header"]
    header_selector = CSSSelector('#header')
    for p in header_selector(legalcode.getroot()):
        p.getparent().remove(p)

    # add our base CSS into the mix
    etree.SubElement(
        legalcode.find("head"), "link",
        {"rel":"stylesheet",
         "type":"text/css",
         "href":"http://yui.yahooapis.com/2.6.0/build/fonts/fonts-min.css"})

    # return the serialized document
    return Response(etree.tostring(legalcode.getroot()))


# This function could probably use a better name, but I can't think of
# one!
def license_catcher(request):
    """
    If someone chooses something like /licenses/by/ (fails to select a
    version, etc) help point them to the available licenses.
    """
    target_lang = util.get_target_lang_from_request(request)

    template = util.get_zpt_template(
        'catalog_pages/license_catcher.pt', target_lang)
    engine_template = util.get_zpt_template(
        'macros_templates/engine_bare.pt', target_lang)

    license_versions = util.catch_license_versions_from_request(request)

    if not license_versions:
        return exc.HTTPNotFound()

    context = {'request': request,
               'engine_template': engine_template,
               'license_versions': reversed(license_versions),
               'license_class': license_versions[0].license_class}
    context.update(util.rtl_context_stuff(target_lang))

    # This is a helper page, but it's still for not-found situations.
    # 404!
    return Response(
        template.pt_render(context),
        status=404)


def moved_permanently_redirect(request):
    """
    General method for redirecting to something that's moved permanently
    """
    return exc.HTTPMovedPermanently(
        location=request.matchdict['redirect_to'])
