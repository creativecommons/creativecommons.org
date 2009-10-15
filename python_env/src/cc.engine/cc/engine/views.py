from webob import Response

from repoze.bfg.chameleon_zpt import render_template_to_response

from cc.engine import util
from cc.license import by_code, CCLicenseError


class FakeView(object):
    """
    Currently just used to satisfy the rtl stuff.  We need to get rid
    of this.
    """
    is_rtl =  False

    def get_ltr_rtl(self):
        return None

    def is_rtl_align(self):
        return None


def root_view(context, request):
    return Response("This is the root")


def licenses_view(context, request):
    template = util.get_zpt_template(
        'catalog_pages/licenses-index.pt')
    engine_template = util.get_zpt_template(
        'macros_templates/engine.pt')

    fake_view = FakeView()

    return Response(
        template.pt_render(
            {'request': request,
             'engine_template': engine_template,
             'view': fake_view,
             'context': context}))


def specific_licenses_router(context, request):
    """
    """
    # Router isn't the right name here.  But I can't think fo a better
    # name :\
    license_code = request.matchdict['code']
    license_version = request.matchdict['version']
    license_jurisdiction = request.matchdict.get('jurisdiction')
    license_action = request.matchdict.get('action')

    ambiguous_jurisdiction_or_action = request.matchdict.get(
        'jurisdiction_or_action')
    if ambiguous_jurisdiction_or_action:
        if ambiguous_jurisdiction_or_action in ('rdf', 'legalcode'):
            license_action = ambiguous_jurisdiction_or_action
        else:
            license_jurisdiction = str(ambiguous_jurisdiction_or_action)

    try:
        license = by_code(
            license_code,
            jurisdiction=license_jurisdiction,
            version=license_version)
    except CCLicenseError:
        ### give a proper errored httpresponse
        return Response(
            "No such license.")

    if license_action:
        if license_action == 'rdf':
            return license_rdf_view(
                context, request, license,
                license_code, license_version, license_jurisdiction)
        elif license_action == 'legalcode':
            return license_legalcode_view(
                context, request, license,
                license_code, license_version, license_jurisdiction)
        else:
            # TODO: This isn't the right thing to do, obviously.
            return Response("No such action :(")
    else:
        return license_deed_view(
            context, request, license,
            license_code, license_version, license_jurisdiction)


def license_deed_view(context, request, license,
                      license_code, license_version, license_jurisdiction):
    text_orientation = util.get_locale_text_orientation(request)

    # 'rtl' if the request locale is represented right-to-left;
    # otherwise an empty string.

    is_rtl = text_orientation == 'rtl'

    # Return the appropriate alignment for the request locale:
    # 'text-align:right' or 'text-align:left'.
    if text_orientation == 'rtl':
        is_rtl_align = 'text-align: right'
    else:
        is_rtl_align = 'text-align: left'

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
    if license_code in ('devnations', 'sampling'):
       color = 'red'
    elif license_code.find('sampling') > -1 or \
             license_code.find('nc') > -1 or \
             license_code.find('nd') > -1:
       color = 'yellow'
    else:
       color = 'green'

    identity_data = util.get_locale_identity_data(request)

    # TODO: use lang from util.get_locale_file_from_lang_matches, but
    #   only get it once..
    lang_matches = request.accept_language.best_matches()[0]
    target_lang = lang_matches[0]
    conditions = util.get_license_conditions(license, target_lang)

    template = util.get_zpt_template('licenses/standard_templates/deed.pt')

    return Response(
        template.pt_render(
            {'license_code': license_code,
             'license_version': license_version,
             'license': license,
             'get_ltr_rtl': text_orientation,
             'is_rtl_align': is_rtl_align,
             'multi_language': multi_language,
             'color': color,
             'conditions': conditions}))


def license_rdf_view(context, request, license,
                     license_code, license_version, license_jurisdiction):
    return Response('license rdf')


def license_legalcode_view(context, request, license,
                      license_code, license_version, license_jurisdiction):
    return Response('license legalcode')



def publicdomain_view(context, request):
    return Response("this is the public domain view")
