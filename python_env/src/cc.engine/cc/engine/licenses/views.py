from webob import exc

from lxml import etree
from lxml.cssselect import CSSSelector
from webob import Response

from cc.engine.decorators import get_license
from cc.engine import util
from cc.i18npkg import ccorg_i18n_setup
from cc.license import by_code, CCLicenseError
from cc.licenserdf.tools.license import license_rdf_filename


def licenses_view(request):
    return util.plain_template_view('catalog_pages/licenses-index.pt', request)


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
    'CC0': 'licenses/zero_deed.pt'}


@get_license
def license_deed_view(request, license):
    text_orientation = util.get_locale_text_orientation(request)

    # True if the legalcode for this license is available in
    # multiple languages (or a single language with a language code different
    # than that of the jurisdiction.
    #
    # ZZZ i18n information like this should really be stored outside of
    # the presentation layer; we don't maintain it anywhere right now, so
    # here it is.
    multi_language = license.jurisdiction in ('es', 'ca', 'be', 'ch', 'rs')

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

    identity_data = util.get_locale_identity_data(request)

    # TODO: Adjust this target_lang stuff!
    accept_lang_matches = request.accept_language.best_matches()
    if request.matchdict.has_key('target_lang'):
        target_lang = request.matchdict.get('target_lang')
    elif accept_lang_matches:
        target_lang = accept_lang_matches[0]
    else:
        target_lang = 'en'

    rdf_style_target_lang = target_lang.replace('_', '-').lower()

    license_title = None
    try:
        license_title = license.title(rdf_style_target_lang)
    except KeyError:
        # don't have one for that language, use default
        license_title = license.title()

    conditions = util.get_license_conditions(license, target_lang)

    active_languages = util.active_languages()

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
        'license_title': license_title,
        'license': license,
        'multi_language': multi_language,
        'color': color,
        'conditions': conditions,
        'deed_template': deed_template,
        'active_languages': active_languages,
        'support_template': support_template,
        'target_lang': target_lang}
    context.update(util.rtl_context_stuff(request))

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


def license_legalcode_redirect(request):
    # TODO: Use the legal code from the RDF instead of this hackery.
    if request.matchdict['code'] == 'MIT':
        return exc.HTTPTemporaryRedirect(
            location='http://opensource.org/licenses/mit-license.php')
    elif request.matchdict['code'] == 'BSD':
        return exc.HTTPTemporaryRedirect(
            location='http://opensource.org/licenses/bsd-license.php')
