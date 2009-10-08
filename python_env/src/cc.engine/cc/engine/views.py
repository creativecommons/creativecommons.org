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
    license_code = request.matchdict['license_code']
    license_version = request.matchdict['license_version']
    license_jurisdiction = request.matchdict.get('license_jurisdiction')
    license_action = request.matchdict.get('license_action')

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

    return Response(
        "this is the creative commons %s %s license" % (
            request.matchdict['license_code'],
            request.matchdict['license_version']))


def specific_licenses_rdf(context, request):
    return Response("RDF TIME")



def publicdomain_view(context, request):
    return Response("this is the public domain view")
